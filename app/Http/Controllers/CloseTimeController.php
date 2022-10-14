<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecloseTimeRequest;
use App\Http\Requests\UpdatecloseTimeRequest;
use App\Models\closeTime;

class CloseTimeController extends Controller
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
     * @param  \App\Http\Requests\StorecloseTimeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorecloseTimeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\closeTime  $closeTime
     * @return \Illuminate\Http\Response
     */
    public function show(closeTime $closeTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\closeTime  $closeTime
     * @return \Illuminate\Http\Response
     */
    public function edit(closeTime $closeTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecloseTimeRequest  $request
     * @param  \App\Models\closeTime  $closeTime
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecloseTimeRequest $request, closeTime $closeTime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\closeTime  $closeTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(closeTime $closeTime)
    {
        //
    }
}
