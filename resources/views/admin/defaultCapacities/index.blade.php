@extends('layouts.admin')

@section('title', '通常枠数')

@section('content')
<div class="container">
    <table class="table table-striped">
        <tr>
            <td>店舗名</td>
            <td>曜日</td>
            <td>通常枠数</td>
            <td></td>
        </tr>
        {{-- 
            @foreach($users as $user)
            <tr>
                <td>{{$user -> getUserInfo()}}</td>
                <td>{{$user -> email}}</td>
                <td>{{$user -> phone}}</td>
                <td>{{$user -> birthday}}</td>
            </tr>
            @endforeach
             --}}
        
    </table>

</div>
@endsection

@section('footer')
@endsection