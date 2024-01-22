<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKarteRequest;
use App\Http\Requests\UpdateKarteRequest;
use App\Models\Karte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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
    public function create()
    {
        //
        return view('admin.kartes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKarteRequest $request)
    {
        //
        return 'hoge';
    }

    /**
     * Display the specified resource.
     */
    public function show(Karte $karte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karte $karte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKarteRequest $request, Karte $karte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karte $karte)
    {
        //
    }
}
