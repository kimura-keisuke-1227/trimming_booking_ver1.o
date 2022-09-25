<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ms_coursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'course' => 'シャンプー',
            'order' => 10
        ];
        DB::table('ms_courses') -> insert($param);
        
        $param =[
            'course' => 'シャンプー・カット',
            'order' => 20
        ];
        DB::table('ms_courses') -> insert($param);
    }
}
