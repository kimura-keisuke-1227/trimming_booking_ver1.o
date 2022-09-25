@extends('layouts.user')

@section('title' , 'ペット選択')

@section('content')

<div class="container">
    <p>こんにちは、{{$owner -> name}}さん</p>
    
    <form action="{{Route('booking.selectCourse')}}" method="post">
        @csrf
        <label for="pet">◆予約するペットを選択してください。</label><br>
        <select name="pet" id="">
            @foreach($pets as $pet)
                <option value="{{$pet -> id}}">{{$pet -> getData()}}</option>
            @endforeach        
        </select>
        <br><br>
        <input type="submit" value="コース選択へ">
    
    </form>
    
    <br>

</div>
@endsection

@section('footer')
@endsection