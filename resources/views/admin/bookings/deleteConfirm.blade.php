@extends('layouts.admin')

@section('title', '予約の取消')

@section('content')
<div class="container">
    <p>{{$owner -> getFullName()}}様</p>
    <p>{{$pet -> getData()}}</p>
    <p>{{$course -> getCourseInfo()}}</p>
    <p>{{$date}}</p>
    <p>{{$timeStr}}</p>

    <p>上記の予約を取り消しますか？</p>

    <form action="{{Route('booking.store')}}" method="post">
        @csrf
        <input type="submit" name="" id="" value="予約する">
    </form>
</div>
@endsection

@section('footer')
@endsection