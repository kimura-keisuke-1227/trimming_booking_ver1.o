@extends('layouts.guest')

@section('title' , '予約者情報登録')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
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
            <form action="{{Route('nonMember.beginBookingEntry')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">飼い主様情報</h3>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">姓</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="owner_last_name" value="{{ old('name') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">名</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="owner_first_name" value="{{ old('name') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">姓（カナ）</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="owner_last_name_kana" value="{{ old('name') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">名(カナ)</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="owner_first_name_kana" value="{{ old('name') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">メールアドレス</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="mail" value="{{ old('name') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2" for="name">電話番号</label>
                    <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="phone" value="{{ old('name') }}">
                </div>

                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">ペット情報</h3>
                </div>

                <div class="pt-4 px-6">


                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">犬種を選択してください</label>
                        <div class="flex">
                            <select id="dogtype" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="dogtype">
                                @foreach($dogtypes as $dogtype)
                                <option value="{{$dogtype -> id}}">{{$dogtype -> type}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">名前</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="pet_name" value="{{ old('name') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="weight">体重(kg)</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" step="0.1" name="weight" value="{{ old('weight') }}">
                    </div>

                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">コースの選択へ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- <form method="POST" action="{{route('pets.store')}}">
    @csrf
    <input type="integer" name="owner_id">
    <input type="text" name="pet_name">
    <select name="dogtype" id="">
        @foreach($dogtypes as $dogtype)
        <option value="{{$dogtype -> id}}">{{$dogtype -> type}}</option>    
        @endforeach
    </select>
    <input type="submit">
</form> -->

@endsection

@section('footer')
@endsection