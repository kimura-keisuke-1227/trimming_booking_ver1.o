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
            'name' => '管理者',
            'email' => '000@gmail.com' ,
            'phone' => '0120-000-000' ,
            'password' => $password,
            'default_salon' => 2,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'name' => 'テストユーザー２',
            'email' => '111@gmail.com' ,
            'phone' => '0120-222-222' ,
            'password' => $password,
            'default_salon' => 2,            
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'name' => 'テストユーザー３号',
            'email' => '333@gmail.com' ,
            'phone' => '0120-333-333' ,
            'password' => $password,

            'default_salon' => 3,
        ];
        DB::table('users') -> insert($param);
        
        $password = '12345678';
        $password = Hash::make($password);
        $param =[
            'name' => 'テストユーザー4号',
            'email' => '444@gmail.com' ,
            'phone' => '0120-444-444' ,
            'password' => $password,

            'default_salon' => 4,
        ];
        DB::table('users') -> insert($param);

        
    }
}