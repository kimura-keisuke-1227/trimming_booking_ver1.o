@extends('layouts.admin')

@section('title', '全体設定一覧')

@section('content')

<div class="container">
    <form action="{{Route('admin.checkBookings.dateAndSalon')}}">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>設定内容</th>
                <th>設定値</th>
            </tr>
            @foreach($settings as $setting)
            <tr>
                <td>{{$setting->explain}}</td>
                <td>
                    @if($setting -> isNumber)
                    <input id="{{$setting->id}}" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="weight" value="{{$setting->setting_int}}">
                    @else
                    <input id="{{$setting->id}}" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="weight" value="{{$setting->setting_string}}">
                    @endif
                </td>
            </tr>
            @endforeach

        </table>
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">設定保存</button>
        </div>
    </form>

</div>

@endsection

@section('footer')
@endsection