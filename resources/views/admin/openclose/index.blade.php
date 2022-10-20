@extends('layouts.admin')

@section('title', '空き枠一覧')

@section('content')

<div class="container">


    <form action="{{Route('admin.checkOpenCloseWithDate.change')}}" method="get">
        @csrf
        <label for="salon"></label>
        <select name="salon" id="">
            @foreach($salons as $salon)
            <option value="{{$salon -> id}}" 
            @if($salon->id == $selectedSalon)
                selected
            @endif
            >{{$salon -> salon_name}}</option>
            @endforeach
        </select>
        <select name="course" id="course">
            @foreach($courses as $course)
            <option value="{{$course -> id}}" 
            @if($course_id == $course -> id)
            selected
            @php
                $displayCourse = $course -> course;
            @endphp
            @endif
            >{{$course -> course}}</option>
            @endforeach
        </select>
        <input type="date" name="st_date" value="{{$st_date}}">
        <input type="submit" value="表示">
    </form>

    <br>

    <br>
    <h3>{{$salons->find($selectedSalon)->salon_name}}  
        @php
            echo $displayCourse;
        @endphp
    </h3>

    <a href="{{Route('admin.checkOpenCloseWithDate', [
        'salon' =>    $selectedSalon, 
        'course' => $course_id,
        'date'=>$before_start_day,
        ])}}">前週へ</a>
    <a href="{{Route('admin.checkOpenCloseWithDate', [
        'salon' =>    $selectedSalon, 
        'course' => $course_id,
        'date'=>$next_start_day,
        ])}}">次週へ</a>

    @php
        if($course_id==2){
            $another_course_id = 1;
        } else{
            
            $another_course_id = 2;
        }

    @endphp
    <a href="{{Route('admin.checkOpenCloseWithDate', [
        'salon' =>    $selectedSalon, 
        'course' => $another_course_id,
        'date'=>$st_date,
        ])}}">コース切り替え</a>
    <form action="{{Route('admin.changeOXlist.all')}}" method="POST">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>日付</th>
                @foreach($days as $day)
                <th class="pc_only">
                    @php
                        $week = array( "日", "月", "火", "水", "木", "金", "土" );
                        $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                        echo $dateStr; 
                    @endphp
                </th>
                <th class="pc_only">
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
                    @if($capacities[$day][$timesNum[$time]] == 1)
                    <td id="td_{{$day}}_{{ $timesNum[$time]}}" class="{{$day}}_{{ $timesNum[$time]}} opened"> <p>○</p>
                        <input id="opened_{{$day}}_{{$timesNum[$time]}}" type="hidden" class="opened_input" name="{{$day}}_{{$timesNum[$time]}}" id="" value="1">
                    </td>
                    @elseif($capacities[$day][$timesNum[$time]] == 2)
                    <td id="td_{{$day}}_{{ $timesNum[$time]}}"  class="{{$day}}_{{ $timesNum[$time]}} opened"><p>○</p>
                        <input id="opened_{{$day}}_{{$timesNum[$time]}}" type="hidden"  class="opened_input" name="{{$day}}_{{$timesNum[$time]}}" id="" value="1">
                        
                    </td>
                    @elseif($capacities[$day][$timesNum[$time]] == 0)
                    <td id="td_{{$day}}_{{ $timesNum[$time]}}"  class="{{$day}}_{{ $timesNum[$time]}} closed">
                        <p>×</p>
                        
                        <input id="closed_{{$day}}_{{$timesNum[$time]}}" class="closed_input" type="hidden" name="{{$day}}_{{$timesNum[$time]}}" id="" value="0">
                    </td>
                    @elseif($capacities[$day][$timesNum[$time]] == -1)
                    <td>定休日</td>
                    <input id="closed_{{$day}}_{{$timesNum[$time]}}" class="closed_input" type="hidden" name="{{$day}}_{{$timesNum[$time]}}" id="" value="-1">
                    @endif
                @endforeach
            </tr>
            @endforeach
        </table>
        <input type="date" name="st_date" value="{{$st_date}}">
        <input type="date" name="ed_date" value="{{$ed_date}}">
        <input type="integer" name="st_time" value="{{ $st_time }}">
        <input type="integer" name="ed_time" value="{{$ed_time}}">
        <input type="integer" name="salon_id" value="{{$selectedSalon}}">
        <input type="integer" name="course_id" value="{{$course_id}}">
        <button type="submit">登録</button>
    </form>

    <a href="{{Route('admin.makebooking')}}">新規予約へ</a>
</div>

<script>
    $(document).on("click",'.opened', function () {
        console.log('opened was clicked!');
        $(this).children('p').text("×");
        $(this).children('input').val("0");
        $(this).removeClass('opened');
        $(this).addClass('closed');
    });
    $(document).on("click",'.closed', function () {
        console.log('closed was clicked!');
        $(this).children('p').text("○");
        $(this).children('input').val("1");
        $(this).removeClass('closed');
        $(this).addClass('opened');
    });
</script>

@endsection

@section('footer')
@endsection