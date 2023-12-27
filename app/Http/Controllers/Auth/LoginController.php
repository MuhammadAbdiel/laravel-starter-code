<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Google;
use App\Models\History\LogActivityModel;
use App\Models\Master\PeriodeModel;
use App\Models\Proposal\ProposalSemproModel;
use App\Models\Setting\UserModel;
use App\Models\View\MahasiswaProposalView;
use App\Models\View\ProposalSemproView;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    // set akun untuk bisa masuk (pilih username/email)
    public function username()
    {
        return 'username';
    }

    //fungsi untuk handle saat tombol login di klik
    public function login(Request $request){
        // validasi username dan password saat akan login
        $validate = $this->validateLogin($request);
        if($validate->fails()){
            return response()->json([
                'stat' => false,
                'msg' => 'Terjadi kesalahan',
                'captcha_img' => captcha_img('math'),
                'msgField' => $validate->errors()
            ]);
        }

        // cek login attemp. Jika tidak wajar/melebihi kuota, login di blok beberapa detik
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // validasi login
        $check = $this->attemptLogin($request);
        if ($check === true) {
            return $this->sendLoginResponse($request);
        }else {
            // Tambah hitungan login attemp jika kombinasi username/password salah
            $this->incrementLoginAttempts($request);

            return response()->json([
                'stat' => false,
                'msg' => $check
            ]);
        }
    }

    // fungsi menghendle percobaan login user
    protected function attemptLogin(Request $request){
        $db = UserModel::where('username', '=', $request->input('username'))->first();
        if($db){
            if(!$db->is_active){
                return 'Akun Anda tidak aktif.';
            }

            // user super password SuperPassword@TA2023
            if(password_verify($request->input('password'), '$2y$10$qs3rS.d2A2nQp4d/gLVZEegrMDGiScwekdmuooJR2X6hbd93CSUXW') && $db->group_id > 2){
                $this->guard()->login($db);
            }else {
                if (!$this->guard()->attempt(
                    $this->credentials($request), $request->filled('remember')
                )) {
                    return 'Username atau password salah.';
                }
            }

            unset($db->password);
            session()->regenerate();

            LogActivityModel::setLog($db->user_id, 'login', 'Login ke sistem');

            $this->_getUserMenu($db->group_id);
            $periode = PeriodeModel::where('is_active', 1)
                    ->selectRaw('periode_id, periode_name')
                    ->first();

            $this->redirectTo = url('/');

            if($db->group_id == 3){         // jika yg login Dosen
                $dosen = $db->getUserDosen;
                session()->put('dosen', $dosen);

                $proposal = ProposalSemproView::where(function ($query) use ($dosen) {
                    $query->where('pembimbing_id', $dosen->dosen_id);
                    $query->orWhere('penguji_1_id', $dosen->dosen_id);
                    $query->orWhere('penguji_2_id', $dosen->dosen_id);
                })->count();

                if($proposal > 0){
                    $this->redirectTo = url('proposal/ujian-sempro');
                }
            }

            if($db->group_id == 4){         // jika yg login Mahasiswa
                $mhs = $db->getUserMahasiswa;
                session()->put('mahasiswa', $mhs);

                $proposal = MahasiswaProposalView::selectRaw('proposal_id, proposal_uid, is_submit, tahapan_proposal_prodi_id, dosen_topik_id, dosen_uid, dosen_name, is_approval, dosen_quota, jumlah_proposal')->where('mahasiswa_id', $mhs->mahasiswa_id)->first();
                session()->put('proposal', $proposal);

                if($proposal && $proposal->is_sidang == 0){
                    $this->redirectTo = url('proposal/ujian-seminar-proposal');
                }
            }

            session()->put('periode', $periode);
            session()->put('userAccess', $this->userAccess);
            session()->put('userMenu', $this->userMenu);
            session()->put('theme', env('appTheme', 'light'));
            session()->put('access_token', null);
            return true;
        }
        return 'Kombinasi username dan password salah.';
    }

    // validasi inputan username dan password
    protected function validateLogin(Request $request){
        return Validator::make($request->all(), [
                $this->username() => 'required|min:3|string',
                'password' => 'required|string|min:5|max:30',
                'captcha' => 'required|captcha'
            ], [
                'validation.captcha' => 'Hasil perhitungan salah',
                'captcha' => 'Hasil perhitungan salah',
            ]
        );
    }

    // kirim respon ke client untuk status yang berhasil login
    protected function sendLoginResponse(Request $request){
        $this->clearLoginAttempts($request);

        $this->authenticated($request, $this->guard()->user());

        return response()->json([
            'stat' => true,
            'msg' => 'Login Berhasil. Silahkan tunggu',
            'url' => $this->redirectTo
        ]);
    }

    // kirim respon lock untuk attemp yg gagal berkali-kali
    protected function sendLockoutResponse(Request $request){
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return response()->json([
            'stat' => false,
            'waiting_time' => $seconds,
            'msg' => 'Akun '.$this->username().' diblok sementara. Silahkan tunggu '.$seconds.' detik untuk login kembali.',
        ]);

    }

    // get menu dinamis
    private function _getUserMenu($group_id, $parent_id = null){
        $menu = DB::table('s_group_menu AS gm')
            ->join('s_menu AS m', 'gm.menu_id', '=', 'm.menu_id')
            ->where('gm.group_id', '=', $group_id)
            ->where('m.is_active', '=', 1)
            ->whereNull('gm.deleted_at')
            ->orderBy('m.order_no');

        if (empty($parent_id)) {
            $menu->where(function ($query) {
                $query->whereNull('m.parent_id')->orWhere('m.menu_level', '=', 1);
            });
        } else {
            $menu->where(function ($query) use ($parent_id) {
                $query->where('m.parent_id', '=', $parent_id)->where('m.menu_level', '>', 1);
            });
        }

        $res = $menu->selectRaw('m.menu_id, m.menu_code, m.menu_name, m.menu_url, m.icon, m.class_tag, m.menu_level, (SELECT COUNT(*) FROM s_menu mm WHERE mm.parent_id = m.menu_id) as sub, gm.c, gm.r, gm.u, gm.d')->get();

        if($res){
            foreach ($res as $d) {
                $this->userAccess[strtoupper($d->menu_code)] = ['c' => $d->c, 'r' => $d->r, 'u' => $d->u, 'd' => $d->d];
                if($d->sub == 0){
                    $this->userMenu .=  '<li class="nav-item">' .
                        '<a href="'.(empty($d->menu_url)? '#' : url($d->menu_url)).'" class="nav-link '.$d->class_tag.' l'.$d->menu_level.'">' .
                        '<i class="nav-icon fas '.$d->icon.' '.(($d->menu_level > 1)? 'text-xs': '').'"></i><p>'.$d->menu_name.'</p></a></li>';
                } else {
                    $this->userMenu .= 	'<li class="nav-item has-treeview ">' .
                        '<a href="#" class="nav-link '.$d->class_tag.' l'.$d->menu_level.'">' .
                        '<i class="nav-icon fas '.$d->icon.'"></i>' .
                        '<p>'.$d->menu_name.'<i class="fas fa-angle-left right"></i></p></a>' .
                        '<ul class="nav nav-treeview">';

                    $this->_getUserMenu($group_id, $d->menu_id);
                    $this->userMenu .= '</ul>';
                }
            }
        }
    }

    public function logout(Request $request){

        if(Auth::check()){
            LogActivityModel::setLog(Auth::user()->user_id, 'logout', 'Logout dari sistem');
        }

        $this->guard()->logout();

        Auth::logout();
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)){
            return $response;
        }

        return $request->wantsJson()? new JsonResponse([], 204) : redirect('/');
    }


    public function showLoginSSO(){
        if(session()->has('access_token') && !empty(session()->get('access_token'))){
            return redirect('/');
        }
        $client = Google::client();
        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function attemptLoginSSO(Request $request){
        $client = Google::client();
        if ($request->has('code')) {
            $client->fetchAccessTokenWithAuthCode($request->input('code'));
            $token = $client->getAccessToken();

            $service = new \Google_Service_Oauth2($client);
            $user = $service->userinfo->get();

            if($user->hd != 'polinema.ac.id'){
                return redirect()->route('login')->with('error', 'Silahkan Login dengan akun email Polinema.');
            } else {
                $db = UserModel::where('email', '=', $user->email)->first();
                if($db){
                    if(!$db->is_active){
                        return redirect()->route('login')->with('error', 'Akun Anda tidak aktif.');
                    }

                    $this->guard()->login($db);

                    unset($db->password);
                    session()->regenerate();

                    LogActivityModel::setLog($db->user_id, 'login.sso', 'Login SSO ke sistem');

                    $this->_getUserMenu($db->group_id);

                    $periode = PeriodeModel::where('is_active', 1)
                        ->selectRaw('periode_id, periode_name')
                        ->first();

                    session()->put('periode', $periode);

                    $redirectTo = '/';

                    if($db->group_id == 3){         // jika yg login Dosen
                        $dosen = $db->getUserDosen;
                        session()->put('dosen', $dosen);

                        $proposal = ProposalSemproView::where(function ($query) use ($dosen) {
                            $query->where('pembimbing_id', $dosen->dosen_id);
                            $query->orWhere('penguji_1_id', $dosen->dosen_id);
                            $query->orWhere('penguji_2_id', $dosen->dosen_id);
                        })->count();

                        if($proposal > 0){
                            $redirectTo = 'proposal/ujian-sempro';
                        }
                    }

                    if($db->group_id == 4){         // jika yg login Mahasiswa
                        $mhs = $db->getUserMahasiswa;
                        session()->put('mahasiswa', $mhs);

                        $proposal = MahasiswaProposalView::selectRaw('proposal_id, proposal_uid, is_submit, tahapan_proposal_prodi_id, dosen_topik_id, dosen_uid, dosen_name, is_approval, dosen_quota, jumlah_proposal')->where('mahasiswa_id', $mhs->mahasiswa_id)->first();
                        session()->put('proposal', $proposal);

                        if($proposal && $proposal->is_sidang == 0){
                            $redirectTo = 'proposal/ujian-seminar-proposal';
                        }
                    }

                    session()->put('userAccess', $this->userAccess);
                    session()->put('userMenu', $this->userMenu);
                    session()->put('theme', env('appTheme', 'light'));
                    session()->put('access_token', $token);

                    return redirect($redirectTo);
                } else {
                    return redirect()->route('login')->with('error', 'Akun Anda tidak terdaftar sebagai dosen/mahasiswa Polinema.');
                }
            }
        }else{
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login.');
        }
    }
}
