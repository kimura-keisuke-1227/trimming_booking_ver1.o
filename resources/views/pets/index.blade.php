@extends('layouts.user')

@section('title','ペット一覧')

@section('content')

<div class="container">
    <p>こんにちは、{{$owner -> name}}さん</p>
    <table class="table table-striped">
        <tr>
            <td>犬種</td>
            <td>名前</td>
            <td>生年月日</td>
        </tr>
        @foreach($pets as $pet)
        <tr>
            <td>{{$pet -> dogtype -> type}}</td>
            <td>{{$pet -> getData()}}</td>
            <td>{{$pet -> birthday}}</td>
        </tr>
        @endforeach
        
    </table>
    <a href="/dog_register">ペット登録へ</a>

</div>
@endsection

@section('footer')
@endsection