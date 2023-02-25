@extends('layouts.user')

@section('title' , 'サロン設定修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
            <form action="{{Route('admin.salon.edit',['salon_id' => 1])}}" method="POST" enctype="multipart/form-data">
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
                        <p>{{$salon->salon_name}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">犬種(変更不可)</label>
                        <div class="flex">
                            <p></p>

                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="weight">体重(kg)</label>
                        <p>aaa</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">誕生日</label>
                        <p>aaa</p>
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