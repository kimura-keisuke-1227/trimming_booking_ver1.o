@extends('layouts.admin')

@section('title' , '基本コース一覧')

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
                <th>基本コース</th>
            </tr>
            @foreach ($courseMasters as $courseMaster)
            <tr>
                <td><a href="{{Route('admin.course_master.edit', $courseMaster)}}">{{$courseMaster->course}}</a></td>
            </tr>
            @endforeach
        </table>
        <a href="{{Route('admin.course_master.create')}}"></a>
    </form>
    <a href="{{Route('admin.course_master.create')}}">[基本コース追加]</a>

</div>
@endsection

@section('footer')
@endsection