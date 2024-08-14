<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use Exception;

use App\Models\Course;
use App\Models\CourseMaster;
use App\Models\Dogtype;

class CourseMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        $course_masters = CourseMaster::all();
        return view('admin.courseMasters.index',[
            'courseMasters' => $course_masters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return view('admin.courseMasters.create',[
        ]);
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

        $course = $request['course'];

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "基本コースの登録";
        $check_log_detail = "基本コース:{$course}";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        
        $course_master_max_order = CourseMaster::query()
        ->max('order');
        ;

        $new_order = $course_master_max_order + 10;

        $course_master = new CourseMaster();

        $course_master['course'] = $course;
        $course_master['order']  = $new_order;

        try{
            $course_master->save();
            Log::info(__METHOD__ . '(' . __LINE__ . ')' . "Successfully_create_a_new_course_master:" . $course .' order:' . $new_order);
        }
        catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')' . 'Error_occurred_when_create_course_master:' . $course .' ' . $e);
            Log::error(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
            return redirect(Route('admin.course_master.index'))
                ->with('error','基本コースを登録に失敗しました。');
        }

        $dogTypes = Dogtype::all();

        // すべての犬種に対して、コースを設定。ただし、最初は非表示。
        foreach($dogTypes as $dogType){
            $course = new Course();
            $course['course_master_id'] = $course_master->id;
            $course['dogtype_id'] = $dogType->id;
            $course['minute'] = 60*24;
            $course['minute_for_show'] = 60*24;
            $course['price'] = 99999;
            $course['flg_show'] = false;
            $course['st_date'] = "2020-01-01";

            try{
                $course->save();
                Log::info(__METHOD__ . '(' . __LINE__ . ')' . 'Successfully_create_a_new_course_when_create_a_course_master.');
            } catch(Exception $e){                
                Log::error(__METHOD__ . '(' . __LINE__ . ')' . 'Error_occurred_when_create_a_new_course_when_create_a_course_master. ' . $e);
            }

        }

        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(Route('admin.course.edit'))
            ->with('success','基本コースを登録しました。コース情報を確認してください。');
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
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return __METHOD__;
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
