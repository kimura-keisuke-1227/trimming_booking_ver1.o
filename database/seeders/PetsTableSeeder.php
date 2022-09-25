<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class PetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'owner_id' => 2,
            'dogtype_id' => 1 ,
            'name' => 'テスト犬１号',
            'birthday' => '2020-3-31',
        ];
        DB::table('pets') -> insert($param);
        $param =[
            'owner_id' => 2,
            'dogtype_id' => 20,
            'name' => 'テスト犬2号',
            'birthday' => '2020-3-31',
        ];
        DB::table('pets') -> insert($param);
        $param =[
            'owner_id' => 3,
            'dogtype_id' => 10 ,
            'name' => 'テスト犬3号',
        ];
        DB::table('pets') -> insert($param);
    }
}
