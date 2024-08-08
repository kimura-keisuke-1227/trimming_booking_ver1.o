@extends('layouts.admin')

@section('title','休日設定一覧')

@section('content')

<div class="container">
<label class="block text-sm font-medium mb-2" for="salon">店舗を選択してください。</label>
        <div class="mb-6 ">
            <label class="block text-sm font-medium mb-2" for="salon">店舗を選択してください。</label>
            <div class="flex">
                <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="salon">
                    
                        <option >
                            aaa
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


    <a href="{{route('admin.holiday.create' , ['salon_id' => $salon_id ])}}">休日データの追加</a>

</div>
@endsection

@section('footer')
@endsection