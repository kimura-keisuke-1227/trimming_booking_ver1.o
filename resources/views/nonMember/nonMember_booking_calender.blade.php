
@extends('layouts.user')

@section('title','予約日時選択')

@section('content')
<div class="container">

    <br>
    <br>
    <p></p>
    {{-- 
        <!-- <form action="{{Route('admin.getAcceptableCountWithSalonDate')}}" method="post">
            @csrf
            <label for="salon">店舗を選択</label>
            <select name="salon" id="salon">
                <option value="1">流星台</option>
                <option value="2">研究学園</option>
                <option value="3">越谷</option>
            </select>
            <br>
            <label for="date">表示開始日付</label>
            <input type="date" name="st_date" value="{{$date}}">
            <input type="submit" value="表示">
        </form> -->
    
     --}}

     <p>{{$salon -> salon_name}}</p>
     <p>{{$pet_name . '[' . $dog_type -> type . ']'}} </p>
     <p>{{$course -> getCourseInfo()}}</p>

    <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $before_date])}}">前週へ</a>
    <a href="{{route('booking.selectCalender.salonAndDay' , ['salon' => $salon -> id, 'st_date' => $after_date])}}">次週へ</a>
    <table class="table table-striped">
        <tr>
            <th>日付</th>
            @foreach($days as $day)
            <th>
                @php
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
            </th>
            @endforeach
        </tr>
        
        @foreach($times as $time)
        <tr>
            <th>{{$time}}</th>
            @foreach($days as $day)
                @if($capacities[$day][$timesNum[$time]] > 0)
                <td><a href="{{route('nonMember.booking.confirm' , ['date' => $day, 'time' => $timesNum[$time]])}}">{{$capacities[$day][$timesNum[$time]]}}</a></td>
                @else
                <td>×</td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </table>
</div>
@endsection 