@extends('layouts.user')

@section('title', '予約一覧')

@section('content')

<div class="container">
    <p>該当の予約は{{$count}}件です。</p>
    <table class="table table-striped">
        <tr>
            <td>日付</td>
            <td>時間</td>
            
            <td>ペット名</td>
            <td>コース</td>
        </tr>
        @foreach($bookings as $booking)
        <tr>
            <td>{{$booking -> date}}</td>
            <td>{{$booking -> getStartTime()}}</td>
            
            <td>{{$booking -> pet -> getData()}}</td>
            <td>{{$booking -> getCourse()}}</td>
        </tr>
        @endforeach
        
    </table>

    <a href="/new_booking">新規予約へ</a>
</div>

@endsection

@section('footer')
@endsection