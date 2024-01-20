@extends('layouts.admin')

@section('content')
<div class="container">
    {{-- 
        <select name="course" id="">
            <option value="1">シャンプー</option>
            <option value="2">シャンプー・カット</option>
        </select>
        --}}
    <form action="{{Route('admin.course.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>犬種</th>
                <th>コース</th>
                <th>所要時間(分)</th>
                <th>案内時間(分)</th>
            </tr>
            @foreach ($courses as $course)
            <tr>
                <td>{{$course->dogtype->type}}</td>
                <td>{{$course->courseMaster->course}}</td>
                <td><input id="minute_{{ $course->id }}" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="minute_{{ $course->id }}"  value="{{$course->minute}}" min="0"></td>
                <td><input id="minute_for_show_{{ $course->id }}" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="minute_for_show{{ $course->id }}"  value="{{$course->minute_for_show}}" min="0"></td>
            </tr>
            @endforeach
        </table>
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
        </div>
    </form>

</div>
@endsection

@section('footer')
@endsection