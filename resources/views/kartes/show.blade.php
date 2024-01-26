
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
                        <p>{{$karte->karte_date()}}</p>
                    </div>
                    <div class="mb-6">
                        <textarea readonly name="" id="" cols="30" rows="10">{{ $karte->karte_for_owner }}</textarea>
                    </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('footer')
@endsection