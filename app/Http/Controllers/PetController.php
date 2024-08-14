<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Dogtype;
use App\Models\Karte;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\classes\Util;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        $pets = Pet::where('owner_id' , $owner -> id) -> get();

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "ユーザーによるペット一覧の取得[{$method_name}]";
        $check_log_detail = "";
        $request_from_user = request();
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request_from_user);
        
        
        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return view('pets.index',[
            'owner' => $owner ,
            'pets' => $pets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        
        // $dogTypes = Dogtype::all();
        $dogTypes = Dogtype::query()
            ->where("flg_show",true)
            ->orderBy('order')
            ->get();

        session([
            'dogtypes' => $dogTypes
        ]);
        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return view('pets.create',[
            'dogtypes' => $dogTypes,
        ]
    );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $owner = Auth::user();
        Log::info(__METHOD__ . ' starts by user_id(' . $owner->id . ')');
        
        $dogTypes = session('dogtypes');
        
        $pet = new Pet;
        
        $request -> validate([
            'name' =>['required', 'string' , 'max:255'],
            'weight' => ['numeric','min:0','max:255'],
            'birthday' =>['required','before:today'],
        ]);
        
        #$form = $request -> all();
                // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "ユーザーによるペットの登録[{$method_name}]";
        
        
        #unset($form['_token']);
        $owner = Auth::user();
        $pet -> owner_id = $owner -> id ;
        $pet -> name = $request -> name;
        $pet -> dogtype_id = $request -> dogtype;
        $pet -> birthday = $request -> birthday;
        $pet -> weight = $request -> weight;
        $check_log_detail = $pet;
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        $pet ->  save();
        Log::notice(__METHOD__ . ' owner user_id(' . $owner->id . ') saved a pet id(' . $pet -> id .')');
        
        Log::debug('登録ペット情報：(owner_id)' . $request -> owner_id . ' (pet_name)' . $request -> pet_name . '(dog_type)' . $request -> dogtype );
        
        Log::info(__METHOD__ . ' ends by user_id(' . $owner->id . ')');
        return redirect('/pets') -> with('success','ペットを登録をしました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function show_by_staff($pet_id)
    {
        //
        $owner = Auth::user();
        Log::debug(__METHOD__.'('.__LINE__.') started by user('.$owner->id.')');
        $pet = Pet::find($pet_id);
        $kartes = Karte::query()
            ->where('pet_id',$pet_id)
            ->orderBy('date','desc')
            ->limit(12)
            ->get();
         Log::debug(__METHOD__.'('.__LINE__.')'.'kartes:');
         Log::debug($kartes);

        // //存在しないペットや他人のペットを表示できないようにする。

        // $is_pet_owned_by_owner = true;
        // Log::debug("is_pet_owned_by_owner: " . $is_pet_owned_by_owner);

        // if(is_null($pet) or $owner->id !== $pet->owner_id){
        //     $is_pet_owned_by_owner  = false;
        // }
        // $is_albe_to_see_the_pet = $is_pet_owned_by_owner;
        // if(!$is_albe_to_see_the_pet ){
        //     Log::warning(__METHOD__.'('.__LINE__.') The user('.$owner->id.') tried to open pet('.$pet_id.') so refused!!' );
        //     return redirect('/pets') -> with('error','無効なページです');
        // }
        
        Log::debug(__METHOD__.'('.__LINE__.') ended by user('.$owner->id.')');
        return view('admin.pets.edit',[
            'pet' => $pet,
            'kartes' => $kartes,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $owner = Auth::user();
        Log::debug(__METHOD__.'('.__LINE__.') started by user('.$owner->id.')');
        $pet = Pet::find($id);
        $kartes = Karte::query()
            ->where('pet_id',$id)
            ->orderBy('date','desc')
            ->limit(12)
            ->get();
         Log::debug(__METHOD__.'('.__LINE__.')'.'kartes:');
         Log::debug($kartes);

        //存在しないペットや他人のペットを表示できないようにする。

        $is_pet_owned_by_owner = true;
        Log::debug("is_pet_owned_by_owner: " . $is_pet_owned_by_owner);

        if(is_null($pet) or $owner->id !== $pet->owner_id){
            $is_pet_owned_by_owner  = false;
        }
        $is_albe_to_see_the_pet = $is_pet_owned_by_owner;
        if(!$is_albe_to_see_the_pet ){
            Log::warning(__METHOD__.'('.__LINE__.') The user('.$owner->id.') tried to open pet('.$id.') so refused!!' );
            return redirect('/pets') -> with('error','無効なページです');
        }
        
        Log::debug(__METHOD__.'('.__LINE__.') ended by user('.$owner->id.')');
        return view('pets.edit',[
            'pet' => $pet,
            'kartes' => $kartes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
        $updateData=$request -> validate([
            'weight' => ['numeric','min:0','max:255'],
            'birthday' =>['required','before:today'],
        ]);

        $pet->weight = $request->weight;
        $pet->birthday = $request->birthday;

        // 操作記録をDBに
        $user =Auth::user();
        $method_name = __METHOD__;
        $realIp = request()->ip();

        $user_info = "user_id({$user->id}) IP[{$realIp}]";
        $check_log_summary = "ユーザーによるペット情報の変更[{$method_name}]";
        $check_log_detail = $pet;
        $access_log_id = Util::recordAccessLog(__METHOD__,$user_info,$check_log_summary,$check_log_detail,$request);
        

        $pet->save();
        return redirect(Route('pets.edit',['pet' => $pet]))
        ->with('success','情報を修正しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
