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
                <th>開始日付</th>
                <td><input type="date" name="st_date"></td>
            </tr>
            <tr>
                <th>開始時間</th>
                <td><input type="number" name="st_time"></td>
            </tr>
            <tr>
                <th>終了日付</th>
                <td><input type="date" name="ed_date"></td>
            </tr>
            <tr>
                <th>終了時間</th>
                <td><input type="number" name="ed_time"></td>
            </tr>
            <tr>
                <th>枠数</th>
                <td><input type="number" name="capacity"></td>
            </tr>

               
        </table>
        <input type="submit" value="登録">
    </form>
    <a href="/admin/newtempcapacitycreate">新規臨時枠数登録</a>
</div>

@endsection

@section('footer')
@endsection