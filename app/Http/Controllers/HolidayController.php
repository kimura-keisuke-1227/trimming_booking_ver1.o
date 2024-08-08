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
        $holiday = new Holiday();

        
        try{
            
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID] =$salon_id;
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] = $request['single_date'];
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] = $request['single_comment'];
            
            $holiday->save();

            Log::info(__METHOD__ . '(' . __LINE__ . ')' . 'saved_hodliday salon:' . $salon_id .' date:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] .' comment:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] );
            
            // 操作記録をDBに
            $user =Auth::user();
            $method_name = __METHOD__;
            $realIp = request()->ip();
    
            $user_info = "user_id({$user->id}) IP[{$realIp}]";
            $check_log_summary = "単一店休日の登録";
            $check_log_detail = "休日ID:{$holiday->id} :日付:{$holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE]} コメント:{$holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT]}";
            $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        }catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')error_occurred_when_save_single_holiday:' . $e);
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
        $holiday = new Holiday();

        
        try{
            
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_SALON_ID] =$salon_id;
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] = $request['single_date'];
            $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] = $request['single_comment'];
            
            $holiday->save();

            Log::info(__METHOD__ . '(' . __LINE__ . ')' . 'saved_hodliday salon:' . $salon_id .' date:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE] .' comment:' . $holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT] );
            
            // 操作記録をDBに
            $user =Auth::user();
            $method_name = __METHOD__;
            $realIp = request()->ip();
    
            $user_info = "user_id({$user->id}) IP[{$realIp}]";
            $check_log_summary = "単一店休日の登録";
            $check_log_detail = "休日ID:{$holiday->id} :日付:{$holiday[Holiday::CONST_STR_COLUMN_NAME_OF_DATE]} コメント:{$holiday[Holiday::CONST_STR_COLUMN_NAME_OF_COMMENT]}";
            $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        }catch(Exception $e){
            Log::error(__METHOD__ . '(' . __LINE__ . ')error_occurred_when_save_single_holiday:' . $e);
        }
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
    public function destroy(Holiday $holiday): RedirectResponse
    {
        //
    }
}
