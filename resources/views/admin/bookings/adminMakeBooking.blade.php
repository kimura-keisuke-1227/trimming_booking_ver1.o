@extends('layouts.admin')

@section('title', '予約登録')

@section('content')

<div class="container">

    <form action="" method="post">
        @csrf
        <label for="pet">
            ◆ペット(選択)
            <br>
            <select name="pet" id="">
                @foreach($pets as $pet)
                <option value="{{$pet -> id}}">{{$pet -> getDataWithOwner()}}</option>
                @endforeach
            </select>
        </label>
        <br>
        <br>

        <label for="salon">
            ◆店舗(選択)
            <br>
            <select name="salon" id="">
                @foreach($salons as $salon)
                <option value="{{$salon -> id}}">{{$salon -> salon_name}}</option>
                @endforeach
            </select>
        </label>
        <br>

        <label for="course">
            ◆コース(選択)
            <br>
            <select name="course" id="">
                @foreach($courses as $course)
                <option value="{{$course -> id}}">{{$course -> getCourseInfoForAdminBookingMaking()}}</option>
                @endforeach
            </select>
        </label>
        <br>

        <label for="date">
            <input name="date" type="date" value={{$today}}>
        </label>
        <br>

        <label for="st_time">
            ◆開始時間(入力)
            <br>
            <input name='st_hour' type="number" value="9">時
            <input name='st_minute' type="number" value="0">分
        </label>
        <br>
        <label for="ed_time">
            ◆終了時間(入力)
            <br>
            <input name='ed_hour' type="number" value="11">時
            <input name='ed_minute' type="number" value="00">分
        </label>
        <br>

        <label for="price">
            ◆金額(入力)
            <br>
            <input name='price' type="number" value="0">
        </label>
        <br>
        <br>
        <input type="submit" value="登録">
    </form>
</div>

@endsection

@section('footer')
@endsection