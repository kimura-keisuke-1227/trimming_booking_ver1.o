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
    <table class="table table-striped pc_only">
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
                @if($capacities[$day][$timesNum[$time]] == 1)
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">○</a></td>
                @elseif($capacities[$day][$timesNum[$time]] == 2)
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">○</a></td>
                @else
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">×</a></td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </table>
    <table class="table table-striped sp_only">
        <tr>
            <th>日付</th>
            @foreach($days as $day)
            <th>
                @php
                    $week = array( "日", "月", "火", "水", "木", "金", "土" );
                    $dateStr = date('m/',strtotime($day)) .PHP_EOL.date('d',strtotime($day)) .PHP_EOL.'('. $week[ date('w',strtotime($day))] . ')';
                    echo $dateStr; 
                @endphp
            </th>
            @endforeach
        </tr>
        
        @foreach($times as $time)
        <tr>
            <th id="{{$timesNum[$time]}}" >
                {{ $time }}</th>
            @foreach($days as $day)
                <!-- <td>{{$capacities[$day][$timesNum[$time]]}}</td> -->
                @if($capacities[$day][$timesNum[$time]] == 1)
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">○</a></td>
                @elseif($capacities[$day][$timesNum[$time]] == 2)
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">○</a></td>
                @else
                <td><a href="{{ Route('admin.switchOX',[
                    'salon' => $selectedSalon,
                    'course' => $course_id,
                    'date' => $day,
                    'time' => $timesNum[$time],
                    'st_date' => $st_date,
                    'count' => $capacities[$day][$timesNum[$time]],
                    ])}}">×</a></td>
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