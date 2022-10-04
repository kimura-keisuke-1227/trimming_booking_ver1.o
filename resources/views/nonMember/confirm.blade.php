@extends('layouts.user')

@section('title', '予約内容の確認')

@section('content')
<div class="container">
    <p>{{$salon -> salon_name}}</p>
     <p>{{$pet_name . '[' . $dog_type -> type . ']'}} </p>
     <p>{{$course -> getCourseInfo()}}</p>
     <p>{{$date}}</p>
    {{--
        <p>{{$owner -> getFullName()}}様</p>
        <p>{{$pet -> getData()}}</p>
        <p>{{$date}}</p>
        <p>{{$timeStr}}</p>
        --}}
    <p>上記下の内容で予約を行いますか？</p>

    <form action="{{Route('booking.store')}}" method="post">
        @csrf
        <input type="submit" name="" id="" value="予約する">
    </form>
</div>
@endsection

@section('footer')
@endsection