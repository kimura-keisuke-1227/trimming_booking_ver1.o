
@extends('layouts.admin')

@section('title' , 'カルテ作成')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">

            <p>{{$owner -> getFullName()}}様。いつもご利用ありがとうございます。</p>
            <br>
            <p>予約対象</p>
            <p>{{$pet -> getData()}}</p>
            <div class="container">
                <form action="{{Route('booking.selectCalender')}}" method="post">
                    @csrf
                    <div class="mb-6 @if (count($salons) <= 1)not_show_one_salon @endif">
                        <label class="block text-sm font-medium mb-2" for="salon">店舗を選択してください。</label>
                        <div class="flex">
                            <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="salon">
                                @foreach($salons as $salon)
                                <option value="{{$salon -> id}}" @if( $salon->id == $owner-> default_salon)
                                    selected
                                    @endif
                                    >{{$salon -> salon_name}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none transform -translate-x-full flex items-center px-2 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <br><br>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="course">◆コースを選択してください。</label>
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
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">メッセージ</label>
                        <p>持病・ワクチン・複数頭のご予約、その他ご要望や連絡事項がございましたらご記入ください。</p>
                        <p>ミックス犬の場合は犬種をご記入ください。</p>
                        <textarea id="message" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="message" rows="5">{{$message_before}}</textarea>
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