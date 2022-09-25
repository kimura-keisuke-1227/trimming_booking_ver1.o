<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $param = [
            'date' => '2022-9-21' ,
            'st_time' =>  600,
            'ed_time' =>  720,
            'pet_id' => 1 ,
            'course_id' => 2 ,
            'salon_id' => 2,
            'price' => 3000 ,
            'booking_status' => 1 ,
        ];
        DB::table('bookings') -> insert($param);

        $param = [
            'date' => '2022-9-21' ,
            'st_time' =>  600,
            'ed_time' =>  750,
            'pet_id' => 2 ,
            'course_id' => 2 ,
            'salon_id' => 2,
            'price' => 3000 ,
            'booking_status' => 1 ,
        ];
        DB::table('bookings') -> insert($param);

    }
}
