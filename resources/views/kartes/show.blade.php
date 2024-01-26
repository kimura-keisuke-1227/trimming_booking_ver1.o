
@extends('layouts.user')

@section('title' , 'カルテ明細')

@section('content')
<section>
    <div class="container">

        <div class="pt-4 px-4 overflow-x-auto">
            <div class="container">
                    <div class="mb-6">
                        <p>{{$karte->pet->getPetInfoForAdminMobile()}}</p>
                    </div>
                    <div class="mb-6">
                        <p>{$karte->karte_date()}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="message">カルテ</label>
                        <p>{{ $karte->karte_for_owner }}</p>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
@endsection