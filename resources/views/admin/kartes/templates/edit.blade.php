@extends('layouts.admin')

@section('title','カルテ用テンプレート一覧')

@section('content')

<div class="container">
    <h4>テンプレート名</h4>
    <div class="mb-6">
        <label class="block text-sm font-medium mb-2" for="message">{{$karteFormat->title}}</label>
        <textarea id="message" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="karte_for_staff" rows="20">{{$karteFormat->format}}</textarea>
    </div>

    <div class="ml-auto">
        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
    </div>

</div>
@endsection

@section('footer')
@endsection