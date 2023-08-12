@extends('layouts.admin')

@section('title','お知らせ一覧')

@section('content')

<div class="container">
    <a href="">[新規お知らせ登録]</a>
    <table class="table table-striped pc_only">
        <tr>
            <th>id</th>
            <th>内容</th>
            <th>表示ページ</th>
            <th>表示開始</th>
            <th>表示終了</th>
            <th>変更</th>
            <th>削除</th>
        </tr>
        @foreach($notifications as $notification)
        <tr>
            <td>{{$notification -> id}}</td>
            <td>{{$notification -> contents}}</td>
            <td>{{$notification -> page}}</td>
            <td>{{$notification -> start_time}}</td>
            <td>{{$notification -> end_time}}</td>
            <td><a href="">変更</a></td>
            <td><a href="">削除</a></td>
            {{-- 
                <!-- <td><a href="{{ Route('admin.salon.edit',['salon_id' => $salon])}}">[設定]</a></td> -->
                
                --}}
        </tr>
        @endforeach

    </table>
</div>
@endsection

@section('footer')
@endsection