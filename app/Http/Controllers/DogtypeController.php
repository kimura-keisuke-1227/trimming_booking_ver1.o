<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Dogtype;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;

class DogtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $dogtypes = Dogtype::all();
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return view('admin.dogtypes.index',[
            'dogtypes' => $dogtypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return view('admin.dogtypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
                // 操作記録をDBに
                $user =Auth::user();
                $method_name = __METHOD__;
                $realIp = request()->ip();
        
                $type = $request['type'];
        
                $user_info = "user_id({$user->id}) IP[{$realIp}]";
                $check_log_summary = "犬種の登録";
                $check_log_detail = "犬種:{$type}";
                $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);

        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(Route('admin.dogtype.index'))
        ->with('success','犬種を登録しました。');
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
