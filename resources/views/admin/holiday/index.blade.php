@extends('layouts.admin')

@section('title','休日設定一覧')

@section('content')

<div class="container">
        <div class="py-4 bg-white rounded">
        <table class="table table-striped pc_only">

        @foreach($salons as $salon)
        <tr>
            <td>
                @if ($salon->id == $salon_id)
                    【選択中】
                @endif    
            {{$salon -> salon_name}}</td>
            <td><a href="{{Route('admin.holiday',['salon_id' => $salon->id])}}">[設定を開く]</a></td>
        </tr>
        @endforeach

    </table>

    </div>
        <table class="table table-striped pc_only">
        <tr>
            <!-- <th>id</th> -->
            <th>日付</th>
            <th>コメント</th>
            <th>削除</th>
        </tr>
        @foreach($holidays as $holiday)
        <tr>
            <td>{{$holiday -> date_str()}}</td>
            <td>{{$holiday -> comment}}</td>
            <td>
                <a href="{{ route('admin.holiday.destroy', ['holiday' => $holiday]) }}" 
                onclick="return confirm('削除しますか？')">[削除]</a>
            </td>

        </tr>
        @endforeach

    </table>

    <a href="{{route('admin.holiday.create' , ['salon_id' => $salon_id ])}}">休日データの追加</a>
</div>
@endsection

@section('footer')
@endsection