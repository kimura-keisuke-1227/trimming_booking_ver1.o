@extends('layouts.admin')

@section('title', '予約一覧')

@section('content')

<div class="container">
    <form action="{{Route('admin.checkBookings.dateAndSalon')}}">
        <label for="salon">
            ◆店舗(選択)
            <select name="salon" id="">
                @foreach($salons as $salon)
                    <option value="{{$salon -> id}}" 
                @if($salon->id == $selectedSalon->id)
                    selected
                @endif
                >{{$salon -> salon_name}}</option>
            @endforeach
            </select>
        </label>
        <br>
        <br>
        <label for="date">
            日付（入力）
            <input type="date" name="date" id="" value="{{$checkdate}}">
        </label>
        <br>
        <br>
        <input type="submit" name="" id="" value="表示">
    </form>
    <br>
    <br>

    <h3>{{$selectedSalon -> salon_name .' ' . $checkdate}}</h3>
    <table class="table table-striped">
        <tr>
            <th>店舗名</th>
            <th>日付</th>
            <th>開始時間</th>
            <th>終了時間</th>
            <th>飼い主</th>
            <th>ペット</th>
            <th>コース</th>
        </tr>
        @foreach($bookings as $booking)
        <tr>
            <td>{{$booking -> salon -> salon_name}}</td>
            <td>{{$booking -> date}}</td>
            <td>{{$booking -> getStartTime()}}</td>
            <td>{{$booking -> getEndTime()}}</td>
            <td>{{$booking -> pet -> user -> getUserInfo()}}</td>
            <td>{{$booking -> pet -> getData()}}</td>
            <td>{{$booking -> course -> courseMaster -> course}}</td>
        </tr>
        @endforeach

    </table>

    <br>
    <br>
    <h3>タイムテーブル</h3>
    <table class="table table-striped">
        @foreach($times as $time)
            <tr>
                <th>{{$time}}</th>
                @foreach($courses as $course)
                    @foreach($bookings as $booking)
                        @if($course->id == $booking->course->courseMaster->id)
                            @if($booking->st_time == $timesNums[$time])
                                <td class="bg_color{{$course->id}}">{{$booking->getBookingInfoForStaff()}}</td>
                            @elseif(($timesNums[$time]>$booking->st_time) and ($timesNums[$time]<$booking->ed_time))
                                <td class="bg_color{{$course->id}}">↓</td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </table>
</div>

@endsection

@section('footer')
@endsection