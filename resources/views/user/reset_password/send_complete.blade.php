@extends('layouts.guest')

@section('title')
    パスワードリセットメール送信完了
@endsection

@section('content')
    <div>
        <h1>パスワードリセットメールを送信しました。</h1>

        <a href="{{ route('login') }}">TOPへ</a>
    </div>
@endsection