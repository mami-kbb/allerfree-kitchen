@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6 ">
    <div>
        <h2 class="text-2xl font-bold text-accent">{{ $message }}</h2>
        @if ($selectedAllergies->count())
        <p class="text-secondary">除外アレルギー：<span class="font-bold text-secondary">{{ $selectedAllergies->implode('，') }}</span></p>
        @endif
        @if ($selectedCategories->count())
        <p class="text-secondary">除外アレルギーカテゴリー：<span class="font-bold text-secondary">{{ $selectedCategories->implode('，') }}</span></p>
        @endif
    </div>
    <div class="mx-4 sm:mx-6 py-4">
        <a class="text-xl {{ $tab === 'recommend' ? 'font-bold' : ''}}" href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'recommend'])) }}">おすすめ</a>
        <a class="text-xl px-4 {{ $tab === 'mylist' ? 'font-bold' : '' }}" href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'mylist'])) }}">お気に入り</a>
    </div>
    <div class="container mx-auto p-4">
        @if ($tab === 'mylist' && auth()->guest())
            <p>
                <a href="{{ route('login') }}">ログイン</a>するとお気に入りを管理できます
            </p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($recipes as $recipe)
                <a class="w-full bg-white rounded-lg shadow-md overflow-hidden" href="{{ route('recipe.show', ['recipe_id' => $recipe->id]) }}">
                    <div><img class="w-full h-48 object-cover" src="{{ asset('storage/'. $recipe->image) }}" alt="{{ $recipe->name }}"></div>
                    <p class="font-bold my-2">{{ $recipe->name }}</p>
                </a>
                @empty
                <p>{{ $tab === 'mylist' ? 'お気に入りのレシピはありません。' : '該当するレシピが見つかりませんでした。' }}</p>
                @endforelse
            </div>
            <div>
                {{ $recipes->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection