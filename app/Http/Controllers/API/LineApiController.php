<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineApiController extends Controller
{
    //
    public function getTest(Request $request){
        Log::debug(__METHOD__.'('.__LINE__.') start!');
        Log::debug(__METHOD__.'('.__LINE__.') email:' . $request->get('email'));
        Log::debug(__METHOD__.'('.__LINE__.') mode:' . $request->get('mode'));

        Log::debug(__METHOD__.'('.__LINE__.') end!');
        return __METHOD__;
    }
}
