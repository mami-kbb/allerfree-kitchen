@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <div>
        <img src="{{ asset('storage/'.$recipe->image) }}" alt="{{ $recipe->name }}">
        <div>
            <h2>{{ $recipe->name }}</h2>
            <div>
                <p>投稿者：</p>
                <a href="{{ route('profile',['user_id' => $recipe->user_id]) }}">
                    @if($recipe->user->profile->profile_image)
                    <img src="{{ asset('storage/'.$recipe->user->profile->profile_image) }}" alt="ユーザーアイコン">
                    @else
                    <img src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
                    @endif
                    {{ $recipe->user->name }}
                </a>
            </div>
            <div>
                <h3>レシピ説明</h3>
                <p>{{ $recipe->description }}</p>
            </div>
            <div>
                <span>該当アレルギー：</span>
                @foreach ($recipe->allergies as $allergy)
                <span>{{ $allergy->name }}</span>
                @endforeach
            </div>
            <div>
                @auth
                <form action="{{ route('like', ['id' => $recipe->id]) }}" method="post">
                    @csrf
                    <button class="like-btn">
                        <img src="{{ asset($isLiked ? '/images/heart_logo_pink.png' : '/images/heart_logo.png') }}" alt="いいね" class="like-btn__img">
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="like-btn">
                    <img src="{{ asset('/images/heart_logo.png') }}" alt="いいね" class="like-btn__img">
                </a>
                @endauth
                <p class="like-count" data-testid="like-count">{{ $recipe->likes_count }}</p>
            </div>
        </div>
    </div>
    <div>
        <div>
            <h3>材料({{ $recipe->servings }})</h3>
            @foreach ($recipe->ingredients as $ingredient)
            <div>
                <p>{{ $ingredient->name }}<span>{{ $ingredient->pivot->quantity }}</span></p>
            </div>
            @endforeach
        </div>
        <div>
            <h3>作り方</h3>
            @foreach($recipe->steps as $step)
            <div class="step-row">
                <p class="step-content"><span class="step-number">step{{ $step->step_number }}：</span>{{ $step->content }}</p>
            </div>
            @endforeach
        </div>
        <div>
            <h3>コツ・ポイント</h3>
            <p>{{ $recipe->tips }}</p>
        </div>
        <div>
            <h3>コメント<span>({{ $recipe->comments_count }})</span></h3>
            @foreach ($recipe->comments as $comment)
            <div></div>
            @endforeach
        </div>
    </div>
</div>
@endsection