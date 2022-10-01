@extends('layouts.user')

@section('title', '予約一覧')

@section('content')

<div class="container">
   <p>◆予約日から１週間を経過したデータは表示されません。</p>

    <table class="table table-striped">
        <tr>
            <td>日付</td>
            <td>時間</td>
            
            <td>ペット名</td>
            <td>店舗名</td>
            <td>コース</td>
        </tr>
        @foreach($bookings as $booking)
            <tr>
                <td>
                @php
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $day = $booking-> date;
                    $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
                </td>
                <td>{{$booking->getStartTime()}}</td>
                <td>{{$booking->pet->getDataWithOwner()}}</td>
                <td>{{$booking->salon->salon_name}}</td>
                <td>{{$booking->course->courseMaster->course}}</td>
            </tr>
        @endforeach
        
    </table>

    <a href="/new_booking">新規予約へ</a>
</div>

@endsection

@section('footer')
@endsection