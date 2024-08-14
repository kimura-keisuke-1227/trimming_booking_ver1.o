<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Dogtype;
use App\Http\Requests\StoreDogtypeRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;
use Exception;

class DogtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "[管理者]犬種一覧の表示";
        $check_log_detail = "犬種一覧取得";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,"");


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
    public function store(StoreDogtypeRequest $request)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
        $dog_type_max_order = Dogtype::query()
        ->max('order');
        ;

        $new_order = $dog_type_max_order + 10;

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $type = $request['type'];

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "犬種の登録";
        $check_log_detail = "犬種:{$type}";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);


        $dog_type = new Dogtype();
        $dog_type['type'] = $type;
        $dog_type['order'] = $new_order;

        try{
            $dog_type->save();
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . "Successfully_create_a_new_dog_type:" . $type .' order:' . $new_order);
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
            return redirect(Route('admin.dogtype.index'))
            ->with('success','犬種を登録しました。');
        } catch(Exception $e){
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . " Error_occurred_when_create_a_new_dog_type:" .$e);
            return redirect(Route('admin.dogtype.index'))
            ->with('error','犬種の登録が失敗しました。');
        }
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
    public function destroy(string $id)
    {

    }

    public function switch_flg_show($id){
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
        $dog_type = Dogtype::query()
        ->find($id);
        ;

        $type = $dog_type->type;
        
        if($dog_type->flg_show){
            $check_log_detail = "犬種:{$type}を無効に切替。";
            $dog_type['flg_show'] = false;
            $success_message = "{$type}の無効化に";
        }else{
            $check_log_detail = "犬種:{$type}を有効に切替。";
            $dog_type['flg_show'] = true;
            $success_message = "{$type}の有効化に";
        }

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "犬種の有効無効切り替え";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,"switch_show_flg:{$id}");



        try{
            $dog_type->save();
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . "Successfully_switch_show_flg_of:" . $type);
        } catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')' . " Error_occurred_when_switch_show_flg_of_dog_type:"  . $type.' '.$e);
            return redirect(Route('admin.dogtype.index'))
            ->with('error',"{$success_message}に失敗しました。");
        }
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(Route('admin.dogtype.index'))
        ->with('success',"{$success_message}に成功しました。");
    }
}
