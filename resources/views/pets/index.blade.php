@extends('layouts.user')

@section('title','ペット一覧')

@section('content')

<div class="container">
    <p>こんにちは、<br class="sp_only">{{$owner -> getFullName()}}さん</p>
    <table class="table table-striped pc_only">
        <tr>
            <th>名前</th>
            <th>犬種</th>
            <th>体重(kg)</th>
            <th>生年月日</th>
            <th></th>
            {{-- 
                 --}}
        </tr>
        @foreach($pets as $pet)
        <tr>
            <td>{{$pet -> name}}</td>
            <td>{{$pet -> dogtype -> type}}</td>
            <td>{{$pet -> weight}}</td>
            <td>{{$pet -> getPetBirthday()}}</td>
            <td><a href="{{Route('pets.edit',['pet' => $pet])}}">[詳細・修正]</a></td>
            {{-- 
                 --}}
        </tr>
        @endforeach

    </table>

    <table class="table table-striped sp_only">
        <tr>
            <th>登録済みペット一覧</th>
        </tr>
        @foreach($pets as $pet)
        <tr><td>
            {{$pet -> dogtype -> type}} <br>
            {{$pet -> getData()}} <br>
            {{$pet -> birthday}} <br>
            <a href="{{Route('pets.edit',['pet' => $pet])}}">[詳細・修正]</a>
        </td></tr>
        @endforeach
    </table>
    <a href="{{ Route('user.dog_register')}}">ペット登録へ</a>

</div>
@endsection

@section('footer')
@endsection