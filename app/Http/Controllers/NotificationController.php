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
use Illuminate\Support\Facades\Auth;

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
        $st_date = date("Y-m-d" . " 00:00") ;
        $ed_date = Util::addDays($st_date,30) . ' 23:59';
        
        Log::debug(__METHOD__.'('.__LINE__.')'.'$st_date:' . $st_date .' $ed_date:' . $ed_date);
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
        // return($request);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "スタッフによるによるお知らせの登録[{$method_name}]";
        $check_log_detail = "{$request['st_date']} から {$request['ed_date']} までのお知らせ。{$request['contents']}";
        $request_from_user = '';
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        

        Notification::create([
            'contents' => $request['contents'],
            'st_date' => $request['st_date'],
            'ed_date' => $request['ed_date'],
        ]);
        
        return redirect()->route('notification.index')
        ->with("success", "お知らせを保存しました。");
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
