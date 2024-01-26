
@extends('layouts.admin')

@section('title' , 'カルテ編集')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">
            <div class="container">
                <form action="{{Route('admin.karte.update',['karte' => $karte])}}" method="post">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">飼い主</label>
                        <p>{{$karte->pet->owner()}}様</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">ペット</label>
                        <p>{{$karte->pet->getPetInfoForAdminMobile()}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">日付</label>
                        <p>{{$karte_data}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">カルテ(お客様向け)</label>
                        <textarea id="message" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="karte_for_owner" rows="20">{{ $karte->karte_for_owner }}</textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">カルテ記載(スタッフのみ)</label>
                        <textarea id="message" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" name="karte_for_staff" rows="20">{{ $karte->karte_for_staff }}</textarea>
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