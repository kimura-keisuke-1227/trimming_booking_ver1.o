@extends('layouts.admin')

@section('title' , 'サロン設定修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
            <form action="{{Route('admin.salon.edit',['salon_id' => $salon -> id])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">サロン情報</h3>
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
                        <label class="block text-sm font-medium mb-2" for="name">店舗名</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->salon_name }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">都道府県</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->prefecture }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">住所1</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->address1 }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">住所2</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->address2 }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">電話番号</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->phone }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">開店時間</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">閉店時間</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">メールアドレス(予約通知用)</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="name" value="{{ $salon->email }}">
                    </div>
                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">変更</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('footer')
@endsection