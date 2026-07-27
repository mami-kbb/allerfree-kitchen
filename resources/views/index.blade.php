@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <div>
        <h2>レシピ一覧</h2>
    </div>
    <div>
        <a href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'recommend'])) }}">おすすめ</a>
        <a href="{{ route('recipes.list', array_merge(request()->all(), ['tab' => 'mylist'])) }}">お気に入り</a>
    </div>
    <div>
        @if ($tab === 'mylist' && auth()->guest())
            <p>
                <a href="{{ route('login') }}">ログイン</a>するとお気に入りを管理できます
            </p>
        @else
            @forelse ($recipes as $recipe)
            <a href="{{ route('recipe.show', ['recipe_id' => $recipe->id]) }}">
                <div><img src="{{ asset('storage/'. $recipe->image) }}" alt="{{ $recipe->name }}"></div>
                <p>{{ $recipe->name }}</p>
            </a>
            @empty
            <p>{{ $tab === 'mylist' ? 'お気に入りのレシピはありません。' : '該当するレシピが見つかりませんでした。' }}</p>
            @endforelse
            <div>
                {{ $recipes->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection