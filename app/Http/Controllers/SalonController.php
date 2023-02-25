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
        $salons = Salon::all();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.salons.index', [
            'salons' => $salons,
        ]);
    }

    public function show($salon_id){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon = Salon::find($salon_id)->get();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        
    }


    public function edit($salon_id){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon = Salon::find($salon_id)->get();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        
    }
}
