@extends('layouts.admin')

@section('content')
<div class="container">
    <select name="course" id="">
        <option value="1">シャンプー</option>
        <option value="2">シャンプー・カット</option>
    </select>
    <table class="table table-striped">
        <tr>
            <th>犬種</th>
            <th>所要時間(分)</th>
            <th>案内時間(分)</th>
        </tr>
        @foreach ($courses as $course)
        <tr>
            <td>{{$course->minute}}</td>
            <td>{{$course->minute}}</td>
            <td>{{$course->minute_for_show}}</td>
        </tr>
        @endforeach
        
    </table>

</div>
@endsection

@section('footer')
@endsection