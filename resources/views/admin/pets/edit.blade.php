@extends('layouts.admin')

@section('title' , 'ペット情報修正')

@section('content')
<section class="py-8">
    <div class="container px-4 mx-auto">
        <div class="py-4 bg-white rounded">
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
                        <label class="block text-sm font-medium mb-2" for="name">名前</label>
                        <p>{{$pet->name}}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">犬種</label>
                        <div class="flex">
                            <p>{{$pet->dogtype->type}}</p>

                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="weight">体重　{{ $pet->weight }}(kg)</label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2" for="name">誕生日　{{ $pet->birthday }}</label>
                    </div>
                </div>
            <br>
            <div class="pt-4 px-6" style="border:1px">
                <h4>施術履歴（カルテ）</h4>
                <table class="table_karte">
                    <tr>
                        <th>日付</th>
                    </tr>
                    @foreach ($kartes as $karte)
                        <tr>
                            <td>
                                    <a href="{{Route('admin.karte.show',['karte' => $karte])}}">{{$karte->karte_date()}}</a>
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