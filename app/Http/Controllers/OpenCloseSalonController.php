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
use App\Models\RegularHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpenCloseSalonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Auth::user();
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start by staff('.Util::getUserId().')');
        $salon_id = $staff->default_salon;
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
        $course_master_id = $course_id;
        if($course_master_id==1){
            $ed_time = 60*17+1;
        } else{
            $ed_time = 60*16+1;
        }
        Log::info(__METHOD__ . '(' . __LINE__ . ') get setting info of step_time from DB!');
        $step_time = Util::getSetting(30, 'step_time', true);


        #Log::info(__METHOD__.'('.__LINE__.') user_id('.$user->id . ')getOpenCloseSalon all database.');

        $util = new Util();
        $days = $util->getDaysList($st_date, $ed_date);
        $times = $util->getTimes($st_time, $ed_time, $step_time);
        $timesNum = $util->getTimesNum($st_time, $ed_time, $step_time);

        $OXListOfStartToEndDate = $this->makeOpenCloseListFromStdateToEddate($salon_id, $course_id, $st_date, $ed_date, $st_time, $ed_time, $step_time);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');

        $timesCount = $util->getTimesCount($st_time, $ed_time, $step_time);

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
            'st_date' => $st_date,
            'ed_date'=> $ed_date,
            'st_time' => $st_time,
            'ed_time'=> $ed_time,
            'timesCount' => $timesCount,
            'timeOfFirst' => $times[$st_time]
        ]);
    }

    public function switchOX($salon,$course,$date,$time,$st_date,$count){
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        if($count==0){
            $openCloseSalon = OpenCloseSalon::where('salon_id',$salon)
            ->where('course_id' , $course)
            ->where('date', $date)
            ->where('time',$time);

            Log::debug(__METHOD__.'('.__LINE__.') This time was closed  salon_id(' . $salon . ') course_id('.$course.') date('.$date .') time(' > $time .')' );
            $openCloseSalon -> delete();
    
        } else{
            Log::debug(__METHOD__.'('.__LINE__.') This time was opened so we are closing it! salon_id(' . $salon . ') course_id('.$course.') date('.$date .') time(' > $time .')' );
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

    public function makeOpenCloseListFromStdateToEddate($salon_id, $course_id, $st_date, $ed_date, $st_time, $ed_time, $step_time)
    {
        $allOpenCloseSalonBySalonIdAndCourseId
        =  $this->getAllOpenCloseSalonBySalonIdAndCourseId($salon_id, $course_id);
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start!');
        
        $regularHolidays = RegularHoliday::where('salon_id',$salon_id) -> get();
        
        $OpenCloseListFromStdateToEddate = [];
        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)) {
            $OpenCloseListFromStdateToEddate[$date] = $this->makeOXListOfOneDay($date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId,$regularHolidays);
        }
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end!');
        return $OpenCloseListFromStdateToEddate;
    }

    private function makeOXListOfOneDay($date, $st_time, $ed_time, $step_time, $allOpenCloseSalonBySalonIdAndCourseId,$regularHolidays)
    {
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start! date:' . Util::getYMDWFromDbDate($date));
        $OXListOfOneDay = [];

        $regularHoliday = $regularHolidays->where('dayOfWeek' , date('w',strtotime($date)));

        $todayUsualAccept = -9999;
        if($regularHoliday->isEmpty()){
            $todayUsualAccept = 1;
        } else{
            $todayUsualAccept = -1;
            
        }

        for ($time = $st_time; $time < $ed_time; $time = $time + $step_time) {
            $OXListOfOneDay[$time] = $todayUsualAccept;
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

    public function getAllOpenCloseSalonBySalonIdAndCourseId($salon_id, $couse_id)
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
    
    public function changeOXListAll(Request $request){
        $staff = Auth::user();
        Log::debug(__METHOD__ . '(' . __LINE__ . ') start! by staff(' . $staff->id.')');
        $step_time = Util::getSetting(30,'step_time',true);

        $st_date = $request->st_date;
        $ed_date = $request->ed_date;
        $st_time = $request->st_time;
        $ed_time = $request->ed_time;
        $salon_id = $request->salon_id;
        $course_id = $request->course_id;
        
        Log::debug(__METHOD__.'('.__LINE__.') st_time:' . $st_time . ' ed_time'. $ed_time);

        $insertsDatas = [];
        for($date = $st_date; $date<= $ed_date; $date = Util::addDays($date,1)){
            for($time = $st_time;$time<$ed_time;$time=$time + $step_time){
                $key = $date . '_' . (string) $time;
                $openCloseSalon = $request->get($key);
                Log::debug(__METHOD__.'('.__LINE__.') $request->'. $key .'('. $request->get($key) .')');
                
                if($openCloseSalon==0){
                    $openCloseSalon = [
                        'salon_id' => $salon_id,
                        'course_id' => $course_id,
                        'date' => $date,
                        'isOpen' => 0,
                        'time' => $time,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $insertsDatas[]=$openCloseSalon;
                }
            }
        }
        
        Log::debug(__METHOD__ . '(' . __LINE__ . ') end! by staff(' . $staff->id.')');
        
        //設定日以前の開閉データは一律削除　店舗は関係なく処理
        $daysForStockOXData = Util::getSetting(30,'delete_open_close_date_Xdays_before',true);

        $today = date('Y-m-d');
        $dateForDelete = Util::addDays($today,-$daysForStockOXData);
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') $daysForStockOXData=' . $daysForStockOXData .' dateForDelete=' .$dateForDelete);

        $deleteData = OpenCloseSalon::where('date','<=',$dateForDelete);
        $deleteData->delete();
        Log::notice(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') deleted old OX data from ' . $dateForDelete . '!!');

        //一度データをクリアする(ただし、サロンと日付を注意)
        $deleteData = OpenCloseSalon::where('salon_id',$salon_id)
        ->where('course_id',$course_id)
        ->where('date','>=',$st_date)
        ->where('date','<=',$ed_date)
        #->get()
        ;
        
        Log::debug(__METHOD__ . '(' . __LINE__ . ')  is deleting deleteData salon_id=('.$salon_id.') course_id = (' . $course_id .') st_date(' . $st_date . ') ed_date(' .$ed_date .')');
        #Log::debug($deleteData);
        $deleteData->delete();
        Log::notice(__METHOD__.'('.__LINE__.') deleted OX data by staff(' . $staff->id.')' );
        
        Log::debug(__METHOD__.'('.__LINE__.') $insertsDatas:' );
        #Log::debug($insertsDatas);
        DB::table('open_close_salons')->insert($insertsDatas);
        Log::notice(__METHOD__.'('.__LINE__.') inserted data by staff(' . $staff->id.')' );
        //最後に解禁する

        Log::debug(__METHOD__ . '(' . __LINE__ . ') end! by staff(' . $staff->id.')');
        return redirect(Route('admin.checkOpenCloseWithDate',[
            'salon' =>    $salon_id, 
            'course' => $course_id,
            'date'=>$st_date,
        ]));
    }

    public static function temporaryClose($salon,$date){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        //とりあえず、該当サロン、日付のデータを1回消しておく

        //該当日・サロンのデータを0(0:00)から1439(23:59)まで消しておく
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
    }
}
