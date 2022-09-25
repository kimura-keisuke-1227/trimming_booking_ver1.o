<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempCapacity;
use App\Models\Salon;
use Illuminate\Support\Facades\Log;

class TempCapacityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tempCapacities = TempCapacity::all();
        $salons = Salon::all();
        
        return view('admin.tempcapacity.index',[
            'tempcapacities' => $tempCapacities,
            '$salons' => $salons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salons = Salon::all();
        return view('admin.tempcapacity.create',[
            'salons' => $salons,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tempCapacity = new TempCapacity();
        $salon_id = $request -> salon;
        $st_date = $request -> st_date;
        $st_time = $request -> st_time;
        $ed_date = $request -> ed_date;
        $ed_time = $request -> ed_time;
        $capacity = $request -> capacity;

        $tempCapacity -> salon_id = $salon_id;
        $tempCapacity -> st_date = $st_date;
        $tempCapacity -> st_time = $st_time;
        $tempCapacity -> ed_date = $ed_date;
        $tempCapacity -> ed_time = $ed_time;
        $tempCapacity -> capacity = $capacity;

        $tempCapacity -> save();
        Log::debug($tempCapacity);
        return redirect('/admin/capacitysetting');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
