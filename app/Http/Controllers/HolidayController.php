<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Salon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;
use App\classes\Util;

use App\Http\Requests\SingleHolidayRqeuest;
use App\Http\Requests\MultipleHolidayRqeuest;
use Exception;
use Illuminate\Support\Facades\Log;
class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($salon_id)
    {
        $salons = Salon::all();
        $holidays = Holiday::where(Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID,$salon_id)
        ->orderBy(Holiday::CONST_STR_COLUMN_NAME_OF_DATE)
        ->get();
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . 'holidays');
        Log::debug($holidays);
        return view('admin.holiday.index',[
            'holidays' => $holidays,
            'salons' => $salons,
            'salon_id'  => $salon_id,
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create($salon_id)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $salon = Salon::find($salon_id);
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return view('admin.holiday.create' , [
            'salon_id' => $salon_id ,
            'salon' => $salon,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($salon_id, SingleHolidayRqeuest $request)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        Log::debug($request);

        // ボタンの名前で条件分岐
        if ($request->has('single_holiday')) {
            // 単一日休日の登録処理
            $this->registerSingleHoliday($salon_id, $request);
        } elseif ($request->has('multiple_holidays')) {
            // 複数休日の登録処理
            $this->registerMultipleHolidays($salon_id, $request);
        }

        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(route('admin.holiday',['salon_id'=>$salon_id]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_multi_holidays($salon_id, MultipleHolidayRqeuest $request)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        Log::debug($request);

        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(route('admin.holiday',['salon_id'=>$salon_id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        
        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();
        
        $holiday_id = $holiday->id;
        $salon_id = $holiday->salon_id;
        $date = $holiday->date;
        $comment = $holiday->comment;
        
        Log::debug(__METHOD__ . '(' . __LINE__ . ')' . 'holiday_id:' . $holiday_id .' salon:' . $salon_id . ' date:' . $date . ' comment:' . $comment);

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "店休日の削除";
        $check_log_detail = "日付:{$date} サロン:{$salon_id} コメント:{$comment}";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,"get");

        $holiday->delete();
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
        return redirect(Route('admin.holiday',['salon_id' => $salon_id]))
            ->with('success','店休日を削除しました。');
    }

    private function registerSingleHoliday($salon_id,$request){
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $holiday = new Holiday();

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $date = $request['single_date'];
        $comment = $request['single_comment'];

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "単一店休日の登録";
        $check_log_detail = "日付:{$date} コメント:{$comment}";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        
        try{
            
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID] =$salon_id;
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] = $request['single_date'];
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] = $request['single_comment'];
            
            $holiday->save();

            Log::info(__METHOD__ . '(' . __LINE__ . ')' . 'saved_hodliday salon:' . $salon_id .' date:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] .' comment:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] );
            

        }catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')error_occurred_when_save_single_holiday:' . $e);
        }
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
    }

    private function registerMultipleHolidays($salon_id,$request){
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $st_date = $request['st_date'];
        $ed_date = $request['ed_date'];
        $day_of_week = $request['day_of_week'];
        $comment = $request['single_comment'];
        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "複数店休日の登録";
        $check_log_detail = "開始日付:{$st_date} 終了日付:{$ed_date} 曜日番号:{$day_of_week}  コメント:{$comment}";
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);

        $holiday = new Holiday();

        for ($date = $st_date; $date <= $ed_date; $date = Util::addDays($date, 1)){
            // 日付を曜日に
            $date_day_of_week = date('w', strtotime($date));
            if ($day_of_week==999 || $date_day_of_week == $day_of_week){
                try{
                    $holiday = new Holiday();
                    $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID] =$salon_id;
                    $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] = $date;
                    $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] = $comment;

                    $holiday->save();

                    Log::info(__METHOD__ . '(' . __LINE__ . ')' . 'saved_hodliday salon:' . $salon_id .' date:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] .' comment:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] );

                }catch(Exception $e){
                    Log::error(__METHOD__ . '(' . __LINE__ . ')error_occurred_when_save_single_holiday:' . $e);
                }
            }else{
                Log::info(__METHOD__ . '(' . __LINE__ . ') skipped ' . $date);
            }

        }
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');
    }
}
