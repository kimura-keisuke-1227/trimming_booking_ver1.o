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
            'order' => 20 ,
            'setting_name' => 'step_time' ,
            'explain' => '予約を○分刻みで受け付けるか。' ,
            'setting_int' =>  30,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'order' => 30 ,
            'setting_name' => 'untilTheDayAhead' ,
            'explain' => '○日先まで予約を受け入れるか。' ,
            'setting_int' =>  30,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'order' => 40 ,
            'setting_name' => 'deadTimeForBooking' ,
            'explain' => '営業時間終了の○分前まで' ,
            'setting_int' =>  30,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'order' => 10 ,
            'setting_name' => 'showBookingsAfterNDays' ,
            'explain' => 'ユーザーに○日前までの予約を表示する。' ,
            'setting_int' =>  60,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);
    }
}
