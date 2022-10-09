<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DogtypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'type' => 'イタリアングレーハウンド',
            'order' => 10
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'チワワ（スムース）',
            'order' => 20
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'チワワ',
            'order' => 30
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ダックス（スムース）',
            'order' => 40
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ダックス',
            'order' => 50
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'パグ',
            'order' => 60
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'マルチーズ',
            'order' => 70
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ヨークシャーテリア',
            'order' => 80
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'シーズー',
            'order' => 90
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'パピヨン',
            'order' => 100
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ポメラニアン',
            'order' => 110
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ペキニーズ',
            'order' => 120
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'プードル',
            'order' => 130
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'シュナウザー',
            'order' => 140
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => '柴',
            'order' => 150
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ピーグル',
            'order' => 160
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'アメリカンコッカー',
            'order' => 170
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ラブラドール',
            'order' => 180
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ボーダーコリー',
            'order' => 190
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'ゴールデン',
            'order' => 200
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'その他(小型犬)',
            'order' => 210
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'その他(中型犬)',
            'order' => 220
        ];
        DB::table('dogtypes') -> insert($param);
        $param =[
            'type' => 'その他(大型犬)',
            'order' => 230
        ];
        DB::table('dogtypes') -> insert($param);
    }
}
