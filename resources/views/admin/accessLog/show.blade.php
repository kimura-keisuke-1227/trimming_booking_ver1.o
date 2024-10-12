@extends('layouts.admin')

@section('title' , 'ペット情報修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">アクセスログ詳細</h3>
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
                        <label class="block text-sm font-medium mb-2" for="id">id</label>
                        <p>{{$AccessLog->id}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="summary">操作者情報</label>
                        <p>{{$AccessLog->user_info}}</p>
                        <td>{{ optional($AccessLog->user)->email ?? 'ユーザー情報なし' }}</td>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="summary">概要</label>
                        <p>{{$AccessLog->summary}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="summary">詳細</label>
                        <p>{{$AccessLog->detail}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="summary">リクエストデータ</label>
                        <p>{{$AccessLog->request}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="summary">メソッド</label>
                        <p>{{$AccessLog->method}}</p>
                    </div>
                </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
@endsection
