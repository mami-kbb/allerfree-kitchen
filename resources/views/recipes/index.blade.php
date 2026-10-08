@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white p-4 mb-6 md:mx-6  md:py-8 md:px-10">
        @unless (request()->hasAny(['keyword', 'exclude_ingredients', 'allergy_recipe', 'allergy_category']))
        <div class="mb-6 flex flex-wrap items-center justify-between gap-x-4 gap-y-1 rounded-xl bg-taupe-100 px-4 py-3">
            <p class="text-sm text-accent md:text-base">
                <span class="font-bold">アレルギー品目を除外して</span>レシピを探せます。食品表示法の29品目に対応！
            </p>
            <button type="button" data-about-open
                    class="shrink-0 text-sm font-semibold text-blue-800 underline hover:text-blue-900 cursor-pointer">
                使い方を見る →
            </button>
        </div>
        @endunless
        <div>
            <h2 class="text-2xl font-bold text-accent">{{ $message }}</h2>
            @if ($excludeIngredientsDisplay)
            <p class="text-secondary">除外食材：<span>{{ $excludeIngredientsDisplay }}</span></p>
            @endif
            @if ($selectedAllergies->count())
            <p class="text-secondary">除外アレルギー：<span class="font-bold text-secondary">{{ $selectedAllergies->implode('，') }}</span></p>
            @endif
            @if ($selectedCategories->count())
            <p class="text-secondary">除外アレルギーカテゴリー：<span class="font-bold text-secondary">{{ $selectedCategories->implode('，') }}</span></p>
            @endif
        </div>
        <div class="sm:mx-6 px-4 py-2 border-b">
            <a class="text-xl {{ $tab === 'recommend' ? 'font-bold' : ''}}" href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'recommend'])) }}">おすすめ</a>
            <a class="text-xl px-4 {{ $tab === 'mylist' ? 'font-bold' : '' }}" href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'mylist'])) }}">お気に入り</a>
        </div>
        <div class="container mx-auto p-4">
            @if ($tab === 'mylist' && auth()->guest())
                <p class="text-lg ml-4">
                    <a class="hover:font-bold text-gray-600 hover:text-gray-900" href="{{ route('login') }}">ログイン</a>するとお気に入りを管理できます
                </p>
            @else
                @if ($recipes->isEmpty())
                    <p class="text-lg ml-4">{{ $tab === 'mylist' ? 'お気に入りのレシピはありません。' : '該当するレシピが見つかりませんでした。' }}</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($recipes as $recipe)
                            <a class="w-full bg-white rounded-lg shadow-md overflow-hidden" href="{{ route('recipe.show', ['recipe_id' => $recipe->id]) }}">
                                <div><img class="w-full h-48 object-cover" src="{{ $recipe->thumbnail_url }}" alt="{{ $recipe->name }}" loading="lazy"></div>
                                <p class="font-bold text-center m-2">{{ $recipe->name }}</p>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $recipes->appends(request()->query())->links('pagination::custom') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection