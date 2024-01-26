<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKarteRequest;
use App\Http\Requests\UpdateKarteRequest;
use App\Models\Karte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

use App\Models\Booking;
use App\classes\Util;

class KarteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($booking_id)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'booking:');
        Log::debug($booking_id);
        $booking = Booking::find($booking_id);
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.kartes.create',[
            'booking' => $booking,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKarteRequest $request)
    {
        //
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'. '$request');
        
        $validated = $request->validated();
        Log::debug($validated);

        $karte = Karte::create($validated);

        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return redirect(Route('admin.karte.show',['karte' => $karte]))
        ->with("success","登録しました");
    }

    /**
     * Display the specified resource.
     */
    public function show_for_user(Karte $karte)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return view('kartes.show',[
            'karte' => $karte,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karte $karte)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.kartes.edit',[
            'karte' => $karte,
            'karte_data' => Util::getYMDWFromDbDate($karte->date)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKarteRequest $request, Karte $karte)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
         Log::debug($request);
         $validated = $request->validated();
         Log::debug($validated);
         
         $karte -> fill([
             'karte_for_staff' => $validated['karte_for_staff'],
             'karte_for_owner' => $validated['karte_for_owner'],
            ]);
            
        Log::debug(__METHOD__.'('.__LINE__.')'.'en1d!');
        $karte->save($validated);
        return redirect(Route('admin.karte.show',['karte' => $karte]))
        ->with("success","登録しました");
        // Route('admin.karte.show',['karte' => $karte])
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karte $karte)
    {
        //
    }
}
