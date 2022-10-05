@extends('layouts.guest')

@section('title')
    新パスワード入力フォーム
@endsection

@section('content')
    <div>
        <h1 class="title">新しいパスワードを設定</h1>
        <form method="POST" action="{{ route('password_reset.update') }}">
            @csrf
            <input type="hidden" name="reset_token" value="{{ $userToken->token }}">

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2" for="password">パスワード</label>
                <input id="password" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded input {{ $errors->has('password') ? 'incorrect' : '' }}" type="password" name="password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
                @error('token')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2" for="password">パスワードを再入力</label>
                <input id="password" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded input {{ $errors->has('password_confirmation') ? 'incorrect' : '' }}" type="password" name="password_confirmation">
            </div>
            <div class="ml-auto">
                <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">パスワードを再設定</button>
            </div>
        </form>
    </div>
@endsection