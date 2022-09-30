<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RegularHoliday;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this -> call(Ms_booking_StatusTableSeeder::class);
        $this -> call(DogtypesTableSeeder::class);
        $this -> call(CoursesTableSeeder::class);
        $this -> call(CourseMastersTableSeeder::class);

        $this -> call(UserTableSeeder::class);
        $this -> call(PetsTableSeeder::class);
        $this -> call(BookingsTableSeeder::class);
        $this -> call(SalonsTableSeeder::class);
        $this -> call(Default_Capacities_TableSeeder::class);
        $this -> call(TempCapacities_TableSeeder::class);
        $this -> call(RegularHolidaysTableSeeder::class);
    }
}
