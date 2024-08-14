@extends('layouts.admin')

@section('content')
<div class="container">
    <form action="{{Route('admin.course.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="table table-striped">
            <tr>
                <th>犬種</th>
                <th>有効・無効</th>
                <th></th>
            </tr>
            @foreach ($dogtypes as $dogtype)
            <tr>
                <td>{{$dogtype->type}}</td>
                <td><a href="">[無効化]</a></td>
                <td><a href="">[無効化]</a></td>
            </tr>
            @endforeach
        </table>
    </form>

</div>
@endsection

@section('footer')
@endsection