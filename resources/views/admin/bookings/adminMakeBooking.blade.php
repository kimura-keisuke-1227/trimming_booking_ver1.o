@extends('layouts.admin')

@section('title', '予約登録')

@section('content')

<div class="container">

    <form action="" method="post">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="pet"> ◆ペット(選択)</label>
            <div class="flex">
                <select id="dogtype" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="pet">
                    @foreach($pets as $pet)
                    <option value="{{$pet -> id}}">{{$pet -> getDataWithOwner()}}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <br>
        <br>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="salon"> ◆店舗(選択)</label>
            <div class="flex">
                <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="salon">
                    @foreach($salons as $salon)
                    <option value="{{$salon -> id}}">{{$salon -> salon_name}}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <br>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="course"> ◆コース(選択)</label>
            <div class="flex">
                <select id="course" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="course">
                    @foreach($courses as $course)
                    <option value="{{$course -> id}}">{{$course -> getCourseInfoForAdminBookingMaking()}}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <br>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="date">日付</label>
            <input id="date" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="date" value="{{ old('birthday') }}">
        </div>
        <br>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="st_hour">開始時間（入力）</label>
            <input id="st_hour" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="st_hour" value="{{ old('name') }}">時
            <input id="st_minute" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="st_minute" value="{{ old('name') }}">分
        </div>
        <br>
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="ed_hour">終了時間（入力）</label>
            <input id="ed_hour" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="ed_hour" value="{{ old('name') }}">時
            <input id="ed_minute" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="ed_minute" value="{{ old('name') }}">分
        </div>
        <br>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="price">金額（入力）</label>
            <input id="price" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="price" value="{{ old('name') }}">円
        </div>
        <br>
        <br>
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
        </div>
    </form>
</div>

@endsection

@section('footer')
@endsection