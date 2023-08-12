<?php

namespace App\Http\Controllers;

// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Http\Response;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;

use App\Models\Notification;

use Illuminate\Support\Facades\Log;
use App\classes\Util;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::debug(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $allNotifications = Notification::all();

        Log::debug(__METHOD__.'('.__LINE__.') got all notifications:');
        Log::debug($allNotifications);
        
        Log::debug(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.notifications.create',[
            'notifications' => $allNotifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
