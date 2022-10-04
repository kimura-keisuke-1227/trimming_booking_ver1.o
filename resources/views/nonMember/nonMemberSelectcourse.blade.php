@extends('layouts.user')

@section('title' , 'コース選択')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">

            <br>
            <p>予約対象</p>
            <div class="container">
                <form action="{{Route('nonMember.booking.selectCalender')}}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="salon">店舗を選択してください。</label>
                        <div class="flex">
                            <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="salon">
                                @foreach($salons as $salon)
                                <option value="{{$salon -> id}}">{{$salon -> salon_name}}</option>
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

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="category">コース</label>
                        <div class="flex">
                            <select id="course" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="course">
                                @foreach($courses as $course)
                                <option value="{{$course -> id}}">{{$course -> getCourseInfo()}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">日程選択へ</button>
                    </div>


                </form>

            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
@endsection