<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Default_Capacities_TableSeeder extends Seeder
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
            'capacity' => 2,
            'dayOfWeek' => 0, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 1, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 2, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 0,
            'dayOfWeek' => 3, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 4, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 5, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 1,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 6, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 0, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 1, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 2, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 3, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 4, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 5, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 2,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 6, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 0, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 1, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 2, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 3, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 4, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 2,
            'dayOfWeek' => 5, 
        ];
        DB::table('default_capacities') -> insert($param);
        $param =[
            'salon_id' => 3,
            'st_date' => '2022-09-01',
            'capacity' => 3,
            'dayOfWeek' => 6, 
        ];
        DB::table('default_capacities') -> insert($param);
      
    }
}
