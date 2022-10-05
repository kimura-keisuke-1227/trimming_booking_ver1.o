@extends('layouts.guest')

@section('title')
    パスワード再設定メール送信フォーム
@endsection

@section('content')
    <div>
    <div class="mx-auto lg:ml-80"></div>
        <h1>パスワード再設定メール送信フォーム</h1>
        <form method="POST" action="{{ route('password_reset.email.send') }}">
            @csrf
            <div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="email">メールアドレス</label>
                    <input id="email" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="email" name="email" value="{{ old('email') }}">
                </div>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="ml-auto">
                <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">再設定用メールを送信</button>
            </div>
        </form>

        <a href="{{ route('login') }}">戻る</a>
    </div>
@endsection