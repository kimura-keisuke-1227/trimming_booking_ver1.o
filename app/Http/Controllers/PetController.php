<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Dogtype;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        
        $dogTypes = Dogtype::all();
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
            'weight' => ['numeric'],
        ]);
        
        #$form = $request -> all();
        
        #unset($form['_token']);
        $owner = Auth::user();
        $pet -> owner_id = $owner -> id ;
        $pet -> name = $request -> name;
        $pet -> dogtype_id = $request -> dogtype;
        $pet -> birthday = $request -> birthday;
        $pet -> weight = $request -> weight;
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
        
        //存在しないペットや他人のペットを表示できないようにする。
        if((is_null($pet)) or ($owner->id !== $pet->owner_id)){
            Log::warning("message");(__METHOD__.'('.__LINE__.') The user('.$owner->id.') tried to open pet('.$id.') so refused!!' );
            return '無効なページです。';
        }
        
        Log::debug(__METHOD__.'('.__LINE__.') ended by user('.$owner->id.')');
        return view('pets.edit',[
            'pet' => $pet
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
        //
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
