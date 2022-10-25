<!DOCTYPE html>
<html lang="ja">
<head>
    <title>@yield('title', 'ねこカフェららべる')</title>
    <meta charset="utf-8">
    <meta name="robots" content="noindex" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="{{ asset('/css/tailwind/tailwind.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">
    <script src="{{ asset('/js/main.js')}}"></script>
</head>
<body class="antialiased bg-body text-body font-body">

<!-- ▼▼▼▼共通ヘッダー▼▼▼▼　-->
<header>
    <div class="container px-4 mx-auto">
        <nav class="flex items-center justify-between py-6">
            <a class="text-3xl font-semibold leading-none" href="/">ねこカフェららべる</a>
            <ul class="hidden lg:flex ml-12 mr-auto space-x-12">
                <li><a class="text-sm text-blueGray-400 hover:text-blueGray-500" href="#">設備</a></li>
                <li><a class="text-sm text-blueGray-400 hover:text-blueGray-500" href="#">ねこちゃんたち</a></li>
                <li><a class="text-sm text-blueGray-400 hover:text-blueGray-500" href="/blogs">ブログ</a></li>
                <li><a class="text-sm text-blueGray-400 hover:text-blueGray-500" href="#">メニュー</a></li>
                <li><a class="text-sm text-blueGray-400 hover:text-blueGray-500" href="#">よくあるご質問</a></li>
            </ul>
            <div>
                <a class="mr-2 inline-block px-4 py-3 text-xs text-blue-500 hover:text-blue-600 leading-none border border-blue-200 hover:border-blue-300 rounded" href="/contact">お問い合わせ</a>
                <a class="inline-block px-4 py-3 text-xs font-semibold leading-none bg-blue-500 hover:bg-blue-600 text-white rounded" href="/#access">アクセス</a>
            </div>
        </nav>
    </div>
</header>
<!-- ▲▲▲▲共通ヘッダー▲▲▲▲　-->

<!-- ▼▼▼▼ページ毎の個別内容▼▼▼▼　-->
<main>
@yield('content')
</main>
<!-- ▲▲▲▲ページ毎の個別内容▲▲▲▲　-->

<!-- ▼▼▼▼共通フッター▼▼▼▼　-->
<footer class="bg-black">
    <div class="px-4 container mx-auto p-10 flex justify-between">
        <div class="text-white text-left">
            <h2 class="text-xl font-semibold">ねこカフェららべる</h2>
            <p>〒123-4567</p>
            <p>東京都墨田区押上1-2-3 Illuminateビル9F</p>
        </div>

        <ul class="text-white text-left hidden md:flex flex-wrap flex-col h-12 md:w-128">
            <li class="ml-6"><a href="/" class="hover:underline">ホーム</a></li>
            <li class="ml-6"><a href="#" class="hover:underline">設備</a></li>
            <li class="ml-6"><a href="#" class="hover:underline">ねこちゃんたち</a></li>
            <li class="ml-6"><a href="/blogs" class="hover:underline">ブログ</a></li>
            <li class="ml-6"><a href="/#access" class="hover:underline">アクセス</a></li>
            <li class="ml-6"><a href="#" class="hover:underline">よくあるご質問</a></li>
            <li class="ml-6"><a href="/contact" class="hover:underline">お問い合わせ</a></li>
            <li class="ml-6"><a href="#" class="hover:underline">プライバシーポリシー</a></li>
        </ul>
    </div>
</footer>
<!-- ▲▲▲▲共通フッター▲▲▲▲　-->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>

