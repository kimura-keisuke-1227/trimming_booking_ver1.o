@extends('layouts.admin')

@section('content')
<div class="container">
    <form action="{{Route('admin.dogtypes.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <td>新犬種<input id="new_dog_type" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="text" name="new_dog_type"></td>

        <table class="table table-striped">
            <tr>
                <th>コース</th>
                <th>所要時間(分)</th>
                <th>案内時間(分)</th>
            </tr>
        @foreach($courseMasters as $courseMaster)
        <tr>
            <td>
                {{$courseMaster->course}}
            </td>
            <td>
                <input id={{ $courseMaster->id}}" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="minute_{{ $courseMaster->id}}"  value="0" min="0">
            </td>
            <td>
                <input id={{ $courseMaster->id}}" class="block w-half px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" name="minute_for_show_{{ $courseMaster->id}}"  value="0" min="0">
            </td>
        </tr>
        @endforeach
        </table>
        <div class="ml-auto">
            <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">登録</button>
        </div>
    </form>
        <table class="table table-striped">
            <tr>
                <th>登録済み犬種</th>
            </tr>
            @foreach ($dogtypes as $dogtype)
            <tr>
                <td>{{$dogtype->type}}</td>
            </tr>
            @endforeach
        </table>

</div>
@endsection

@section('footer')
@endsection