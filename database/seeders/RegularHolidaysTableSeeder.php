<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegularHolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'dayOfWeek' => 3,
        ];
        DB::table('regular_holidays') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'dayOfWeek' => 4,
        ];
        DB::table('regular_holidays') -> insert($param);

    }
}
