<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'setting_name' => 'step_time' ,
            'setting_int' =>  30,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'setting_name' => 'untilTheDayAhead' ,
            'setting_int' =>  30,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'setting_name' => 'deadTimeForBooking' ,
            'setting_int' =>  30,
        ];
        DB::table('settings') -> insert($param);
    }
}
