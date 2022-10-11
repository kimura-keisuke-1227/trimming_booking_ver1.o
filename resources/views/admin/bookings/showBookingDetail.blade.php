@extends('layouts.user')

@section('title', '予約の詳細')

@section('content')
<div class="container">
    <table class="table table-striped">
        <tr>
            <td>予約ID</td>
            <td>{{$booking -> id}}</td>
        </tr>
        <tr>
            <td>ペット</td>
            <td>ペット情報</td>
        </tr>
        <tr>
            <td>日付</td>
            <td>{{$booking -> date}}</td>
        </tr>
        <tr>
            <td>開始時間</td>
            <td>{{$booking -> getStartTime()}}</td>
        </tr>
        <tr>
            <td>終了時間</td>
            <td>{{$booking -> getEndTime()}}</td>
        </tr>
        <tr>
            <td>お客様向け終了時間</td>
            <td>{{$booking -> getEndTimeForOwner()}}</td>
        </tr>
        <tr>
            <td>メッセージ</td>
            <td>{{$booking -> getBookingMessage()}}</td>
        </tr>
    </table>

    <br>

    <button type="button" onClick="history.back()">戻る</button>

</div>
@endsection

@section('footer')
@endsection