<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'salon_name' => 'テスト店舗' ,
            'prefecture' =>  '広島県',
            'address1' =>  'テスト市',
            'address2' =>  '123-456',
            'phone' => '0120-444-444' ,
            'email' => 'kim.ksuke@gmail.com' ,
            'st_time' => 600 ,
            'ed_time' => 1080 ,
        ];
        DB::table('salons') -> insert($param);

    }
}
