
@extends('layouts.admin')

@section('title' , 'カルテ作成')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">
            <div class="container">
                <form action="{{Route('booking.selectCalender')}}" method="post">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">カルテ記載</label>
                        <textarea id="message" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="message" rows="5"></textarea>
                    </div>

                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
@endsection