@extends('layouts.admin')

@section('title', '予約一覧')

@section('content')

<div class="container">
    <form action="{{Route('admin.checkBookings.dateAndSalon')}}">

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="salon">店舗を選択してください</label>
            <div class="flex">
                <select id="salon" class="appearance-none block pl-4 pr-8 py-3 mb-2 text-sm bg-white border rounded" name="salon">
                    @foreach($salons as $salon)
                    <option value="{{$salon -> id}}"
                    @if ($salon->id==$selectedSalon->id)
                        selected
                    @endif
                    >{{$salon -> salon_name}} </option>
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

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2" for="date">日付</label>
            <input id="date" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="date" value="{{ $date }}">
        </div>
        <br>
        <br>
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">表示</button>
        </div>
    </form>
    <br>
    <br>

    <h3>{{$selectedSalon -> salon_name .' ' . $checkdate}}</h3>
    <table class="table table-striped pc_only">
        <tr>
            <th>店舗名</th>
            <th>日付</th>
            <th>開始時間</th>
            <th>終了時間</th>
            <th>終了時間<br>(お客様向け)</th>
            <th>飼い主</th>
            <th>ペット</th>
            <th>コース</th>
            {{-- 
                <th></th>
                <th></th>
                --}}
        </tr>
        @foreach($bookings as $booking)
        <tr>
            <td>{{$booking -> salon -> salon_name}}</td>
            <td>{{$booking -> getBookingDate()}}</td>
            <td>{{$booking -> getStartTime()}}</td>
            <td>{{$booking -> getEndTime()}}</td>
            <td>{{$booking -> getEndTimeForOwner()}}</td>
            @if($booking -> pet_id !== 0)
            <td><a href="{{Route('admin.showUserInfo',['userID'=>$booking -> pet -> user ->id])}}">
                @if($usersCameBeforeList[$booking -> pet -> user->id] == 0)
                    【初回来店】
                @else
                    【来店歴あり】
                @endif
                
                {{$booking -> pet -> user -> getUserInfo()}}
            </a> </td>
            <td><a href="{{Route('admin.pet.show',['pet_id'=>$booking->pet_id])}}">{{$booking -> getPetInfoForAdminMobile()}}</a></td>
            @else
            <td><a href="{{Route('admin.showNonMemberInfo',['bookingId'=>$booking->id])}}">【非会員】{{$booking -> getNonMemberOwner() }}</a></td>
            <td>{{$booking -> getPetNameOfNoMemberBooking() }}</td>
            @endif
            <td>{{$booking -> course -> courseMaster -> course}}</td>
            {{-- 
                <td><a href="{{Route('admin.showBookingDetail', ['bookingId' => $booking->id ])}}">詳細</td>
                
                --}}
            <td><a href="{{Route('admin.cancelConfirm', ['bookingId' => $booking->id ])}}">詳細・キャンセル</a></td>
            {{-- 
                <td><a href="{{Route('admin.karte.create', ['bookingID' => $booking->id ])}}">カルテ記入</a></td>
                <td><a href="{{Route('admin.karte.create' , ['bookingID' => $booking])}}">カルテ記入</a></td>
                --}}
        </tr>
        @endforeach

    </table>

    <table class="table table-striped sp_only">
        <tr>
            <th>予約情報</th>
        </tr>
        @foreach($bookings as $booking)
        <tr>
            <td>
                {{$booking -> date}} <br>
                {{$booking -> getStartTime()}}　〜
                {{$booking -> getEndTime()}}<br>
                (お客様表示終了時間)<br>
                {{$booking -> getEndTimeForOwner()}}<br>
                @if($booking -> pet_id !== 0)
                <a href="{{Route('admin.showUserInfo',['userID'=>$booking -> pet -> user ->id])}}">
                    @if($usersCameBeforeList[$booking -> pet -> user->id] == 0)
                        【初回来店】
                    @else
                        【来店歴あり】
                    @endif
                    {{$booking -> pet -> user -> getUserInfo()}}</a> <br>
                <a href="{{Route('admin.pet.show',['pet_id'=>$booking->pet_id])}}">{{$booking -> getPetInfoForAdminMobile()}}</a>
                <br>
                @else
                <a href="{{Route('admin.showNonMemberInfo',['bookingId'=>$booking->id])}}">【非会員】{{$booking -> getNonMemberOwner() }}</a><br>
                {{$booking -> getNonMemberPetName() }}<br>
                {{$booking -> getNonMemberPetInfo() }}<br>
                @endif
                {{$booking -> course -> courseMaster -> course}}<br>
                <a href="{{Route('admin.cancelConfirm', ['bookingId' => $booking->id ])}}">[詳細・キャンセル]</a> <br>
                <a href="{{Route('admin.cancelConfirm', ['bookingId' => $booking->id ])}}">カルテ記入</a> <br>

            </td>
        </tr>
        @endforeach
    </table>

    <br>
    <br>
    <section class="pc_only timetable">

        <h3>タイムテーブル</h3>
        <table class="table table-striped">
            @foreach($times as $time)
            <tr>
                <th class="fixed01">{{$time}}</th>
                @foreach($courses as $course)
                @foreach($bookings as $booking)
                @if($course->id == $booking->course->courseMaster->id)
                @if($booking->st_time == $timesNums[$time])
                @if($booking -> pet_id !== 0)
                <td class="bg_color{{$course->id}}">{{$booking->getBookingInfoForStaff()}}</td>
                @else
                <td class="bg_color{{$course->id}}">{{$booking -> getNonMemberPetForTable() }}</td>
                @endif
                @elseif($booking->st_time == $timesNums[$time]-$step_time)
                @if($booking -> pet_id !== 0)
                <td class="bg_color{{$course->id}}">{{$booking->getBookingCourseAndDogTypeInfoForStaff()}}</td>
                @else
                <td class="bg_color{{$course->id}}">{{$booking -> getDogTypeAndCourse() }}</td>
                @endif
                @elseif(($timesNums[$time]>$booking->st_time) and ($timesNums[$time]<$booking->ed_time))
                    <td class="bg_color{{$course->id}}">↓</td>
                    @else
                    <td></td>
                    @endif
                    @endif
                    @endforeach
                    @endforeach
            </tr>
            @endforeach
        </table>
    </section>
</div>

@endsection

@section('footer')
@endsection