@extends('layouts.admin')

@section('title','休日設定一覧')

@section('content')

<div class="container">
<label class="block text-sm font-medium mb-2" for="salon">{{ $salon->salon_name }}</label>
        <div class="mb-6 ">
            <form action="{{Route('admin.holiday.store', ['salon_id' => $salon_id ])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">単一日休日登録</h3>
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
                        <label class="block text-sm font-medium mb-2" for="single_date">店休日</label>
                        <input id="single_date" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="single_date" min="2000-01-01" max="2100-12-31" >
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="single_comment">コメント</label>
                        <input id="single_comment" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="single_comment">
                    </div>
                    <div class="ml-auto">
                        <button name="single_holiday" type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md"onclick="return confirm('登録します。よろしいですか？')">登録</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mb-6 ">
            <form action="{{Route('admin.holiday.store', ['salon_id' => $salon_id ])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">複数休日登録</h3>
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
                        <label class="block text-sm font-medium mb-2" for="st_date">開始日</label>
                        <input id="st_date" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="st_date" min="2000-01-01" max="2100-12-31" >
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="ed_date">終了日</label>
                        <input id="ed_date" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="ed_date" min="2000-01-01" max="2100-12-31" >
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="day_of_week">曜日</label>
                        <div class="flex">
                            <select id="day_of_week" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="day_of_week">
                                
                                <option value=1>月曜日</option>
                                <option value=2>火曜日</option>
                                <option value=3>水曜日</option>
                                <option value=4>木曜日</option>
                                <option value=5>金曜日</option>
                                <option value=6>土曜日</option>
                                <option value=7>日曜日</option>
                                <option value=999>すべての日</option>
                                
                            </select>
                            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="single_comment">コメント</label>
                        <input id="single_comment" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="single_comment">
                    </div>
                    <div class="ml-auto">
                        <button name="multiple_holidays" type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md" onclick="return confirm('登録します。よろしいですか？')">登録</button>
                    </div>
                </div>
            </form>
        </div>
</div>
@endsection

@section('footer')
@endsection