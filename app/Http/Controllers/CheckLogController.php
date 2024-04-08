<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessLogRequest;
use App\Http\Requests\UpdateAccessLogRequest;
use App\Models\AccessLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AccessLogController extends Controller
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
    public function store(StoreAccessLogRequest $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessLog $AccessLog): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccessLog $AccessLog): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccessLogRequest $request, AccessLog $AccessLog): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessLog $AccessLog): RedirectResponse
    {
        //
    }
}
