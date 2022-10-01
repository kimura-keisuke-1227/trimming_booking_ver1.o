@extends('layouts.admin')

@section('title', '設定一覧')

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
                        <input type="number" name="{{$setting -> setting_name}}" value="{{$setting -> setting_int}}">
                    @else
                        <input type="text" name="{{$setting -> setting_name}}" value="{{$setting -> setting_str}}">
                    @endif
                </td>
            </tr>
        @endforeach

        </table>
        <input type="submit" name="" id="" value="設定変更">
    </form>
 
</div>

@endsection

@section('footer')
@endsection