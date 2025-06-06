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
        $password = 'admin_password';
        $password = Hash::make($password);
        $param =[
            'last_name' => '管理',
            'last_name_kana' => 'カンリ',
            'first_name' => '者',
            'first_name_kana' => 'シャ',
            'email' => 'admin@admin.co.jp' ,
            'phone' => '0120-000-000' ,
            'auth' => 1,
            'password' => $password,
            'default_salon' => 1,
        ];
        DB::table('users') -> insert($param);

        $password = 'kimurapass';
        $password = Hash::make($password);
        $param =[
            'last_name' => '木村',
            'last_name_kana' => 'カンリ',
            'first_name' => '管理',
            'first_name_kana' => 'シャ',
            'email' => 'kim.ksuke@gmail.com' ,
            'phone' => '0120-000-000' ,
            'auth' => 1,
            'password' => $password,
            'default_salon' => 1,
        ];
        DB::table('users') -> insert($param);
        
        $password = 'kimurapass';
        $password = Hash::make($password);
        $param =[
            'last_name' => '木村',
            'last_name_kana' => 'カンリ',
            'first_name' => '飼い主',
            'first_name_kana' => 'シャ',
            'email' => 'keisukekimura.create@gmail.com' ,
            'phone' => '0120-000-000' ,
            'auth' => 0,
            'password' => $password,
            'default_salon' => 1,
        ];
        DB::table('users') -> insert($param);
        /*
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => 'テストユーザー',
            'last_name_kana' => 'テストユーザー',
            'first_name' => '２号',
            'first_name_kana' => 'ニゴウ',
            'email' => '222@gmail.com' ,
            'phone' => '0120-222-222' ,
            'password' => $password,
            'default_salon' => 2,
            'auth' => 0,
            'cameBefore' => 1
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
            'auth' => 0,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'last_name' => 'テストユーザー',
            'last_name_kana' => 'テストユーザー',
            'first_name' => '４号',
            'first_name_kana' => 'ヨンゴウ',
            'email' => '444@gmail.com' ,
            'phone' => '0120-444-444' ,
            'password' => $password,
            'default_salon' => 3,
            'auth' => 0,
        ];
        DB::table('users') -> insert($param);
        */
        
    }
}