<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;

use App\classes\Util;
use Illuminate\Support\Facades\Log;

class SalonController extends Controller
{
    //

    public function index(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
    }

    public function show($salon_id){
        $salon = Salon::find($salon_id)->get();

        
    }
}
