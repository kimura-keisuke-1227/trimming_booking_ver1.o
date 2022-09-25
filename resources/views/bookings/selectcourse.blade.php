@extends('layouts.user')

@section('title' , 'コース選択')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">
        
            <p>こんにちは、{{$owner -> name}}さん</p>
            <br>
            <p>予約対象</p>
            <p>{{$pet -> getData()}}</p>
            <div class="container">
                <form action="{{Route('booking.selectCalender')}}" method="post">
                    @csrf
                    <label for="salon">◆店舗を選択してください。</label><br>
                    <select name="salon">
                        @foreach($salons as $salon)
                        <option value="{{$salon -> id}}" 
                        @if( $salon->id == $owner-> default_salon)
                        selected
                        @endif
                        >{{$salon -> salon_name}}</option>
                        @endforeach
                    </select>
                    <br><br>
                    <label for="salon">◆コースを選択してください。</label><br>
                    <select name="course">
                        @foreach($courses as $course)
                        <option value="{{$course -> id}}">{{$course -> getCourseInfo()}}</option>
                        @endforeach
                    </select>
                    <br>
                    <br>
                    
                    <input type="submit" method="POST" value="日程選択へ">
                
                
                </form>
            
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
@endsection