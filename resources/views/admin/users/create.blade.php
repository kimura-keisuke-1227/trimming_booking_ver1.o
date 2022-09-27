@extends('layouts.guest')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        @if(session()->has('success'))
        {{--
                    <!-- ▼▼▼▼登録完了メッセージ(全ページで共通)▼▼▼▼　-->
                 --}}
        <div class="mb-4 text-right">
            <div class="pl-6 pr-16 py-4 bg-white border-l-4 border-green-500 shadow-md rounded-r-lg inline-block ml-auto">
                <div class="flex items-center">
                    <span class="inline-block mr-2">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.5 0 0 4.5 0 10C0 15.5 4.5 20 10 20C15.5 20 20 15.5 20 10C20 4.5 15.5 0 10 0ZM14.2 8.3L9.4 13.1C9 13.5 8.4 13.5 8 13.1L5.8 10.9C5.4 10.5 5.4 9.9 5.8 9.5C6.2 9.1 6.8 9.1 7.2 9.5L8.7 11L12.8 6.9C13.2 6.5 13.8 6.5 14.2 6.9C14.6 7.3 14.6 7.9 14.2 8.3Z" fill="#17BB84"></path>
                        </svg>
                    </span>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        {{--
                    <!-- ▲▲▲▲登録完了メッセージ▲▲▲▲　-->
                --}}
        @endif
        @if(session()->has('error'))
        {{--

                    <!-- ▼▼▼▼エラーメッセージ(全ページで共通)▼▼▼▼　-->
                --}}
        <div class="mb-4 text-right">
            <div class="pl-6 pr-16 py-4 bg-white border-l-4 border-green-500 shadow-md rounded-r-lg inline-block ml-auto">
                <div class="flex items-center">
                    <span class="inline-block mr-2">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.5 0 0 4.5 0 10C0 15.5 4.5 20 10 20C15.5 20 20 15.5 20 10C20 4.5 15.5 0 10 0ZM14.2 8.3L9.4 13.1C9 13.5 8.4 13.5 8 13.1L5.8 10.9C5.4 10.5 5.4 9.9 5.8 9.5C6.2 9.1 6.8 9.1 7.2 9.5L8.7 11L12.8 6.9C13.2 6.5 13.8 6.5 14.2 6.9C14.6 7.3 14.6 7.9 14.2 8.3Z" fill="#17BB84"></path>
                        </svg>
                    </span>
                    <p class="text-green-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        {{--

                    <!-- ▲▲▲▲登録完了メッセージ▲▲▲▲　-->
                --}}
        @endif
        <div class="py-4 bg-white rounded">
            <form action="{{route('admin.users.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">ユーザ登録</h3>
                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
                    </div>
                </div>

                <div class="pt-4 px-6">
                    <!-- ▼▼▼▼エラーメッセージ▼▼▼▼　-->
                    @if($errors->any())
                    <div class="mb-8 py-4 px-6 border border-red-300 bg-red-50 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li class="text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <!-- ▲▲▲▲エラーメッセージ▲▲▲▲　-->

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="last_name">名前</label>
                        <input id="last_name" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="last_name" value="{{ old('name') }}">
                        <input id="first_name" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="first_name" value="{{ old('name') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="first_name">フリガナ(全角カナ)</label>
                        <input id="last_name_kana" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="last_name_kana" value="{{ old('name') }}">
                        <input id="first_name_kana" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="first_name_kana" value="{{ old('name') }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="email">通常利用店舗</label>
                        <select name="default_salon">
                            @foreach($salons as $salon)
                            <option value="{{$salon -> id}}">{{$salon -> salon_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="email">メールアドレス</label>
                        <input id="email" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="email">電話番号</label>
                        <input id="phone" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="phone" value="{{ old('email') }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="password">パスワード</label>
                        <input id="password" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="password" name="password">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="password_confirmation">パスワード(確認)</label>
                        <input id="password_confirmation" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="password" name="password_confirmation">
                    </div>


                    <!-- <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="image">画像</label>
                        <div class="flex items-end">
                            <img id="previewImage" src="/images/admin/noimage.jpg" data-noimage="/images/admin/noimage.jpg" alt="" class="rounded-full shadow-md w-32">
                            <input id="image" class="block w-full px-4 py-3 mb-2" type="file" accept='image/*' name="image">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="introduction">自己紹介文</label>
                        <textarea id="introduction" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="introduction" rows="2">{{ old('introduction') }}</textarea>
                    </div> -->
                </div>
            </form>
            <a href="{{Route('login')}}">ログイン画面へ戻る</a>
        </div>
    </div>
</section>

<script>
    // 画像プレビュー
    document.getElementById('image').addEventListener('change', e => {
        const previewImageNode = document.getElementById('previewImage')
        const fileReader = new FileReader()
        fileReader.onload = () => previewImageNode.src = fileReader.result
        if (e.target.files.length > 0) {
            fileReader.readAsDataURL(e.target.files[0])
        } else {
            previewImageNode.src = previewImageNode.dataset.noimage
        }
    })
</script>
@endsection