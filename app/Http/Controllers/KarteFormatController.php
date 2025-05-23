<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKarteFormatRequest;
use App\Http\Requests\UpdateKarteFormatRequest;
use App\Models\Karte;
use App\Models\KarteFormat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class KarteFormatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        $karteFormats = KarteFormat::all();
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.kartes.templates.index',[
            'karteFormats' =>  $karteFormats
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
    public function store(StoreKarteFormatRequest $request)
    {

    } 

    /**
     * Display the specified resource.
     */
    public function show(KarteFormat $karteFormat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * カルテのフォーマットを編集
     */
    public function edit(KarteFormat $karteFormat)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');
        return view('admin.kartes.templates.edit',[
            'karteFormat' =>  $karteFormat
        ]);
    }

    /**
     * Update the specified resource in storage.
     * カルテのフォーマットを編集したものを反映
     */
    public function update(UpdateKarteFormatRequest $request, KarteFormat $karteFormat)
    {
        Log::debug(__METHOD__.'('.__LINE__.')'.'start!');
        Log::debug(__METHOD__.'('.__LINE__.')'.'end!');

        $karteFormat['format'] = "フォーマットテスト";
        $validated = $request->validated();
        $karteFormat -> fill([
            'format' => $validated['format'],
           ]);
        $karteFormat->save();
        return redirect(Route('admin.karte.template.index'))
        ->with('success','フォーマットを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KarteFormat $karteFormat)
    {
        //
    }
}
