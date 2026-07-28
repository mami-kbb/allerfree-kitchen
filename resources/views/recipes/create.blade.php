@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <h2>レシピ投稿</h2>
    <div>
        <form action="{{ route('recipe.store') }}" method="post" novalidate>
            @csrf
            <div>
                <p>レシピ画像</p>
                <div id="list">
                    <label for="recipe_image">画像を選択する</label>
                </div>
                <input type="file" id="recipe_image" name="recipe_image" hidden>
                <div>
                    @error('image')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <h3>レシピ名と説明</h3>
                <div>
                    <label for="name">レシピ名</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                    <div>
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div>
                    <label>アレルギー</label>

                </div>
                <div>
                    <label>レシピの説明</label>
                    <textarea name="description" cols="30" rows="5" id="description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div>
                <h3>材料と作り方</h3>
                <div>
                    <label for="servings">出来上がり量</label>
                    <input type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings') }}">
                </div>
                <div>
                    <label>材料</label>
                </div>
            </div>
        </form>
    </div>
</div>
@endcontent