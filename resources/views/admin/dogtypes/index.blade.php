@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>犬種一覧</h2>
    <form action="{{Route('admin.dogtype.index')}}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>犬種</th>
                {{-- 
                    <th>表示順</th>
                     --}}
                <th>有効・無効</th>
                <th></th>
            </tr>
            @foreach ($dogtypes as $dogtype)
            <tr>
                <td>{{$dogtype->type}}</td>
                {{-- 
                    <td>{{$dogtype->order}}</td>
                     --}}
                <td>@if ($dogtype->flg_show)
                    有効
                @else
                    ×
                @endif</td>
                <td>
                    <a href="{{Route('admin.dogtype.switch_flg_show',$dogtype->id)}}">
                        @if ($dogtype->flg_show)
                            [無効にする]
                        @else
                            [有効化]
                        @endif
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </form>
    <a href="{{Route('admin.dogtype.create')}}">[犬種登録]</a>
</div>
@endsection

@section('footer')
@endsection