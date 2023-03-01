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
            'order' => 10 ,
            'setting_name' => 'showBookingsAfterNDays' ,
            'explain' => 'ユーザーに○日前までの予約を表示する。' ,
            'setting_int' =>  60,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);

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
            'order' => 50 ,
            'setting_name' => 'mailSenderName' ,
            'explain' => '自動送信のメールの差出人名称' ,
            'setting_string' =>  'テスト管理者',
            'isNumber' => false,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'order' => 60 ,
            'setting_name' => 'mailFromSalon' ,
            'explain' => '自動送信のメールアドレス' ,
            'setting_string' =>  'support@trimmingsalon-link.com',
            'isNumber' => false,
        ];
        DB::table('settings') -> insert($param);
        $param = [
            'order' => 70 ,
            'setting_name' => 'salon_name_login' ,
            'explain' => 'ログイン画面に表示するサロン名' ,
            'setting_string' =>  'トリミングサロンLINK',
            'isNumber' => false,
        ];
        DB::table('settings') -> insert($param);

        $param = [
            'order' => 80 ,
            'setting_name' => 'delete_open_close_date_Xdays_before' ,
            'explain' => '予約受付可否データの保存日数（日）' ,
            'setting_int' =>  30,
            'isNumber' => true,
        ];
        DB::table('settings') -> insert($param);
    }
}
