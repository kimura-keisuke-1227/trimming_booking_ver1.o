@extends('layouts.user')

@section('title')
    新パスワード入力フォーム
@endsection

@section('content')
    <div>
        <h1 class="title">新しいパスワードを設定</h1>
        <form method="POST" action="{{ route('password_reset.update') }}">
            @csrf
            <input type="hidden" name="reset_token" value="{{ $userToken->token }}">
            <div class="input-group">
                <label for="password" class="label">パスワード</label>
                <input type="password" name="password" class="input {{ $errors->has('password') ? 'incorrect' : '' }}">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
                @error('token')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="input-group">
                <label for="password_confirmation" class="label">パスワードを再入力</label>
                <input type="password" name="password_confirmation" class="input {{ $errors->has('password_confirmation') ? 'incorrect' : '' }}">
            </div>
            <button type="submit">パスワードを再設定</button>
        </form>
    </div>
@endsection