@extends('layouts.admin')

@section('title', '臨時枠調整一覧')

@section('content')

<div class="container">
    <table class="table table-striped">
        <tr>
            <th>店舗名</th>
            <th>開始日付</th>
            <th>開始時間</th>
            <th>終了日付</th>
            <th>終了時間</th>
            <th>枠数</th>
        </tr>
        @foreach($tempcapacities as $tempcapacity)
        <tr>
            <td>{{$tempcapacity -> salon -> salon_name}}</td>
            <td>{{$tempcapacity -> st_date}}</td>
            <td>{{$tempcapacity -> getStTime()}}</td>
            <td>{{$tempcapacity -> ed_date}}</td>
            <td>{{$tempcapacity -> getEdTime()}}</td>
            <td>{{$tempcapacity -> capacity}}</td>
        </tr>
        @endforeach

    </table>
    <a href="{{ Route('admin.newtempcapacitycreate') }}">新規臨時枠数登録</a>
</div>

@endsection

@section('footer')
@endsection