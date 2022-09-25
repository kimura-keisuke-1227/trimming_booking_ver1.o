<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ms_booking_StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'status_cd' => 1,
            'status' => '予約確定'
        ];
        DB::table('ms_booking_status') -> insert($param);
        $param =[
            'status_cd' => 2,
            'status' => '承認待'
        ];
        DB::table('ms_booking_status') -> insert($param);
        $param =[
            'status_cd' => 9,
            'status' => 'キャンセル'
        ];
        DB::table('ms_booking_status') -> insert($param);
    }
}
