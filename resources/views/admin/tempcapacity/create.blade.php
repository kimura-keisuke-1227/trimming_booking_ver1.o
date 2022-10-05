@extends('layouts.admin')

@section('title', '臨時枠調整一覧')

@section('content')

<div class="container">
    <form action="" method="post">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>店舗選択</th>
                <td>
                    <select name="salon" id="">
                        @foreach($salons as $salon)
                        <option value="{{$salon -> id}}">{{$salon -> salon_name}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>日付</th>
                <td><input type="date" name="st_date" value="{{date('Y-m-d')}}"></td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td><input type="number" name="ed_hour" value="10">時<input type="number" name="ed_minute" value="0">分</td>
            </tr>

            <tr>
                <th>終了時間</th>
                <td><input type="number" name="ed_hour" value="20">時<input type="number" name="ed_minute" value=0>分</td>
            </tr>
            <tr>
                <th>枠数</th>
                <td><input type="number" name="capacity" value=0></td>
            </tr>
        </table>
        <input type="submit" value="登録">
    </form>
    <a href="/admin/newtempcapacitycreate">新規臨時枠数登録</a>
</div>

@endsection

@section('footer')
@endsection