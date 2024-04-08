<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;
use Illuminate\Support\Facades\Log;

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
         $salons = Salon::all();
         $courses = Course::all();
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

        Log::info(__METHOD__.'('.__LINE__.')'.'Access to DB for get Course info.');
        $check_log_summary = "[自動]スタッフによるコースの時間変更のためのDBアクセス[{$method_name}]";
        $check_log_detail = "";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        $courses = Course::all();

        $check_log_summary = "スタッフによるコースの時間変更[{$method_name}]";
        $check_log_detail = "";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);

        foreach($courses as $course){
            //  Log::debug(__METHOD__.'('.__LINE__.') id:' .(String)$course->id.')minute:' . (String)$request["minute_" . (String)$course->id]);

             $course['minute'] = $request["minute_" . (String)$course->id];
             $course['minute_for_show'] = $request["minute_for_show_" . (String)$course->id];
             Log::debug(__METHOD__.'('.__LINE__.')'.'');
             $course->save();
        }

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
}
