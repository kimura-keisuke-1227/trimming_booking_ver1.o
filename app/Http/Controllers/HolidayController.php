<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Salon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;
class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($salon_id)
    {
        $salons = Salon::all();
        $holidays = Holiday::where(Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID,$salon_id)
        ->orderBy(Holiday::CONST_STR_COLUMN_NAME_OF_DATE)
        ->get();
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . 'holidays');
        Log::debug($holidays);
        return view('admin.horiday.index',[
            'holidays' => $holidays,
            'salons' => $salons,
            'salon_id'  => $salon_id,
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create($salon_id)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $salon = Salon::find($salon_id);
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return __METHOD__;
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
    public function show(Holiday $holiday): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday): RedirectResponse
    {
        //
    }
}
