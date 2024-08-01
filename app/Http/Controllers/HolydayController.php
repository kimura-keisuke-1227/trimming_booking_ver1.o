<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\RegularHoliday;
use App\Models\Salon;

class HolydayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($salon_id)
    {
        $salons = Salon::all();
        $regularHolidays = RegularHoliday::where('salon_id',$salon_id)
        ->orderBy('st_date')
        ->get();
        return view('admin.horiday.index',[
            'regularHolidays' => $regularHolidays,
            'salons' => $salons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }
}
