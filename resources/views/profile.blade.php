@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <div>
        <div>
            @if($user->profile->profile_image)
                <img src="{{ asset('storage/'.$user->profile->profile_image) }}" alt="ユーザーアイコン">
            @else
                <img src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
            @endif
            <div>
                <p>{{ $user->name }}</p>
                <p>{{ $user->profile->comment }}</p>
            </div>
        </div>
        @if (auth()->id() === $user->id)
        <a href="{{ route('profile.edit')}}">プロフィールを編集</a>
        @endif
    </div>
    <div>
        <h2>投稿レシピ</h2>
    </div>
    <div>
        @forelse($recipes as $recipe)
        <a href="{{ route('recipe.show', ['recipe_id' => $recipe->id]) }}">
            <div><img src="{{ asset('storage/'. $recipe->image) }}" alt="{{ $recipe->name }}"></div>
            <p>{{ $recipe->name }}</p>
        </a>
        @empty
        <p>該当するレシピが見つかりませんでした。</p>
        @endforelse
        <div>
            {{ $recipes->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection