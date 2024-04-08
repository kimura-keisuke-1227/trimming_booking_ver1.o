<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessLogRequest;
use App\Http\Requests\UpdateAccessLogRequest;
use App\Models\AccessLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;
use App\classes\Util;
use Illuminate\Support\Facades\Auth;

class AccessLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info(__METHOD__.'('.__LINE__.')'.'start!');
        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "アクセスログの一覧を取得[{$method_name}]";
        $check_log_detail = "";
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);

        $access_log_list = AccessLog::query()
        ->latest('created_at')
        ->paginate(50);
        Log::info(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.accessLog.index',[
            'list_accessLog' => $access_log_list,
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
    public function store(StoreAccessLogRequest $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessLog $accesslog)
    {
        Log::info(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'AccessLog');
        Log::debug($accesslog);
        Log::info(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.accessLog.show',[
            'AccessLog' => $accesslog,
        ]);
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
