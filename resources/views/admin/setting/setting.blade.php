@extends('layouts.admin')

@section('title', '設定一覧')

@section('content')

<div class="container">
    <form action="{{Route('admin.checkBookings.dateAndSalon')}}">
        <tale class="table table-striped"></tale>
        @foreach($settings as $setting)
            <tr>
                <td>$setting->explain</td>
            </tr>
        @endforeach
        <input type="submit" name="" id="" value="設定変更">
    </form>
 
</div>

@endsection

@section('footer')
@endsection