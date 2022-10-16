<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpenCloseSalonRequest;
use App\Http\Requests\UpdateOpenCloseSalonRequest;
use App\Models\OpenCloseSalon;
use App\classes\BookingsCalc;
use Illuminate\Support\Facades\Log;
use App\classes\Util;

class OpenCloseSalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOpenCloseSalonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOpenCloseSalonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function show(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function edit(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOpenCloseSalonRequest  $request
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOpenCloseSalonRequest $request, OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    public function getOX(){
        Log::debug(__METHOD__.'('.__LINE__.') start!');

        $openOrCloseAll = OpenCloseSalon::all();

        Log::debug(__METHOD__.'('.__LINE__.')' . $openOrCloseAll);
        
        Log::debug(__METHOD__.'('.__LINE__.') end!');
        return $openOrCloseAll;
    }
}
