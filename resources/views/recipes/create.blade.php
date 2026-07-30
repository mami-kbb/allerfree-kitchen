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
                    <div>
                        @foreach($allergies as $allergy)
                        <input type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_recipe[]" {{ in_array($allergy->id, old('allergy_recipe', [])) ? 'checked' : '' }} >
                        <label for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                        @endforeach
                    </div>
                    <div>
                        @error('allergy_recipe')
                        {{ $message }}cd
                        @enderror
                    </div>
                </div>
                <div>
                    <label>レシピの説明</label>
                    <textarea name="description" cols="30" rows="5" id="description">{{ old('description') }}</textarea>
                </div>
            </div>
            <div>
                <h3>材料と作り方</h3>
                <div>
                    <label>出来上がり量</label>
                    <input type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings') }}">
                </div>
                <div>
                    <label>材料</label>
                    @for ($i = 0; $i < max(2, count(old('ingredients', []))); $i++)
                    <div>
                        <input type="text" name="ingredients[]" placeholder="材料名" value="{{ old('ingredients.'.$i) }}">
                        <input type="text" name="quantities[]" placeholder="分量" value="{{ old('quantities.'.$i) }}">
                    </div>
                    @endfor
                </div>
                <button type="button" id="add-ingredient">+ 材料を追加</button>
                @error('ingredients')
                {{ $message }}
                @enderror
                <div>
                    <label>作り方</label>
                    @for ($i = 0; $i < max(2, count(old('steps', []))); $i++)
                    <div>
                        <label>{{ $i +1 }}:</label>
                        <input type="text" name="steps[]" placeholder="作り方" value="{{ old('steps.'.$i) }}">
                    </div>
                    @endfor
                </div>
                <button type="button" id="add-step">+ 工程を追加</button>
                <div>
                    @error('steps')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <label>コツ・ポイント</label>
                <textarea name="tips" id="tips" cols="30" rows="5">{{ old('tips') }}</textarea>
            </div>
            <div>
                <button type="submit">投稿する</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('recipe_image').addEventListener('change', function (e) {
        const list = document.getElementById('list');

        const file = e.target.files[0];
        if (!file) return;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'reader_image';

        list.innerHTML = '';
        list.appendChild(img);
    });

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const container = document.getElementById('ingredient-list');
        const newItem = document.createElement('div');
        newItem.className = 'ingredient-item';
        newItem.style.marginBottom = '10px';
        newItem.innerHTML = `
            <input type="text" name="ingredients[]" class="form-ingredient__input" placeholder="材料名">
            <input type="text" name="quantities[]" class="quantity" placeholder="分量">
        `;
        container.appendChild(newItem);
    });

    document.getElementById('add-step').addEventListener('click', function() {
        const container = document.getElementById('step-list');
        const stepCount = container.getElementsByClassName('step-item').length + 1;
        const newItem = document.createElement('div');
        newItem.className = 'step-item';
        newItem.style.marginBottom = '10px';
        newItem.innerHTML = `
            <span class="step-number">${stepCount}</span>
            <input type="text" name="steps[]" class="form-step__input" placeholder="作り方の説明">
        `;
        container.appendChild(newItem);
    });
</script>
@endsection