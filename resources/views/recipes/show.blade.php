@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 p-4 md:py-6 md:px-10 flex flex-col items-center">
        <div class="md:flex gap-10 w-full max-w-6xl">
            <div class="md:w-1/2">
                <img class="w-full md:w-xl h-102 rounded-2xl object-cover" src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}">
            </div>
            <div class="md:flex-1 mx-2 px-4">
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
                <div class="flex justify-end">
                    <div class="flex flex-col items-center">
                        @auth
                        <form action="{{ route($isLiked ? 'unlike' : 'like', ['recipe' => $recipe]) }}" method="post">
                            @csrf
                            <button class="cursor-pointer">
                                <img src="{{ asset($isLiked ? '/images/heart_logo_pink.png' : '/images/heart_logo.png') }}" alt="いいね" class="w-8 object-cover">
                            </button>
                        </form>
                        @else
                        <a class="cursor-pointer" href="{{ route('login') }}">
                            <img src="{{ asset('/images/heart_logo.png') }}" alt="いいね" class="w-8 object-cover">
                        </a>
                        @endauth
                        <p data-testid="like-count">{{ $recipe->likes_count }}</p>
                    </div>
                </div>
                @if (auth()->id() === $recipe->user->id)
                <a class="block w-48 ml-auto text-center hover:shadow-md border border-accent text-accent px-4 py-2  my-4 rounded-md font-semibold" href="{{ route('recipe.edit',['recipe_id' => $recipe->id]) }}">レシピを編集</a>
                @endif
            </div>
        </div>
        <div class="w-full md:w-3/4 md:flex mx-auto md:my-8 px-6 gap-12">
            <div class="my-6 md:w-1/3">
                <p class="text-lg font-semibold">材料({{ $recipe->servings }})</p>
                @foreach ($recipe->ingredients as $ingredient)
                <div class="flex justify-between my-1 py-1 border-b border-dashed px-2.5">
                    <p>{{ $ingredient->name }}</p>
                    <p>{{ $ingredient->pivot->quantity }}</p>
                </div>
                @endforeach
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
            <p class="text-lg font-semibold">コツ・ポイント</h>
            <p>{{ $recipe->tips }}</p>
        </div>
        <p class="text-sm text-orange-900 m-4">※使用する調味料や加工食品によっては、アレルゲンが含まれる可能性があります。必ず商品の表示をご確認ください。</p>
        <div class="w-full md:w-3/4 border border-accent/20 rounded-2xl bg-white px-6 py-4 my-4">
            <div>
                <p class="text-lg font-bold">コメント<span>({{ $recipe->comments_count }})</span></p>
                <div id="comment-list">
                    @foreach ($recipe->comments as $index => $comment)
                        <div class="my-3 border-b border-dotted py-2 comment-item {{ $index >= 3 ? 'hidden' :'' }}" data-index="{{ $index }}">
                            <div class="flex items-center gap-2">
                                <div>
                                    @if ($comment->user->profile?->profile_image)
                                        <img class="block shrink-0 w-10 h-10 rounded-full object-cover" src="{{ asset('storage/'.$comment->user->profile->profile_image) }}" alt="コメントユーザーアイコン">
                                    @else
                                        <img class="block shrink-0 w-10 h-10 rounded-full object-cover" src="{{ asset('/images/icon.png') }}" alt="コメントユーザーアイコン">
                                    @endif
                                </div>
                                <p>{{ $comment->user->name }}</p>
                            </div>
                            <p class="mt-2">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
                @if ($recipe->comments_count > 3)
                    <button type="button" id="show-more-comments" class="block mx-auto mt-4bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 rounded-md cursor-pointer">
                    さらに表示
                    </button>
                @endif
            </div>
            <div class="my-4">
                <p class="font-bold">コメントを投稿</p>
                <form action="{{ route('comment', ['recipe'=> $recipe]) }}" method="post">
                    @csrf
                    <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y" name="comment" id="comment">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-error">{{ $message }}</p>
                    @enderror
                    <div class="text-center">
                        <button class="bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 mx-2 rounded-md font-semibold" type="submit">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //現段階ではコメント数は少ないため、最初に全コメントを取得し、CSSで非表示にする方法を採用
    const showMoreBtn = document.getElementById('show-more-comments');

    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function () {
            // 現在非表示になっているコメントを取得
            const hiddenComments = document.querySelectorAll('#comment-list .comment-item.hidden');

            // 先頭から3件だけ表示する
            hiddenComments.forEach((comment, i) => {
                if (i < 3) {
                    comment.classList.remove('hidden');
                }
            });

            // 全部表示し終わったらボタン自体を消す
            const remaining = document.querySelectorAll('#comment-list .comment-item.hidden');
            if (remaining.length === 0) {
                showMoreBtn.remove();
            }
        });
    }
</script>
@endsection