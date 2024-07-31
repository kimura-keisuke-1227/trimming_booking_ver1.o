@extends('layouts.admin')

@section('content')
<div class="container">
    <table class="table table-striped">
        <tr>
            <th>id</th>
            <th>概要</th>
            <th>ユーザー</th>
            <th>日時</th>
            
        </tr>
        @foreach ($list_accessLog as $accesslog)
        <tr>
            <td>{{$accesslog->id}}</td>
            <td><a href="{{ Route('accesslog.show',['accesslog' => $accesslog->id]) }}">{{$accesslog->summary}}</a></td>
            <td>{{$accesslog->user->email}}</td>
            <td>{{$accesslog->created_at}}</td>
        </tr>
        @endforeach
    </table>
    {{ $list_accessLog->links() }}
    <div class="ml-auto">
        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
    </div>


</div>
@endsection

@section('footer')
@endsection