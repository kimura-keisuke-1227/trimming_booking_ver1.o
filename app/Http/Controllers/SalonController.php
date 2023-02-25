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
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') salon_id:' . $salon_id);

        $salon = Salon::find($salon_id)->first();
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .')');
        Log::debug($salon);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        return view('admin.salons.edit', [
            'salon' => $salon,
        ]);
    }

    public function create(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.salons.create', [
            
        ]);
    }

    public function store(Request $request){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon =  new Salon;
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return redirect('/salons') -> with('success','サロンを登録をしました。');
    }
}
