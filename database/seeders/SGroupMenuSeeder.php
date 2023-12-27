<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SGroupMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = [];
        for($i = 1; $i <= 15; $i++){
            $menu[] = [
                'group_id'  => 1,
                'menu_id'   => $i,
                'c'   => 1,
                'r'    => 1,
                'u'   => 1,
                'd' => 1,
            ];
        }

        DB::table('s_group_menu')->insert($menu);
    }
}
