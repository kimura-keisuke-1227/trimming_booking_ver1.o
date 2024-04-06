@extends('layouts.admin')

@section('title' , 'お知らせ登録')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
            <form action="{{Route('notification.store',[])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">お知らせ</h3>
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
                        <label class="block text-sm font-medium mb-2" for="name">お知らせ内容</label>
                        <textarea id="prefecture"  rows="5" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="textarea" name="contents" value="">
</textarea> 
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">表示開始日時</label>
                        <input id="address1" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="datetime-local" name="st_date" value="{{$st_date}}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">表示終了日時</label>
                        <input id="address2" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="datetime-local" name="ed_date" value="{{$ed_date}}">
                    </div>
                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">追加</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('footer')
@endsection