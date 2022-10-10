@extends('layouts.user')

@section('title', '予約の取消')

@section('content')
<div class="container">
    <p>{{$booking -> id}}</p>

    <p>上記の予約を取り消しますか？</p>

</div>
@endsection

@section('footer')
@endsection