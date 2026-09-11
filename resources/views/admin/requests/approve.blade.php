@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 p-4 md:px-6 md:py-10 flex flex-col items-center">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">申請認証チェック</h2>
        <div class="md:flex gap-10 w-full max-w-6xl">
            <div class="md:w-1/2">
                <img class="w-full md:w-xl h-102 rounded-2xl object-cover" src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}">
            </div>
            <div class="md:flex-1 mx-2 px-4">
                <h2 class="text-center text-2xl font-bold text-accent m-4">{{ $recipe->name }}</h2>
                <div class="flex justify-end items-center m-4">
                    <p>投稿者：</p>
                    <a class="flex items-center gap-4" href="{{ route('profile',['user_id' => $recipe->user_id]) }}">
                        <img class="block shrink-0 w-12 h-12 rounded-full object-cover" src="{{ $recipe->user->profile->profile_image_url }}" alt="ユーザーアイコン">
                        <p>{{ $recipe->user->name }}</p>
                    </a>
                </div>
                <div class="rounded-2xl bg-taupe-100 px-4 py-1">
                    <p class="my-3">{{ $recipe->description }}</p>
                </div>
                <div class="my-2 border-2 border-accent/20 px-4 py-2 rounded-2xl">
                    <span class="text-lg font-semibold">該当アレルギー</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($recipe->allergies as $allergy)
                        <span class="text-md font-semibold border rounded-full px-2 py-1 mx-1 text-olive-500 bg-white">{{ $allergy->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full md:w-3/4 md:flex mx-auto md:my-8 px-6 gap-12">
            <div class="my-6 md:w-1/3">
                <p class="text-lg font-semibold">材料({{ $recipe->servings }})</p>
                @foreach ($recipe->ingredients as $ingredient)
                <div class="flex justify-between my-1 py-1 border-b border-dashed px-2.5">
                    <p class="{{ $ingredients->contains('id', $ingredient->id) ? 'text-error font-semibold' : '' }}">{{ $ingredient->name }}</p>
                    <p>{{ $ingredient->pivot->quantity }}</p>
                </div>
                @endforeach
                @if ($ingredients->isNotEmpty())
                <p class="text-error">※未設定の食材があります。食材管理画面で詳細設定を行ってから承認処理を行ってください。</p>
                @endif
            </div>
            <div class="my-6 md:flex-1">
                <p class="text-lg font-semibold">作り方</p>
                @foreach($recipe->steps as $step)
                <div class="flex gap-2 m-2 py-1 border-b border-dashed px-2.5">
                    <p class="shrink-0">step{{ $step->step_number }}：</p>
                    <p class="break-words">{{ $step->content }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="w-full bg-taupe-100 rounded-2xl md:w-3/4 px-4 py-3">
            <p class="text-lg font-semibold">コツ・ポイント
            <p>{{ $recipe->tips }}</p>
        </div>
        <div class="w-full my-4 md:my-auto relative px-6">
            <form class="w-full md:w-3/4 mx-auto md:my-8 px-6" action="{{ route('admin.recipe.reject', ['recipe_id' => $recipe->id]) }}" method="post">
                @csrf
                @method('PUT')
                <label class="font-semibold text-lg" for="rejection_reason">差戻し理由</label>
                <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y @error('rejection_reason') border-error @enderror" name="rejection_reason" id="rejection_reason">{{ old('rejection_reason', $recipe->rejection_reason)}}</textarea>
                <button class="block mx-auto text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">差戻し</button>
            </form>
            <form class="absolute right-6 bottom-0" action="{{ route('admin.recipe.approve', ['recipe_id' => $recipe->id]) }}" method="post">
                @csrf
                @method('PUT')
                <button class="text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">承認</button>
            </form>
        </div>
    </div>
</div>
@endsection