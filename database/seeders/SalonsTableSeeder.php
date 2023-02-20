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
            'salon_name' => '茜店' ,
            'prefecture' =>  '埼玉県',
            'address1' =>  '越谷市',
            'address2' =>  '123-456',
            'phone' => '048-918-2163' ,
            'email' => 'trimmingsalonlink2@gmail.com' ,
            'st_time' => 600 ,
            'ed_time' => 1080 ,
        ];
        DB::table('salons') -> insert($param);

    }
}
