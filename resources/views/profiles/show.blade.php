@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:py-6 md:px-10">
        @if (session('delete'))
        <div class="font-bold text-accent">
            {{ session('delete') }}
        </div>
        @endif
        <div>
            <div class="flex gap-6 justify-center items-center">
                @if($user->profile?->profile_image)
                    <img class="block shrink-0 w-48 h-48 md:w-64 md:h-64 rounded-full object-cover" src="{{ asset('storage/'.$user->profile->profile_image) }}" alt="ユーザーアイコン">
                @else
                    <img class="block shrink-0 w-48 h-48 md:w-64 md:h-64 rounded-full object-cover" src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
                @endif
                <div class=" my-4">
                    <p class="text-2xl font-semibold">{{ $user->name }}</p>
                    @if($user->profile?->comment)
                    <p>{{ $user->profile->comment }}</p>
                    @endif
                    @if (auth()->id() === $user->id)
                    <a class="block w-48 text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2 ml-auto my-4 rounded-md font-semibold" href="{{ route('profile.edit')}}">プロフィールを編集</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="m-6 border-b px-4 py-2">
            <h2 class="text-2xl font-semibold">投稿レシピ</h2>
        </div>
        @if ($recipes->isEmpty())
            <p class="text-lg ml-4">No recipe</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($recipes as $recipe)
                    <a class="w-full bg-white rounded-lg shadow-md overflow-hidden" href="{{ route('recipe.show', ['recipe_id' => $recipe->id]) }}">
                        <div><img class="w-full h-48 object-cover" src="{{ asset('storage/'. $recipe->image) }}" alt="{{ $recipe->name }}"></div>
                        <p class="font-bold my-2 text-center">{{ $recipe->name }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $recipes->appends(request()->query())->links('pagination::custom') }}
            </div>
        @endif
    </div>
</div>
@endsection