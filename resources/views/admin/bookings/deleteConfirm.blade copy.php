@extends('layouts.admin')

@section('title', '予約の取消')

@section('content')
<div class="container">

    <p>上記の予約を取り消しますか？</p>

    <form action="{{Route('booking.store')}}" method="post">
        @csrf
        <input type="submit" name="" id="" value="予約を取り消す">
    </form>
</div>
@endsection

@section('footer')
@endsection