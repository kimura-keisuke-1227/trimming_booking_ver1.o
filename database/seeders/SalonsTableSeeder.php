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
            'salon_name' => '流星台店' ,
            'prefecture' =>  '茨城県',
            'address1' =>  'つくば市流星台',
            'address2' =>  '123-456',
            'phone' => '0120-444-444' ,
            'st_time' => 600 ,
            'ed_time' => 1200 ,
        ];
        DB::table('salons') -> insert($param);

        $param = [
            'salon_name' => '万博公園店' ,
            'prefecture' =>  '茨城県',
            'address1' =>  'つくば市流星台',
            'address2' =>  '234-567',
            'phone' => '0120-444-444' ,
            'st_time' => 600 ,
            'ed_time' => 1200 ,
        ];
        DB::table('salons') -> insert($param);

        $param = [
            'salon_name' => '越谷店' ,
            'prefecture' =>  '埼玉県',
            'address1' =>  '越谷市東越谷',
            'address2' =>  '345-678',
            'phone' => '0120-444-444' ,
            'st_time' => 600 ,
            'ed_time' => 1200 ,
        ];
        DB::table('salons') -> insert($param);
    }
}
