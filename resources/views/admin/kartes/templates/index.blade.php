@extends('layouts.admin')

@section('title','カルテ用テンプレート一覧')

@section('content')

<div class="container">
    <table class="table table-striped pc_only">
        <tr>
            <th>テンプレート名</th>
        </tr>

        @foreach($karteFormats as $karteFormat)
        <tr>
            <td><a href="{{Route('admin.karte.template.edit',['karteFormat' => $karteFormat])}}">{{$karteFormat-> title}}</a></td>
        </tr>
        @endforeach


    </table>


</div>
@endsection

@section('footer')
@endsection