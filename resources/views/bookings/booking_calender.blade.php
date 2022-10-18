
@extends('layouts.user')

@section('title','予約日時選択')

@section('content')
<div class="container">
    <p>こんにちは、{{$owner -> getFullName()}}さん</p>
    <p>{{$pet -> getData()}}</p>
    <p>{{$course -> getCourseInfo()}}</p>
    <p>{{$salon -> salon_name}}</p>
    <p>{{$message}}</p>

    <br>
    <br>
    <p></p>
    @if ($before_date>=$today)
        
    <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $before_date])}}">前週へ</a>
    @endif
    @if ($after_date<=$maxBookingDate)
        
    <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $after_date])}}">次週へ</a>
    @endif
    <table class="table table-striped pc_only">
        <tr>
            <th>日付</th>
            @foreach($days as $day)
            @if ($day <=$maxBookingDate)
                
            <th>
                @php
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
            </th>
            @endif
            @endforeach
        </tr>
        
        @foreach($times as $time)
        <tr>
            <th>{{$time}}</th>
            @foreach($days as $day)
            @if ($day <=$maxBookingDate)            
                @if($capacities[$day][$timesNum[$time]] > 0)
                <td><a href="{{route('booking.selectCalender.date' , ['date' => $day, 'time' => $timesNum[$time]])}}">○</a></td>
                @elseif($capacities[$day][$timesNum[$time]] == -1)
                <td>定休日</td>
                @elseif($capacities[$day][$timesNum[$time]] == 0)
                <td>×</td>
                @endif
            @endif
            @endforeach
        </tr>
        @endforeach
    </table>

    <p class="sp_only">
        @php
            $week = array( "日", "月", "火", "水", "木", "金", "土" );
            echo date('m月d日',strtotime($date)) .'('. $week[ date('w',strtotime($date))] . ')';
            echo 'から';
            echo date('m月d日',strtotime($ed_date)) .'('. $week[ date('w',strtotime($ed_date))] . ')';
        @endphp
    </p>
    <table class="table table-striped sp_only">
        <tr>
            <th>日付</th>
            @foreach($days as $day)
            @if ($day <=$maxBookingDate)
                
            <th>
                @php
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $dateStr = date('d',strtotime($day)) .PHP_EOL.'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
            </th>
            @endif
            @endforeach
        </tr>
        
        @foreach($times as $time)
        <tr>
            <th>{{$time}}</th>
            @foreach($days as $day)
            @if ($day <=$maxBookingDate)
                @if($capacities[$day][$timesNum[$time]] > 0)
                <td><a href="{{route('booking.selectCalender.date' , ['date' => $day, 'time' => $timesNum[$time]])}}">○</a></td>
                @elseif($capacities[$day][$timesNum[$time]] == -1)
                <td>定休日</td>
                @elseif($capacities[$day][$timesNum[$time]] == 0)
                <td>×</td>
                @endif
            @endif
            @endforeach
        </tr>
        @endforeach
    </table>
    @if ($before_date>=$today)
        
        <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $before_date])}}">前週へ</a>
        @endif
        @if ($after_date<=$maxBookingDate)
            
        <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $after_date])}}">次週へ</a>
        @endif
</div>
@endsection 