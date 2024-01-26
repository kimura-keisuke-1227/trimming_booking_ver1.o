@extends('layouts.admin')

@section('title' , 'ペット情報修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
            <form action="{{Route('pets.update',['pet' => $pet])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex px-6 pb-4 border-b">
                    <h3 class="text-xl font-bold">ペット情報</h3>
                </div>

                <div class="pt-4 px-6">
                    <!-- ▼▼▼▼エラーメッセージ▼▼▼▼　-->
                    @if($errors->any())
                    <div class="mb-8 py-4 px-6 border border-red-300 bg-red-50 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li class="text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <!-- ▲▲▲▲エラーメッセージ▲▲▲▲　-->


                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">名前(変更不可)</label>
                        <p>{{$pet->name}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">犬種(変更不可)</label>
                        <div class="flex">
                            <p>{{$pet->dogtype->type}}</p>

                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="weight">体重(kg)</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="number" step="0.1" name="weight" value="{{ $pet->weight }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">誕生日</label>
                        <input id="name" class="block w-full px-4 py-3 mb-2 text-sm bg-white border rounded" type="date" name="birthday" value="{{ $pet->birthday }}">
                    </div>

                    <div class="ml-auto">
                        <button type="submit" class="py-2 px-3 text-xs text-white font-semibold bg-indigo-500 rounded-md">変更</button>
                    </div>
                </div>
            </form>
            <br>
            <div class="pt-4 px-6" style="border:1px">
                <h4>施術履歴（カルテ）aaa</h4>
                <table class="table_karte">
                    <tr>
                        <th>日付</th>
                    </tr>
                    @foreach ($kartes as $karte)
                        <tr>
                            <td>
                                <a href="{{Route('admin.karte.show',['karte' => $karte])}}">{{$karte->date}}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
@endsection