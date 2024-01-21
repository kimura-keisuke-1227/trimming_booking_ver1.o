@extends('layouts.default')

@section('title' , '会員登録')

@section('content')
<form method="GET" action="/owners">
    @csrf
    <input type="text" name="text">
    <input type="submit">
</form>
    
</table>
@endsection

@section('footer')
@endsection