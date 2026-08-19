@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6 flex flex-col items-center">
    <div class="md:flex gap-10">
        <div>
            <img class="w-xl rounded-2xl object-cover" src="{{ asset('storage/'.$recipe->image) }}" alt="{{ $recipe->name }}">
        </div>
        <div class="mx-2 px-4">
            <h2 class="text-center text-2xl font-bold text-accent m-4">{{ $recipe->name }}</h2>
            <div class="flex justify-end items-center m-4">
                <p>投稿者：</p>
                <a class="flex items-center gap-4" href="{{ route('profile',['user_id' => $recipe->user_id]) }}">
                    @if($recipe->user->profile?->profile_image)
                    <img class="block shrink-0 w-12 h-12 rounded-full object-cover" src="{{ asset('storage/'.$recipe->user->profile->profile_image) }}" alt="ユーザーアイコン">
                    @else
                    <img class="block shrink-0 w-12 h-12 rounded-full object-cover" src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
                    @endif
                    <p>{{ $recipe->user->name }}</p>
                </a>
            </div>
            <div>
                <h3 class="text-xl font-semibold">レシピ説明</h3>
                <p class="my-3">{{ $recipe->description }}</p>
            </div>
            <div class="my-2">
                <span class="text-lg font-semibold">該当アレルギー：</span>
                @foreach ($recipe->allergies as $allergy)
                <span class="text-md font-semibold border rounded-full px-2 py-1 mx-1 text-olive-500 bg-white">{{ $allergy->name }}</span>
                @endforeach
            </div>
            <div class="text-end">
                @auth
                <form action="{{ route($isLiked ? 'unlike' : 'like', ['recipe' => $recipe]) }}" method="post">
                    @csrf
                    <button class="like-btn">
                        <img src="{{ asset($isLiked ? '/images/heart_logo_pink.png' : '/images/heart_logo.png') }}" alt="いいね" class="w-8 object-cover">
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="like-btn">
                    <img src="{{ asset('/images/heart_logo.png') }}" alt="いいね" class="w-8 object-cover">
                </a>
                @endauth
                <p class="" data-testid="like-count">{{ $recipe->likes_count }}</p>
            </div>
            @if (auth()->id() === $recipe->user->id)
            <a class="block w-48 text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2  my-4 rounded-md font-semibold" href="{{ route('recipe.edit',['recipe_id' => $recipe->id]) }}">レシピを編集</a>
            @endif
        </div>
    </div>
    <div class="w-full md:flex m-4 px-6 gap-12">
        <div class="my-6 md:w-1/4">
            <h3 class="text-lg font-semibold">材料({{ $recipe->servings }})</h3>
            @foreach ($recipe->ingredients as $ingredient)
            <div class="flex justify-between my-1 py-1 border-b px-2.5">
                <p>{{ $ingredient->name }}</p>
                <p>{{ $ingredient->pivot->quantity }}</p>
            </div>
            @endforeach
        </div>
        <div class="my-6 w-full md:w-1/2">
            <h3 class="text-lg font-semibold">作り方</h3>
            @foreach($recipe->steps as $step)
            <div class="flex m-2 py-1 border-b px-2.5">
                <p>step{{ $step->step_number }}：</p>
                <p class="step-content">{{ $step->content }}</p>
            </div>
            @endforeach
        </div>
    </div>
    <div>
        <h3 class="text-lg font-semibold">コツ・ポイント</h3>
        <p>{{ $recipe->tips }}</p>
    </div>
    <p class="text-sm text-orange-900 m-4">※使用する調味料や加工食品によっては、アレルゲンが含まれる可能性があります。必ず商品の表示をご確認ください。</p>
    <div>
        <div>
            <h3>コメント<span>({{ $recipe->comments_count }})</span></h3>
            @foreach ($recipe->comments as $comment)
            <div>
                <div>
                    <div>
                        @if ($comment->user->profile?->profile_image)
                        <img src="{{ asset('storage/'.$comment->user->profile->profile_image) }}" alt="コメントユーザーアイコン">
                        @else
                        <img src="{{ asset('/images/icon.png') }}" alt="コメントユーザーアイコン">
                        @endif
                    </div>
                    <p>{{ $comment->user->name }}</p>
                </div>
                <p>{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
        <div>
            <h3>コメントを投稿</h3>
            <form action="{{ route('comment', ['recipe'=> $recipe]) }}" method="post">
                @csrf
                <textarea name="comment" id="comment">{{ old('comment') }}</textarea>
                <div>
                    @error('comment')
                    {{ $message }}
                    @enderror
                </div>
                <button type="submit">送信</button>
            </form>
        </div>
    </div>
</div>
@endsection