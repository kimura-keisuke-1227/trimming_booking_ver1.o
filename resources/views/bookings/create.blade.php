@extends('layouts.user')

@section('title' , '予約登録')

@section('content')
<div class="container">
    <form method="POST" action="/owners">
        @csrf
        <input type="integer" name="pet_id">
        <input type="integer" name="course">
        <input type="text" name="date">
        <input type="submit">
    </form>
</div>
@endsection

@section('footer')
@endsection