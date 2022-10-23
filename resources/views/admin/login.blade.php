<!DOCTYPE html>
<html lang="ja">

<head>
  <title>ログイン</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="{{ asset('/css/tailwind/tailwind.min.css')}}">

  <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
  <script src="{{ asset('/js/main.js')}}"></script>
</head>

<body class="antialiased bg-body text-body font-body">
  <div>
    <section class="h-screen py-48 bg-blueGray-50">
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
      <div class="container px-4 mx-auto">
        <div class="flex max-w-md mx-auto flex-col text-center">
          <div class="mt-12 mb-8 p-8 bg-white rounded shadow">
            <h1 class="mb-6 text-3xl">con affetto<br>予約システム</h1>
            <a href="{{ route('admin.users.create')}}" class="new_entry">新規登録はこちらから</a>
            <br><br>
            <p>メールアドレス・パスワードを入力して<br>ログインしてください。</p>
            <br>
            @if($errors->any())
            <div class="mb-8 py-4 px-6 border border-red-300 bg-red-50 rounded">
              <p class="text-red-400">ログインに失敗しました</p>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
              @csrf

              <div class="flex mb-4 px-4 bg-blueGray-50 rounded">
                <input class="w-full py-4 text-xs placeholder-blueGray-400 font-semibold leading-none bg-blueGray-50 outline-none" type="email" placeholder="メールアドレス" name="email" value="{{ old('email') }}">
                <svg class="h-6 w-6 ml-4 my-auto text-blueGray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
              </div>


              <div class="flex mb-6 px-4 bg-blueGray-50 rounded">
                <input class="w-full py-4 text-xs placeholder-blueGray-400 font-semibold leading-none bg-blueGray-50 outline-none" type="password" placeholder="パスワード" name="password">
                <button class="ml-4">
                  <svg class="h-6 w-6 my-auto text-blueGray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                </button>
              </div>
              <button type="submit" class="block w-full p-4 text-center text-xs text-white font-semibold leading-none bg-blue-600 hover:bg-blue-700 rounded">ログイン</button>
            </form>
          </div>
          
            {{-- 
              <a href="{{ route('nonMember.beginBooking')}}">登録なしに予約する方はこちら</a>
              
              --}}
              
          <a href="{{ route('password_reset.email.form') }}">パスワードをお忘れの方</a>
        </div>
      </div>
    </section>
  </div>
</body>

</html>