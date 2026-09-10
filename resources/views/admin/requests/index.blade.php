@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">レシピ投稿・修正申請一覧</h2>
        <div>
            @if ($recipes->isEmpty())
                <p class="text-lg ml-4">申請中のレシピはありません。</p>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($recipes as $recipe)
                        <a class="w-full bg-white rounded-lg shadow-md overflow-hidden" href="{{ route('admin.application', ['recipe_id' => $recipe->id]) }}">
                            <div><img class="w-full h-48 object-cover" src="{{ $recipe->image_url }}" alt="{{ $recipe->name }}"></div>
                            <p class="font-bold text-center m-2">{{ $recipe->name }}</p>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $recipes->appends(request()->query())->links('pagination::custom') }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection