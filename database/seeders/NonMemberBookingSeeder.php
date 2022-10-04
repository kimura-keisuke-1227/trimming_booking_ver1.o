<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NonMemberBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param =[
            'booking_id' => 1,
            'last_name' => '非会員' ,
            'last_name_kana' => 'ヒカイイン',
            'first_name' => '太郎',
            'first_name_kana' => 'タロウ',
            'email' => 'hikaiin1@gmail.com',
            'phone' => '022-222-2222',
            'name' => '非会員の犬',
        ];
        DB::table('non_member_bookings') -> insert($param);

        $param =[
            'booking_id' => 2,
            'last_name' => '非会員' ,
            'last_name_kana' => 'ヒカイイン',
            'first_name' => '太郎',
            'first_name_kana' => 'タロウ',
            'email' => 'hikaiin1@gmail.com',
            'phone' => '022-222-2222',
            'name' => '非会員の犬',
        ];
        DB::table('non_member_bookings') -> insert($param);
    }
}
