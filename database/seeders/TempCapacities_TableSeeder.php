<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TempCapacities_TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'st_time' => 900,
            'ed_date' => '2022-09-01',
            'ed_time' => 1440,
            'capacity' => 0

        ];
        DB::table('temp_capacities') -> insert($param);
    }
}
