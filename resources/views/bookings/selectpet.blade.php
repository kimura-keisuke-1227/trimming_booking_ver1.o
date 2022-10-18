@extends('layouts.user')

@section('title' , 'ペット選択')

@section('content')

<p>{{$owner -> getFullName()}}様。いつもご利用ありがとうございます。</p>

<form action="{{Route('booking.selectCourse')}}" method="post">
    @csrf
    <div class="mb-6">
        <label class="block text-sm font-medium mb-2" for="category">◆予約するペットを選択してください。<br>(ペットが登録されていない場合は先に登録してください。)</label>
        <div class="flex">
            <select id="pet" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="pet">
                @foreach($pets as $pet)
                <option value="{{$pet -> id}}">{{$pet -> getData()}}</option>
                @endforeach
            </select>
            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                </svg>
            </div>
        </div>
    </div>

    <br><br>
    <div class="ml-auto">
        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">コース選択へ</button>
    </div>

</form>

@endsection

@section('footer')
@endsection