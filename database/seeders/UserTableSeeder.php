<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => '管理者',
            'last_name_kana' => '１号',
            'first_name' => 'カンリシャ',
            'first_name_kana' => 'イチゴウ',
            'email' => '000@gmail.com' ,
            'phone' => '0120-000-000' ,
            'password' => $password,
            'default_salon' => 1,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => 'テストユーザー',
            'last_name_kana' => '２号',
            'first_name' => 'テストユーザー',
            'first_name_kana' => 'ニ「ゴウ',
            'email' => '222@gmail.com' ,
            'phone' => '0120-222-222' ,
            'password' => $password,
            'default_salon' => 2,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => 'テストユーザー',
            'last_name_kana' => '３号',
            'first_name' => 'テストユーザー',
            'first_name_kana' => 'サンゴウ',
            'email' => '333@gmail.com' ,
            'phone' => '0120-333-333' ,
            'password' => $password,
            'default_salon' => 3,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => 'テストユーザー',
            'last_name_kana' => '4号',
            'first_name' => 'テストユーザー',
            'first_name_kana' => 'ヨンゴウ',
            'email' => '444@gmail.com' ,
            'phone' => '0120-444-444' ,
            'password' => $password,
            'default_salon' => 3,
        ];
        DB::table('users') -> insert($param);

        
    }
}