<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

use App\Models\Dogtype;
use App\Models\CourseMaster;

class DogTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dogtypes = Dogtype::all();
        $courseMaster = CourseMaster::all();
        return view('admin.dogtypes.index',[
            'dogtypes' => $dogtypes,
            'courseMasters' => $courseMaster,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         Log::debug(__METHOD__.'('.__LINE__.')'.'$request:');
         Log::debug($request);

         // 犬種の登録

         //コースの登録
        return redirect()->route('admin.dogtypes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
