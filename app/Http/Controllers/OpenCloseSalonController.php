<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpenCloseSalonRequest;
use App\Http\Requests\UpdateOpenCloseSalonRequest;
use App\Models\OpenCloseSalon;
use App\classes\BookingsCalc;
use Illuminate\Support\Facades\Log;
use App\classes\Util;
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
        //
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

    public function testOX(){
        Log::debug(__METHOD__.'('.__LINE__.') start!');

        //関数を呼ぶためのパラメータ
        $salon_id = 1;
        $couse_id = 1;
        $st_date = '2022-10-20';
        $ed_date = Util::addDays($st_date,6);
        $st_time = 600;
        $ed_time = 1140;
        $step_time = 30;


        $user = Auth::user();
        #Log::info(__METHOD__.'('.__LINE__.') user_id('.$user->id . ')getOpenCloseSalon all database.');
        $allOpenCloseSalonBySalonIdAndCourseId 
        =  $this-> getAllOpenCloseSalonBySalonIdAndCourseId($salon_id,$couse_id);
        
        $util = new Util();
        $days = $util -> getDaysList($st_date,$ed_date);
        $times = $util -> getTimes($st_time,$ed_time,$step_time);
        $timesNum = $util -> getTimesNum($st_time,$ed_time,$step_time);

        $OpenCloseListFromStdateToEddate = [];
        for($date = $st_date;$date<=$ed_date; $date = Util::addDays($date,1)){
            $OpenCloseListFromStdateToEddate[$date] = $this->makeOXListOfOneDay($date,$st_time,$ed_time,$step_time,$allOpenCloseSalonBySalonIdAndCourseId);
        }

        $OXListOfOneDay = $this->makeOpenCloseListFromStdateToEddate($salon_id,$couse_id,$st_date,$ed_date,$st_time,$ed_time,$step_time,$allOpenCloseSalonBySalonIdAndCourseId);
        Log::debug(__METHOD__.'('.__LINE__.') end!');

        return view('admin.openclose.index',[
            'days'=> $days,
            'times' => $times,
            'timesNum' => $timesNum,
            'capacities' => $OXListOfOneDay,
        ]);
        return $OpenCloseListFromStdateToEddate;
    }

    private function makeOpenCloseListFromStdateToEddate($salon_id,$couse_id,$st_date,$ed_date,$st_time,$ed_time,$step_time,$allOpenCloseSalonBySalonIdAndCourseId){
        Log::debug(__METHOD__.'('.__LINE__.') start!');
        $OpenCloseListFromStdateToEddate = [];
        for($date = $st_date;$date<=$ed_date; $date = Util::addDays($date,1)){
            $OpenCloseListFromStdateToEddate[$date] = $this->makeOXListOfOneDay($date,$st_time,$ed_time,$step_time,$allOpenCloseSalonBySalonIdAndCourseId);
        }
        Log::debug(__METHOD__.'('.__LINE__.') end!');
        return $OpenCloseListFromStdateToEddate;
    }

    private function makeOXListOfOneDay($date,$st_time,$ed_time,$step_time,$allOpenCloseSalonBySalonIdAndCourseId){
        Log::debug(__METHOD__.'('.__LINE__.') start!');
        $OXListOfOneDay =[];

        for($time = $st_time;$time < $ed_time;$time = $time + $step_time){
            $OXListOfOneDay[$time] = 1;
        }

        $dateOpenCloseSalonBySalonIdAndCourseId 
        = $allOpenCloseSalonBySalonIdAndCourseId->where('date',$date);
        Log::debug(__METHOD__.'('.__LINE__.') $dateOpenCloseSalonBySalonIdAndCourseId:');
        Log::debug($dateOpenCloseSalonBySalonIdAndCourseId);

        foreach($dateOpenCloseSalonBySalonIdAndCourseId as $eachOpenCloseSalonBySalonIdAndCourseId){
            $rewrite = true;
            if($eachOpenCloseSalonBySalonIdAndCourseId->date !== $date){
                $rewrite = false;
            }

            $OXListOfOneDay[$eachOpenCloseSalonBySalonIdAndCourseId->time] 
            = $eachOpenCloseSalonBySalonIdAndCourseId -> isOpen;
        }

        Log::debug(__METHOD__.'('.__LINE__.') end!');
        return $OXListOfOneDay;
    }

    private function getAllOpenCloseSalonBySalonIdAndCourseId($salon_id, $couse_id){
        Log::debug(__METHOD__.'('.__LINE__.') start!');

        $user = Auth::user();
        #Log::info(__METHOD__.'('.__LINE__.') user_id('.$user->id . ')getOpenCloseSalon all database.');
        $allOpenCloseSalonBySalonIdAndCourseId = OpenCloseSalon::where('salon_id',$salon_id)
        ->where('course_id' , $couse_id)
        ->get();

        Log::debug(__METHOD__.'('.__LINE__.') end!');
        return $allOpenCloseSalonBySalonIdAndCourseId;

    }
}
