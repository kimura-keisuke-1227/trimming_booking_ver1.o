<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $password = 'admin_pass';
        $password = Hash::make($password);
        $param =[
            'last_name' => '管理者',
            'last_name_kana' => 'カンリシャ',
            'first_name' => 'アカウント',
            'first_name_kana' => 'アカウント',
            'email' => 'admin@admin.com' ,
            'phone' => '0120-000-000' ,
            'password' => $password,
            'default_salon' => 1,
            'auth' => 1,
        ];
        DB::table('users') -> insert($param);

        
    }
}
