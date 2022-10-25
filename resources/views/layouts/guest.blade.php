<!DOCTYPE html>
<html lang="ja">

<head>
    <title>ゲスト画面</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap">
    <link rel="stylesheet" href="{{ asset('/css/admin/tailwind/tailwind.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/css/admin/select2.min.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <script src="{{ asset('/js/main.js')}}"></script>
    <script src="{{ asset('/js/admin/jquery-3.6.0.slim.min.js')}}"></script>
    <script src="{{ asset('/js/admin/select2.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body class="antialiased bg-body text-body font-body">

{{-- 
    <div>
        <!-- ▼▼▼▼共通ヘッダー(SP)▼▼▼▼　-->
        <nav class="lg:hidden py-6 px-6 bg-gray-800">
            <div class="flex items-center justify-between">
                <a class="text-2xl text-white font-semibold" href="<?php echo url('')?>/">トップ画面</a>
                <button class="navbar-burger flex items-center rounded focus:outline-none">
                    <svg class="text-white bg-indigo-500 hover:bg-indigo-600 block h-8 w-8 p-2 rounded" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <title>Mobile menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                    </svg>
                </button>
            </div>
        </nav>
        <!-- ▲▲▲▲共通ヘッダー(SP)▲▲▲▲　-->
        <!-- ▼▼▼▼共通サイドナビ▼▼▼▼　-->

        <div class="hidden lg:block navbar-menu relative z-50">
            <div class="navbar-backdrop fixed lg:hidden inset-0 bg-gray-800 opacity-10"></div>
            <nav class="fixed top-0 left-0 bottom-0 flex flex-col w-3/4 lg:w-80 sm:max-w-xs pt-6 pb-8 bg-gray-800 overflow-y-auto">
                <h1 class="px-6 pb-6 mb-6 lg:border-b border-gray-700">
                    <a class="text-xl text-white font-semibold" href="/">トップ画面</a>
                </h1>
   
            </nav>
        </div>
        
        <!-- ▲▲▲▲共通サイドナビ▲▲▲▲　-->
 --}}

        <div class="container">

            <div class="mx-auto lg:ml-80">
                <!-- ▼▼▼▼共通ヘッダー(PC)▼▼▼▼　-->
                <!-- <section class="py-5 px-6 bg-white shadow hidden lg:block">
                    <div class="flex items-center justify-end">
                        <span class="text-sm text-gray-500">店長</span>
                        <img class="ml-3 w-10 h-10 rounded-full object-cover object-right" src="/images/placeholders/admin/user.jpg" alt="">
                    </div>
                </section> -->
                <!-- ▲▲▲▲共通ヘッダー(PC)▲▲▲▲　-->
    
                <main class="py-4 px-6">
                    @if(session()->has('success'))
                    <!-- ▼▼▼▼登録完了メッセージ(全ページで共通)▼▼▼▼　-->
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
                    <!-- ▲▲▲▲登録完了メッセージ▲▲▲▲　-->
                    @endif
    
                    <!-- ▼▼▼▼ページ毎の個別内容▼▼▼▼　-->
                    @yield('content')
                    <!-- ▲▲▲▲ページ毎の個別内容▲▲▲▲　-->
                </main>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>