@extends('layouts.admin')

@section('title', '空き枠一覧')

@section('content')

<div class="container">
    {{-- 

    <form action="{{Route('admin.getAcceptableCountWithSalonDate')}}" method="post">
        @csrf
        <label for="salon"></label>
        <select name="salon" id="">
            @foreach($salons as $salon)
            <option value="{{$salon -> id}}" 
            @if($salon->id == $selectedSalon->id)
                selected
            @endif
            >{{$salon -> salon_name}}</option>
            @endforeach
        </select>
        <input type="date" name="st_date" value="{{$date}}">
        <input type="submit" value="表示">
    </form>

    <br>

    <br>
    <h3>{{$selectedSalon->salon_name}}</h3>
    --}}
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
                <!-- <td>{{$capacities[$day][$timesNum[$time]]}}</td> -->
                @if($capacities[$day][$timesNum[$time]] > 0)
                <td>{{$capacities[$day][$timesNum[$time]]}}</td>
                @else
                <td>×</td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </table>
 
    <a href="{{Route('admin.makebooking')}}">新規予約へ</a>
</div>

@endsection

@section('footer')
@endsection