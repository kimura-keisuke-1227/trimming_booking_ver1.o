@extends('layouts.admin')

@section('title', '空き枠一覧')

@section('content')

<div class="container">


    <form action="{{Route('admin.checkOpenCloseWithDate.change')}}" method="get">
        @csrf
        <label for="salon"></label>
        <select name="salon" id="">
            @foreach($salons as $salon)
            <option value="{{$salon -> id}}" @if($salon->id == $selectedSalon)
                selected
                @endif
                >{{$salon -> salon_name}}</option>
            @endforeach
        </select>
        <select name="course" id="course">
            @foreach($courses as $course)
            <option value="{{$course -> id}}" @if($course_id==$course -> id)
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
        <br><br>
    <form action="{{Route('admin.changeOXlist.all')}}" method="POST">
        @csrf
        <div class="flex px-6 pb-4 border-b">
            <div class="">
                <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">空き枠登録</button>
            </div>
        </div>
        <table class="table table-striped">
            <tr>
                <th>日付</th>
                @foreach($days as $day)
                <th class="">
                    <p class="pc_only">
                        @php
                        $week = array( "日", "月", "火", "水", "木", "金", "土" );
                        $dateStr = date('m/d',strtotime($day)) .'('. $week[ date('w',strtotime($day))] . ')';
                        echo $dateStr;
                        @endphp
                    </p>
                    <p class="sp_only">
                        @php
                        $week = array( "日", "月", "火", "水", "木", "金", "土" );
                        $dateStr = date('m/',strtotime($day)).PHP_EOL.date('d',strtotime($day)).PHP_EOL .'('. $week[ date('w',strtotime($day))] . ')';
                        echo $dateStr;
                        @endphp
                    </p>
                </th>

                @endforeach
            </tr>

            @foreach($times as $time)
            <tr>
                <th>{{$time}}</th>
                @foreach($days as $day)
                <!-- <td>{{$capacities[$day][$timesNum[$time]]}}</td> -->
                @if($capacities[$day][$timesNum[$time]] == 1)
                <td id="td_{{$day}}_{{ $timesNum[$time]}}" class="{{$day}}_{{ $timesNum[$time]}} opened">
                    <p>○</p>
                    <input id="opened_{{$day}}_{{$timesNum[$time]}}" type="hidden" class="opened_input" name="{{$day}}_{{$timesNum[$time]}}" id="" value="1">
                </td>
                @elseif($capacities[$day][$timesNum[$time]] == 2)
                <td id="td_{{$day}}_{{ $timesNum[$time]}}" class="{{$day}}_{{ $timesNum[$time]}} opened">
                    <p>○</p>
                    <input id="opened_{{$day}}_{{$timesNum[$time]}}" type="hidden" class="opened_input" name="{{$day}}_{{$timesNum[$time]}}" id="" value="1">

                </td>
                @elseif($capacities[$day][$timesNum[$time]] == 0)
                <td id="td_{{$day}}_{{ $timesNum[$time]}}" class="{{$day}}_{{ $timesNum[$time]}} closed">
                    <p>×</p>

                    <input id="closed_{{$day}}_{{$timesNum[$time]}}" class="closed_input" type="hidden" name="{{$day}}_{{$timesNum[$time]}}" id="" value="0">
                </td>
                @elseif($capacities[$day][$timesNum[$time]] == -1)
                @if ($time==$timeOfFirst)
                            <td rowspan={{$timesCount}}>定休日</td>
                        @endif
                <input id="closed_{{$day}}_{{$timesNum[$time]}}" class="closed_input" type="hidden" name="{{$day}}_{{$timesNum[$time]}}" id="" value="-1">
                @endif
                @endforeach
            </tr>
            @endforeach
        </table>
        <input type="hidden" name="st_date" value="{{$st_date}}">
        <input type="hidden" name="ed_date" value="{{$ed_date}}">
        <input type="hidden" name="st_time" value="{{ $st_time }}">
        <input type="hidden" name="ed_time" value="{{$ed_time}}">
        <input type="hidden" name="salon_id" value="{{$selectedSalon}}">
        <input type="hidden" name="course_id" value="{{$course_id}}">
        <div class="flex px-6 pb-4 border-b">
            <div class="">
                <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">空き枠登録</button>
            </div>
        </div>
    </form>

    <a href="{{Route('admin.makebooking')}}">新規予約へ</a>
</div>

<script>
    $(document).on("click", '.opened', function() {
        console.log('opened was clicked!');
        $(this).children('p').text("×");
        $(this).children('input').val("0");
        $(this).removeClass('opened');
        $(this).addClass('closed');
    });
    $(document).on("click", '.closed', function() {
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