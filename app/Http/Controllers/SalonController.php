<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;

class SalonController extends Controller
{
    //

    public function index(){

    }

    public function show($salon_id){
        $salon = Salon::find($salon_id)->get();

        
    }
}
