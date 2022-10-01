@extends('layouts.user')

@section('title', '予約一覧')

@section('content')

<div class="container">
   <p>◆予約日から{{$showBookingsAfterNDays}}日を経過したデータは表示されません。</p>

    <table class="table table-striped">
        <tr>
            <td>日付</td>
            <td>時間</td>
            
            <td>ペット名</td>
            <td>店舗名</td>
            <td>コース</td>
            <td></td>
        </tr>
        @foreach($bookings as $booking)
            @if($booking->pet->user->id == $owner->id)
            <tr>
                <td>{{$booking -> date}}</td>
                <td>{{$booking -> getStartTime()}}</td>
                
                <td>{{$booking -> pet -> getData()}}</td>
                <td>{{$booking -> salon -> salon_name}}</td>
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

    <a href="/new_booking">新規予約へ</a>
</div>

@endsection

@section('footer')
@endsection