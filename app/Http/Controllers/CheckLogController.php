<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckLogRequest;
use App\Http\Requests\UpdateCheckLogRequest;
use App\Models\CheckLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CheckLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
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
    public function store(StoreCheckLogRequest $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CheckLog $checkLog): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CheckLog $checkLog): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckLogRequest $request, CheckLog $checkLog): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CheckLog $checkLog): RedirectResponse
    {
        //
    }
}
