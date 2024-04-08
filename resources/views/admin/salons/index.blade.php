@extends('layouts.admin')

@section('title','サロン一覧')

@section('content')

<div class="container">
    <table class="table table-striped pc_only">
        <tr>
            <th>サロン名</th>
            <th>設定変更</th>
        </tr>
        @foreach($salons as $salon)
        <tr>
            <td>{{$salon -> salon_name}}</td>
            <td><a href="{{ Route('admin.salon.edit',['salon_id' => $salon])}}">[設定]</a></td>
            {{-- 
                
                --}}
        </tr>
        @endforeach

    </table>

    <table class="table table-striped sp_only">
        <tr>
            <th>サロン一覧</th>
        </tr>
        @foreach($salons as $salon)
        <tr>
            {{-- 
                <td><a href="{{ Route('admin.salon.edit',['salon_id' => $salon])}}">{{$salon -> salon_name}}</a></td>
            --}}
            <td><p>{{$salon -> salon_name}}</p></td>

        </tr>
        @endforeach
    </table>
    <a href="{{ Route('admin.salon.create')}}">サロン登録へ</a>

</div>
@endsection

@section('footer')
@endsection