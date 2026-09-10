@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">食材管理・設定</h2>
        <div>
            <h3>食材設定</h3>
            <p>以下の食材の設定を行ってください</p>
        </div>
        <div>
            <h3>食材リスト</h3>
            <table>
                <tr>
                    <th>食材カテゴリー</th>
                    <th>食材名</th>
                    <th>アレルギー</th>
                    <th>アレルギーカテゴリー</th>
                </tr>
                <tr>
                    <td rowspan=""></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection