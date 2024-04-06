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
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::info(__METHOD__.'('.__LINE__.') got all notifications:');
        $allNotifications = Notification::all();

        
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.notifications.index',[
            'notifications' => $allNotifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        // $allNotifications = Notification::all();
        $st_date =date("Y-m-d");
        $ed_date = Util::addDays($st_date,30);

        // Log::debug($allNotifications);
        
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.notifications.create',[
            // 'notifications' => $allNotifications
            'st_date' => $st_date,
            'ed_date' => $ed_date,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        return($request);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return redirect()->route('notification.index')
        ->with("success", "【まだ】お知らせを保存しました。");
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
