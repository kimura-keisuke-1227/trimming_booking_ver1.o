@extends('layouts.user')

@section('title', '予約キャンセルの確認')

@section('content')
<div class="container">
    <p>{{$booking ->pet -> user -> getFullName()}}様</p>
    <p>{{$booking -> pet -> getData()}}</p>
    <p>{{$booking -> course -> getCourseInfo()}}</p>
    <p>{{$booking -> getStartTime()}}</p>
    <p>{{$booking ->message}}</p>

    <p>上記の予約を取り消しますか？</p>

    <form action="{{Route('booking.delete', [
            'bookingId' => $booking-> id,
        ])}}" method="post">
        @csrf
        @method('DELETE')
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">キャンセルする</button>
        </div>
    </form>
</div>
@endsection

@section('footer')
@endsection