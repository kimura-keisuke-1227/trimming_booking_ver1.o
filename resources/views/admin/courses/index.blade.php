@extends('layouts.admin')

@section('content')
<div class="container">
    <table class="table table-striped">
        <tr>
            <td>名前</td>
            <td>アドレス</td>
            <td>電話</td>
            <td>生年月日</td>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{$user -> getUserInfo()}}</td>
            <td>{{$user -> email}}</td>
            <td>{{$user -> phone}}</td>
            <td>{{$user -> birthday}}</td>
        </tr>
        @endforeach
        
    </table>

</div>
@endsection

@section('footer')
@endsection