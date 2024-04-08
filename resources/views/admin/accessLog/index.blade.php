@extends('layouts.admin')

@section('content')
<div class="container">
    <table class="table table-striped">
        <tr>
            <th>id</th>
            <th>概要</th>
            <th>ユーザー</th>
            <th>日次</th>
        </tr>
        @foreach ($list_accessLog as $accessLog)
        <tr>
            <td>{{$accessLog->id}}</td>
            <td>{{$accessLog->summary}}</td>
            <td>{{$accessLog->id}}</td>
            <td>{{$accessLog->created_at}}</td>
        </tr>
        @endforeach
    </table>
    <div class="ml-auto">
        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
    </div>


</div>
@endsection

@section('footer')
@endsection