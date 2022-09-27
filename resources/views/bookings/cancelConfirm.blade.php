@extends('layouts.user')

@section('title', '予約キャンセルの確認')

@section('content')
<div class="container">
    <p>{{$booking ->pet -> user -> getFullName()}}様</p>
    <p>{{$booking -> pet -> getData()}}</p>
    <p>{{$booking -> course -> getCourseInfo()}}</p>
    <p>{{$booking -> date}}</p>
    <p>{{$booking -> getStartTime()}}</p>

    <p>上記下の内容で予約を行いますか？</p>

    <form action="{{Route('booking.delete', [
            'bookingId' => $booking-> id,
        ])}}" method="post">
        @csrf
        @method('DELETE')
        <input type="submit" name="" id="" value="キャンセルする">
    </form>
</div>
@endsection

@section('footer')
@endsection