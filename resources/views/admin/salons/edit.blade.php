@extends('layouts.admin')

@section('title' , 'サロン設定修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
            <form action="{{Route('admin.salon.update',['salon_id' => $salon -> id])}}" method="POST" enctype="multipart/form-data">
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
                        <input id="salon_name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="salon_name" value="{{ $salon->salon_name }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">都道府県</label>
                        <input id="prefecture" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="prefecture" value="{{ $salon->prefecture }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">住所1</label>
                        <input id="address1" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="address1" value="{{ $salon->address1 }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">住所2</label>
                        <input id="address2" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="address2" value="{{ $salon->address2 }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">電話番号</label>
                        <input id="phone" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="phone" value="{{ $salon->phone }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">メールアドレス(予約通知用)</label>
                        <input id="email" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="email" value="{{ $salon->email }}">
                    </div>
                    {{-- 
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2" for="name">開店時間(3または4桁で入力　9時なら900)</label>
                            <input id="open_h" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="st_time" value="{{$open}}">
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium mb-2" for="name">閉店時間(3または4桁で入力　18時なら1800)</label>
                            <input id="close_h" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="ed_time" value="{{$close}}">
                        </div>
                    --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="salon">全コース予約枠自動クローズ</label>
                        <div class="flex">
                            <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="is_close_all_courses">
                                <option value="0">
                                    予約コースのみ
                                </option>
                                <option value="1" 
                                @if ($salon->is_close_all_courses)
                                    selected
                                @endif>
                                    全コース
                                </option>
                            </select>
                            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <br><br>
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