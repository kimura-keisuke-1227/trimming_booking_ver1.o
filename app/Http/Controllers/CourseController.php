<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;
use Illuminate\Support\Facades\Log;

use Exception;

use App\Models\Salon;
use App\Models\Course;
use App\Models\CourseMaster;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 操作記録をDBに
        $staff = Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$staff->id}) IP[{$realIp}]";
        $check_log_summary = "スタッフによるコース情報表示";
        $check_log_detail = "staff_id:{$staff->id} {$staff->email}";
        $access_log_id = Util::recordAccessLog($method_name,$user_info,$check_log_summary,$check_log_detail,$staff->id); 
        
        // $salons = Salon::all();
        $courses = Course::query()
            ->join('dogtypes', 'courses.dogtype_id', '=', 'dogtypes.id') // Dogtypeテーブルと結合
            ->orderBy('dogtypes.order') // Dogtypeのorderカラムで並び替え
            ->orderBy('course_master_id') // その後、course_master_idで並び替え
            ->select('courses.*') // コースの全カラムを選択
            ->get();
    
         
         $courseMasters = CourseMaster::all();
         Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
         Log::debug($courses);
         Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
         return view('admin.courses.index',[
            'courses' => $courses,
            'courseMasters' => $courseMasters,
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
    public function store(Request $request)
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
    public function update(Request $request)
    {
        //
        $r =  $request;
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        
        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();
        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $request_from_user = request();
        $request_from_user = $request;

        Log::info(__METHOD__.'('.__LINE__.')'.'Access to DB for get Course info.');
        $check_log_summary = "[自動]スタッフによるコースの時間変更のためのDBアクセス[{$method_name}]";
        $check_log_detail = "";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        $courses = Course::all();

        $check_log_summary = "スタッフによるコースの時間変更[{$method_name}]";
        $check_log_detail = "";
        

        foreach($courses as $course){
            //  Log::debug(__METHOD__.'('.__LINE__.') id:' .(String)$course->id.')minute:' . (String)$request["minute_" . (String)$course->id]);

             $course['minute'] = $request["minute_" . (String)$course->id];
             $course['minute_for_show'] = $request["minute_for_show_" . (String)$course->id];
             Log::debug(__METHOD__.'('.__LINE__.')'.'');
             $course->save();
             $check_log_detail = $check_log_detail . ' '. $course->dogtype->type . '(' . $course->courseMaster->course. ') [minute:' .$course['minute']  . ' minute_for_show:'.$course['minute_for_show']  .']';
        }

        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);

        Log::debug($request);
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return redirect()->route('admin.course.edit')
            ->with('success','更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function switch_course($id){
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
        $coruse = Course::query()
        ->find($id);
        ;

        $course_text = $coruse->dogtype->type . ' ' . $coruse->courseMaster->course;
        
        if($coruse->flg_show){
            $check_log_detail = "コース:{$course_text}を無効に切替。";
            $coruse['flg_show'] = false;
            $success_message = "{$course_text}の無効化に";
        }else{
            $check_log_detail = "犬種:{$course_text}を有効に切替。";
            $coruse['flg_show'] = true;
            $success_message = "{$course_text}の有効化に";
        }

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "コースの有効無効切り替え";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,"switch_show_flg:{$id}");



        try{
            $coruse->save();
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . "Successfully_switch_show_flg_of:" . $course_text);
        } catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')' . " Error_occurred_when_switch_show_flg_of_course:"  . $course_text.' '.$e);
            return redirect(Route('admin.course.edit'))
            ->with('error',"{$success_message}に失敗しました。");
        }
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(Route('admin.course.edit'))
        ->with('success',"{$success_message}に成功しました。");
    }
}
