@extends('layouts.admin')

@section('title', '予約の取消')

@section('content')
<div class="container">
    <p>予約ID:{{$booking ->id}}</p>
    <p>ペット:{{$booking -> getBookingInfoForStaff()}}</p>
    <p>コース:{{$booking -> getBookingCourseAndDogTypeInfoForStaff()}}</p>
    <p>日付:{{$booking -> date }}</p>
    <p>開始時間:{{$booking -> getStartTime()}}</p>
    <p>終了時間:{{$booking -> getEndTime()}}</p>
    <p>上記の予約を取り消しますか？</p>

    <form action="{{Route('booking.store')}}" method="post">
        @csrf
        <input type="submit" name="" id="" value="キャンセルする">
    </form>
</div>
@endsection

@section('footer')
@endsection