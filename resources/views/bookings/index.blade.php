@extends('layouts.user')

@section('title', '予約一覧')

@section('content')

<div class="container">
   <p>◆予約日から{{$showBookingsAfterNDays}}日を経過したデータは表示されません。</p>

    <table class="table table-striped pc_only">
        <tr>
            <td>日付</td>
            <td>開始時間</td>
            <td>終了時間</td>            
            <td>ペット名</td>
            <td @if ($count_salons<=1)
                class="not_show_one_salon"
            @endif>店舗名</td>
            <td>コース</td>
            <td></td>
        </tr>
        @foreach($bookings as $booking)
            @if($booking->pet->user->id == $owner->id)
            <tr>
                <td>
                @php
                    $day = $booking -> date;
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
                </td>
                <td>{{$booking -> getStartTime()}}</td>
                <td>{{$booking -> getEndTimeForOwner()}}</td>                
                <td>{{$booking -> pet -> getData()}}</td>
                <td @if ($count_salons<=1)
                class="not_show_one_salon"
            @endif>{{$booking -> salon -> salon_name}}</td>
                <td>{{$booking -> getCourse()}}</td>
                <td>
                    @if($booking -> date > date('Y-m-d'))
                        <a href="{{route('booking.cancelConfirm' , ['bookingId' => $booking->id ])}}">キャンセル</a>
                    @else
                        キャンセル不可
                    @endif
                </td>
            </tr>

            @endif
        @endforeach
        
    </table>

    <table class="table table-striped sp_only">
        <tr><th>予約一覧</th></tr>
    @foreach($bookings as $booking)
        @if($booking->pet->user->id == $owner->id)
        <tr><td>
            予約日： 
            @php
                $day = $booking -> date;
                $week = array( "日", "月", "火", "水", "木", "金", "土" );
                $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                echo $dateStr; 
            @endphp<br>
            開始時間：{{$booking -> getStartTime()}} <br>
            ペット：{{$booking -> pet -> getData()}} <br>
            予約店舗：{{$booking -> salon -> salon_name}} <br>
            コース：{{$booking -> getCourse()}} <br>
            @if($booking -> date > date('Y-m-d'))
                <a href="{{route('booking.cancelConfirm' , ['bookingId' => $booking->id ])}}">[キャンセル]</a>
            @else
                キャンセル不可
            @endif
        </td></tr>
        @endif
    @endforeach
    </table>

    <a href="{{ Route('user.newBooking') }}">新規予約へ</a>
</div>

@endsection

@section('footer')
@endsection