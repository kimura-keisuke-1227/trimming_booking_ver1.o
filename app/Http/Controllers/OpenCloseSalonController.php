<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpenCloseSalonRequest;
use App\Http\Requests\UpdateOpenCloseSalonRequest;
use App\Models\OpenCloseSalon;
use App\classes\BookingsCalc;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
use App\Models\Salon;
use App\Models\CourseMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpenCloseSalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        $salon_id = 1;
        $date = date('Y-m-d');
        $course_id = 1;
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');

        return $this->getOXwithParam($salon_id,$course_id,$date);
    }

    public function index2($salon,$course_id,$date)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start! with param salon_id:'. $salon . ' date:' . $date);

        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');

        return $this->getOXwithParam($salon,$course_id,$date);
    }

    public function index3(Request $request)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        $salon_id = $request->salon;
        $course_id=$request->course;
        $date = $request ->st_date;
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');

        return redirect(route('admin.checkOpenCloseWithDate',[
            'salon' =>    $salon_id, 
            'course' => $course_id,
            'date'=>$date,
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOpenCloseSalonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOpenCloseSalonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function show(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function edit(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOpenCloseSalonRequest  $request
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOpenCloseSalonRequest $request, OpenCloseSalon $openCloseSalon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OpenCloseSalon  $openCloseSalon
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpenCloseSalon $openCloseSalon)
    {
        //
    }

    public function getOXwithParam($salon_id,$course_id,$date)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        $user = Auth::user();

        //フォームに表示させるためのサロンとコースマスター（SとSC)
        Log::info(__METHOD__ . '(' . __LINE__ . ') get salons info from DB!');
        $salons = Salon::all();
        Log::info(__METHOD__ . '(' . __LINE__ . ') get courses info from DB!');
        $courseMasters = CourseMaster::all();
        
        //関数を呼ぶためのパラメータ
        $salon = $salons->find($salon_id);
        $st_date = $date;
        $ed_date = Util::addDays($st_date, 6);
        $before_start_day = Util::addDays($st_date, -7);
        $next_start_day = Util::addDays($ed_date, 1);
        $st_time = $salon->st_time;
        $ed_time = $salon->ed_time;
        Log::info(__METHOD__ . '(' . __LINE__ . ') get setting info of step_time from DB!');
        $step_time = Util::getSetting(30, 'step_time', true);


        #Log::info(__METHOD__.'('.__LINE__.') user_id('.$user->id . ')getOpenCloseSalon all database.');
        $allOpenCloseSalonBySalonIdAndCourseId
            =  $this->getAllOpenCloseSalonBySalonIdAndCourseId($salon_id, $course_id);

        $util = new Util();
        $days = $util->getDaysList($st_date, $ed_date);
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);

        $OXListOfStartToEndDate = $this->makeOpenCloseListFromStdateToEddate($salon_id, $course_id, $st_date, $ed_date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');

        return view('admin.openclose.index', [
            'st_date' => $st_date,
            'days' => $days,
            'times' => $times,
            'timesNum' => $timesNum,
            'capacities' => $OXListOfStartToEndDate,
            'salons' => $salons,
            'selectedSalon' => $salon_id,
            'courses' => $courseMasters,
            'before_start_day'=> $before_start_day,
            'next_start_day'=>$next_start_day,
            'course_id' => $course_id,
        ]);
    }

    public function switchOX($salon,$course,$date,$time,$st_date,$count){
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        if($count==0){
            $openCloseSalon = OpenCloseSalon::where('salon_id',$salon)
            ->where('course_id' , $course)
            ->where('date', $date)
            ->where('time',$time)
            -> first();

            Log::debug(__METHOD__.'('.__LINE__.') This time was closed and openCloseSalon data is :');
            $openCloseSalon -> delete();
            Log::debug($openCloseSalon);
        } else{
            Log::debug(__METHOD__.'('.__LINE__.') This time was opened so we are closing it!');
            $openCloseSalon = new OpenCloseSalon();
            $openCloseSalon -> salon_id =$salon;
            $openCloseSalon -> course_id =$course;
            $openCloseSalon -> date =$date;
            $openCloseSalon -> time =$time;
            $openCloseSalon -> isOpen = 0;

            $openCloseSalon -> save();

        }
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
        return redirect(Route('admin.checkOpenCloseWithDate',[
            'salon' => $salon,
            'course' => $course,
            'date' => $st_date,
        ]));
        return __METHOD__;
    }

    private function makeOpenCloseListFromStdateToEddate($salon_id, $couse_id, $st_date, $ed_date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        $OpenCloseListFromStdateToEddate = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $OpenCloseListFromStdateToEddate[$date] = $this->makeOXListOfOneDay($date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId);
        }
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
        return $OpenCloseListFromStdateToEddate;
    }

    private function makeOXListOfOneDay($date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start! date:' . Util::getYMDWFromDbDate($date));
        $OXListOfOneDay = [];

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $OXListOfOneDay[$time] = 1;
        }

        $dateOpenCloseSalonBySalonIdAndCourseId
            = $allOpenCloseSalonBySalonIdAndCourseId->where('date', $date);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') $dateOpenCloseSalonBySalonIdAndCourseId:');
        Log::debug($dateOpenCloseSalonBySalonIdAndCourseId);

        foreach ($dateOpenCloseSalonBySalonIdAndCourseId as $eachOpenCloseSalonBySalonIdAndCourseId) {
            $rewrite = true;
            if ($eachOpenCloseSalonBySalonIdAndCourseId->date !== $date) {
                $rewrite = false;
            }

            $OXListOfOneDay[$eachOpenCloseSalonBySalonIdAndCourseId->time]
                = $eachOpenCloseSalonBySalonIdAndCourseId->isOpen;
        }

        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
        return $OXListOfOneDay;
    }

    private function getAllOpenCloseSalonBySalonIdAndCourseId($salon_id, $couse_id)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');

        $user = Auth::user();
        #Log::info(__METHOD__.'('.__LINE__.') user_id('.$user->id . ')getOpenCloseSalon all database.');
        $allOpenCloseSalonBySalonIdAndCourseId = OpenCloseSalon::where('salon_id', $salon_id)
            ->where('course_id', $couse_id)
            ->get();

        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
        return $allOpenCloseSalonBySalonIdAndCourseId;
    }
}
