<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Course;

class BookingController extends Controller
{
    public function index(Request $request){
        $items = Booking::all();
        return view('bookings.index' , ['items' => $items]);
    }

    public function selectpet(){
        $owner = Owner::where('id',2) -> first();
        session([
            'owner' => $owner,
        ]);
        $pets = Pet::where('owner_id' , $owner -> id) -> get();
        session(['pets' => $pets]);
        //return redirect('/selectcourse');
        
        return view('bookings.selectpet',[
            'pets' => $pets,
            'owner' => $owner,
        ]);
        
    }

    public function petSelect(Request $request){
        $courses = Course::all();
        $pet_id = $request -> pet;
        $pet = Pet::where('id',session('pet_id')) -> first();
        session(['pet'=> $pet,
                 'pet_id' => $pet_id]);
        return redirect('/selectcourse');
    }

    
    public function selectcourse(){
        $courses = Course::all();
        #$pet = Pet::where('id',session('pet_id')) -> first();
        $pet = session('pet');
        return view('bookings.selectcourse',[
            'courses' => $courses,
            'owner' => session('owner'),
            'pet' => session('pet'),
            'pets' => session('pets'),
        ]);
    }
    

    public function courseSelect(Request $request){
        $course_id = $request -> course;
        $course = Course::where('id' , $course_id) -> first();
        session(['course' => $course]);
        
        return redirect('/calendar');
        return view('bookings.booking_calender',[
            'course' => $course,
            'owner' => session('owner'),
            'pet' => session('pet'),
        ]);
    }

    public function bookings_calendar(){

        return view('bookings.booking_calender',[
            'course' => session('course'),
            'owner' => session('owner'),
            'pet' => session('pet'),
        ]);
    }
}
