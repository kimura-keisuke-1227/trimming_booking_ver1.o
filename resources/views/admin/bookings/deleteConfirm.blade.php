@extends('layouts.admin')

@section('title', '予約の取消')

@section('content')
<div class="container">
<table class="table table-striped">
        <tr>
            <td>予約ID</td>
            <td>{{$booking -> id}}</td>
        </tr>
        <tr>
            <td>ペット</td>
            <td>{{$booking -> getBookingInfoForStaff()}}</td>
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

    <form action="{{Route('booking.delete', [
            'bookingId' => $booking-> id,
        ])}}" method="post">
        @csrf
        <input type="submit" name="" id="" value="キャンセルする">
    </form>
</div>
@endsection

@section('footer')
@endsection