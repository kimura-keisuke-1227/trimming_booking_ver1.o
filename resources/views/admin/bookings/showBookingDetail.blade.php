@extends('layouts.user')

@section('title', '予約の取消')

@section('content')
<div class="container">
    <p>{{$booking -> id}}</p>

    <button type="button" onClick="history.back()">戻る</button>

</div>
@endsection

@section('footer')
@endsection