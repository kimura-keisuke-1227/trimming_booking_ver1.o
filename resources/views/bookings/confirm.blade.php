@extends('layouts.user')

@section('title', '予約内容の確認')

@section('content')
<div class="container">
    <p>{{$owner -> getFullName()}}様</p>
    <p>{{$pet -> getData()}}</p>
    <p>{{$course -> getCourseInfo()}}</p>
    <p>{{$timeStr}}</p>
    <p>{{$message}}</p>

    <p>上記下の内容で予約を行いますか？</p>
    <p>(注意)予約可能時間を過ぎた場合や、空き状況が変化した場合予約が出来ない場合があります。</p>

    <form action="{{Route('booking.store')}}" method="post">
        @csrf
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">予約する</button>
        </div>
    </form>
</div>
@endsection

@section('footer')
@endsection