<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;

use App\Http\Requests\StoreSalonRequest;
use App\Http\Requests\UpdateSalonRequest;

use App\classes\Util;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailTestMail;

class SalonController extends Controller
{
    //

    public function index(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');

        // 操作記録をDBに
        $staff = Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$staff->id}) IP[{$realIp}]";
        $check_log_summary = "スタッフによるサロン一覧取得";
        $check_log_detail = "staff_id:{$staff->id} {$staff->email}";
        $access_log_id = Util::recordAccessLog($method_name,$user_info,$check_log_summary,$check_log_detail,"");

        $salons = Salon::all();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.salons.index', [
            'salons' => $salons,
        ]);
    }

    public function show($salon_id){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon = Salon::find($salon_id)->get();
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        
    }


    public function edit($salon_id){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') salon_id:' . $salon_id);

        // 操作記録をDBに
        $staff = Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$staff->id}) IP[{$realIp}]";
        $check_log_summary = "スタッフによるサロン編集画面呼び出し";
        $check_log_detail = "staff_id:{$staff->id} {$staff->email}";
        $access_log_id = Util::recordAccessLog($method_name,$user_info,$check_log_summary,$check_log_detail,$salon_id);

        $salon = Salon::find($salon_id);
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .')');
        Log::debug($salon);
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        
        $open = Util::get4digitTime($salon->st_time);
        $close = Util::get4digitTime($salon->ed_time);
        return view('admin.salons.edit', [
            'salon' => $salon,
            'open'=>$open,
            'close'=>$close,
        ]);
    }

    public function update(UpdateSalonRequest $request, $id){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::debug(__METHOD__.'('.__LINE__.') user(' . Util::getUserId() .') $request:');
        Log::debug($request);
        $validated = $request->validated();
        Log::debug(__METHOD__.'('.__LINE__.')'.'$input');
        Log::debug($validated);

        $st_time = ($request['st_time'] - $request['st_time']%100)/100 * 60 + $request['st_time']%100;
        $ed_time = ($request['ed_time'] - $request['ed_time']%100)/100 * 60 + $request['ed_time']%100;
      
        $salon = Salon::find($id);
        $salon['salon_name'] = $request['salon_name'];
        $salon['prefecture'] = $request['prefecture'];
        $salon['address1']   = $request['address1'];
        $salon['address2']   = $request['address2'];
        $salon['phone']      = $request['phone'];
        $salon['email']      = $request['email'];
        $salon['is_close_all_courses']      = $request['is_close_all_courses'];
        // $salon['st_time']    = $st_time;
        // $salon['ed_time']    = $ed_time;

        $salon->save();

        $is_close_all_courses_val = '選択コースのみ';
        if($salon['is_close_all_courses']){
            $is_close_all_courses_val = '全コースクローズ';
        }

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "スタッフによるサロン情報の変更[{$method_name}]";
        $check_log_detail = $salon['salon_name'] . ' ' .$salon['prefecture'] . ' ' .$salon['address1'] . ' ' .$salon['phone'] . ' ' .$salon['email'] . '　[全コース自動クローズ]'. $is_close_all_courses_val;
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);

        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');

        return redirect(Route('admin.salon.edit',['salon_id' => $salon]))
        ->with('success','情報を更新しました。');
    }

    public function create(){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return view('admin.salons.create', [
            
        ]);
    }

    public function store(StoreSalonRequest $request){
        Log::info(__METHOD__.'('.__LINE__.') start by user(' . Util::getUserId() .')');
        $salon =  new Salon;
        Log::info(__METHOD__.'('.__LINE__.') end by user(' . Util::getUserId() .')');
        return redirect('/salons') -> with('success','サロンを登録をしました。');
    }

    public function mailtest($salon_id){
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' start!');
        $salon = Salon::find($salon_id);
        Log::info(__METHOD__ . '(' . __LINE__ . ')' . ' end!');

            
        Mail::to($salon->email)
            ->send(new MailTestMail());
        return redirect(Route('admin.salon.edit',['salon_id' => $salon]))
        ->with('success',$salon->email .'にテスト用メールを送信しました。');
    }
}
