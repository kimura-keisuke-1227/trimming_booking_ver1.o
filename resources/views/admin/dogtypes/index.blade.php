@extends('layouts.admin')

@section('content')
<div class="container">
    {{-- 
        <select name="course" id="">
            <option value="1">シャンプー</option>
            <option value="2">シャンプー・カット</option>
        </select>
        --}}
        <table class="table table-striped">
            <tr>
                <th>犬種</th>
            </tr>
            @foreach ($dogtypes as $dogtype)
            <tr>
                <td>{{$dogtype->type}}</td>
            </tr>
            @endforeach
        </table>

</div>
@endsection

@section('footer')
@endsection