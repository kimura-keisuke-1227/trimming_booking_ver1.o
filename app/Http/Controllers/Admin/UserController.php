<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salon;
use App\Models\Booking;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use  App\Http\Requests\Admin\StoreUserRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('admin.owners.index',[
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     //ユーザー登録画面表示
    public function create()
    {
        $salons = Salon::all();
        return view('admin.users.create',[
            'salons' => $salons
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //ユーザー登録処理
    public function store(StoreUserRequest $request)
    {
        Log::debug($request);
        $validated = $request -> validated();
        $validated['password'] =Hash::make($validated['password']);
        Log::debug($validated);
        $validated['auth'] =0;
        User::create($validated);

        #return back() -> with('success','会員登録をしました。');
        return redirect('login') -> with('success','会員登録をしました。');
    }


    //スタッフ登録
    public function createStaff()
    {
        $salons = Salon::all();
        return view('admin.createStaff.create',[
            'salons' => $salons
        ]);
    }

    public function storeStaff(StoreUserRequest $request)
    {
        Log::info(__METHOD__.'(start)');
        Log::debug($request);
        $validated = $request -> validated();
        $validated['password'] =Hash::make($validated['password']);
        $validated['auth'] =1;
        Log::debug(__METHOD__. ' $validated:');
        Log::debug($validated);
        $user = User::create($validated);
        Log::debug(__METHOD__ . ' user_id');
        Log::debug($user->id);
        $user = User::find($user->id);
        $user -> auth = 1;
        $user -> save();
        Log::info(__METHOD__.'(end)');
        #return back() -> with('success','会員登録をしました。');


        return redirect('login') -> with('success','スタッフ登録をしました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user ,$userID)
    {
        $user = User::find($userID);
        $cameBefore = false;
        
        if($user->cameBefore ==1){
            $cameBefore = true;
            Log::info(__METHOD__ . ' This user('. $user->id .') came before she or he registered.');
            
        } else{
            Log::info(__METHOD__ . ' This user('. $user->id .') did not came before she or he registered.');
            $pets = Pet::select('id')->where('owner_id',$user->id) -> get();
            $bookings = Booking::whereIn('pet_id', $pets)->where('date','<',date('Y-m-d')) -> get();
            Log::debug(__METHOD__ . '(pets)' . $pets);
            Log::debug(__METHOD__ . '(bookings)' . $bookings);
            
            if($bookings->count()>0){
                $cameBefore = true;
                Log::info(__METHOD__ . ' This user('. $user->id .') came before because of checking Booking data.');
            } else{
                Log::info(__METHOD__ . ' This user('. $user->id .') did not came before because of checking Booking data.');

            }
            
        }
        
        if($cameBefore){
            $cameBefore = '来店歴あり';
            
        } else{
            
            $cameBefore = '来店歴なし';
        }
        
        return view('admin.users.show',[
            'user' => $user,
            'cameBefore' => $cameBefore
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
