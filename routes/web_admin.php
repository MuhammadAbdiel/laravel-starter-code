<?php

use App\Http\Controllers\Master\BeritaController;
use App\Http\Controllers\Master\DosenCircleController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\JurusanController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\MahasiswaController;
use App\Http\Controllers\Master\PeriodeController;
use App\Http\Controllers\Master\ProdiController;
use App\Http\Controllers\Master\TahapanProposalController;
use App\Http\Controllers\Proposal\AdminHasilSeminarProposalController;
use App\Http\Controllers\Proposal\AdminPendaftaranSemproController;
use App\Http\Controllers\Proposal\AdminProposalMahasiswaBermasalahController;
use App\Http\Controllers\Proposal\AdminProposalMahasiswaController;
use App\Http\Controllers\Proposal\AdminUsulanTopikController;
use App\Http\Controllers\Report\LogActivityController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\GroupController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Transaction\QuotaDosenController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'master', 'middleware' => ['auth']], function () {

    // Jurusan
    Route::resource('jurusan', JurusanController::class)->parameter('jurusan', 'id');
    Route::post('jurusan/list', [JurusanController::class, 'list']);
    Route::get('jurusan/{id}/delete', [JurusanController::class, 'confirm']);

    // Prodi
    Route::resource('prodi', ProdiController::class)->parameter('prodi', 'id');
    Route::post('prodi/list', [ProdiController::class, 'list']);
    Route::get('prodi/{id}/delete', [ProdiController::class, 'confirm']);
});

Route::group(['prefix' => 'setting', 'middleware' => ['auth']], function () {

    // Menu
    Route::resource('menu', MenuController::class)->parameter('menu', 'id');
    Route::post('menu/list', [MenuController::class, 'list']);
    Route::get('menu/{id}/delete', [MenuController::class, 'confirm']);

    // Group
    Route::resource('group', GroupController::class)->parameter('group', 'id');
    Route::post('group/list', [GroupController::class, 'list']);
    Route::put('group/{id}/menu', [GroupController::class, 'menu_save']);
    Route::get('group/{id}/delete', [GroupController::class, 'confirm']);

    // User
    Route::resource('user', UserController::class)->parameter('user', 'id');
    Route::post('user/list', [UserController::class, 'list']);
    Route::get('user/{id}/delete', [UserController::class, 'confirm']);
});
