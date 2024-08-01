@extends('layouts.admin')

@section('title','休日設定一覧')

@section('content')

<div class="container">
    <table class="table table-striped pc_only">
        <tr>
            <!-- <th>id</th> -->
            <th>開始日</th>
            <th>終了日</th>
            <th>曜日</th>
        </tr>
        @foreach($regularHolidays as $regularHoliday)
        <tr>
            <!-- <td>{{$regularHoliday -> dayOfWeek}}</td> -->
            <td>{{$regularHoliday -> st_date}}</td>
            <td>{{$regularHoliday -> st_date}}</td>
            <td>
                        @php
                            $week = array( "日", "月", "火", "水", "木", "金", "土" );
                            $dateStr = $week[$regularHoliday -> dayOfWeek] . '曜日';
                            echo $dateStr;
                        @endphp
            </td>
            {{-- 
                <td><a href="{{ Route('admin.salon.edit',['salon_id' => $salon])}}">[設定]</a></td>
                
                --}}
        </tr>
        @endforeach

    </table>

    <table class="table table-striped sp_only">
        <tr>
            <th>サロン一覧</th>
        </tr>
        @foreach($regularHolidays as $salon)
        <tr>
            {{-- 
                <td><a href="{{ Route('admin.salon.edit',['salon_id' => $salon])}}">{{$salon -> salon_name}}</a></td>
                <td><p>{{$salon -> salon_name}}</p></td>
            --}}

        </tr>
        @endforeach
    </table>

    {{-- 
        <a href="{{ Route('admin.salon.create')}}">サロン登録へ</a>
    --}}

</div>
@endsection

@section('footer')
@endsection