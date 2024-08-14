@extends('layouts.admin')

@section('content')
<div class="container">
        <table class="table table-striped">
            <tr>
                <th>犬種</th>
                <th>有効・無効</th>
                <th>表示切替</th>
            </tr>
            @foreach ($dogtypes as $dogtype)
            <tr>
                <td>{{$dogtype->type}}</td>
                <td>@if ($dogtype->flg_show)
                    有効
                @else
                    ×
                @endif</td>
                <td>@if ($dogtype->flg_show)
                    <a href="">[無効化]</a>
                @else
                    <a href="">[有効化]</a>
                @endif</td>
            </tr>
            @endforeach
    </form>

</div>
@endsection

@section('footer')
@endsection