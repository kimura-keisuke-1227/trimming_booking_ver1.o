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
                <th>基本コース</th>
            </tr>
            @foreach ($courseMasters as $courseMaster)
            <tr>
                <td>{{$courseMaster->course}}</td>
            </tr>
            @endforeach
        </table>
    </form>

</div>
@endsection

@section('footer')
@endsection