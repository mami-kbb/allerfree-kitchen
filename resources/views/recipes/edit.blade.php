@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <h2>レシピ編集</h2>
    <div>
        <form action="{{ route('recipe.update', ['recipe_id' => $recipe->id]) }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <div>
                <p>レシピ画像</p>
                <div id="list">
                    <img src="{{ asset('storage/'. $recipe->image) }}" alt="レシピ画像">
                </div>
                <label for="image">画像を選択する</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg" hidden>
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
                    <input type="text" id="name" name="name" value="{{ old('name', $recipe->name) }}">
                    <div>
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div>
                    <label>アレルギー</label>
                    <div>
                        @php
                        $selectedAllergies = old('allergy_recipe', $selectedAllergies);
                        @endphp
                        @foreach($allergies as $allergy)
                        <input type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_recipe[]" {{ in_array($allergy->id, $selectedAllergies) ? 'checked' : '' }} >
                        <label for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                        @endforeach
                    </div>
                    <div>
                        @error('allergy_recipe')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div>
                    <label>レシピの説明</label>
                    <textarea name="description" cols="30" rows="5" id="description">{{ old('description', $recipe->description) }}</textarea>
                </div>
            </div>
            <div>
                <h3>材料と作り方</h3>
                <div>
                    <label>出来上がり量</label>
                    <input type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings', $recipe->servings) }}">
                </div>
                <div>
                    <label>材料</label>
                    <div id="ingredient-list">
                        @foreach($recipe->ingredients as $i => $ingredient)
                        <div class="ingredient-item">
                            <input type="text" name="ingredients[]" placeholder="材料名" value="{{ old('ingredients.'.$i, $ingredient->name) }}">
                            <input type="text" name="quantities[]" placeholder="分量" value="{{ old('quantities.'.$i, $ingredient->pivot->quantity) }}">
                        </div>
                        @endforeach
                        <div class="ingredient-item">
                            <input type="text" name="ingredients[]" placeholder="材料名">
                            <input type="text" name="quantities[]" placeholder="分量">
                        </div>
                    </div>
                </div>
                <button type="button" id="add-ingredient">+ 材料を追加</button>
                <div>
                    @error('ingredients.0')
                    {{ $message }}
                    @enderror
                    @error('quantities')
                    {{ $message }}
                    @enderror
                </div>

                <div>
                    <label>作り方</label>
                    <div id="step-list">
                        @foreach ($recipe->steps as $i => $step)
                        <div class="step-item">
                            <label>{{ $i +1 }}：</label>
                            <input type="text" name="steps[]" placeholder="作り方" value="{{ old('steps.'.$i, $step->content) }}">
                        </div>
                        @endforeach
                        <div class="step-item">
                            <label>{{ $recipe->steps->count() + 1 }}：</label>
                            <input type="text" name="steps[]" placeholder="作り方">
                        </div>
                    </div>
                    <button type="button" id="add-step">+ 工程を追加</button>
                </div>

                <div>
                    @error('steps.0')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div>
                <label>コツ・ポイント</label>
                <textarea name="tips" id="tips" cols="30" rows="5">{{ old('tips', $recipe->tips) }}</textarea>
            </div>
            <div>
                <button type="submit">更新</button>
            </div>
        </form>
        <div>
            <form action="{{ route('recipe.delete', ['recipe_id' => $recipe->id]) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function (e) {
        const list = document.getElementById('list');

        const file = e.target.files[0];
        if (!file) return;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'image';

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
            <span class="step-number">${stepCount}：</span>
            <input type="text" name="steps[]" class="form-step__input" placeholder="作り方">
        `;
        container.appendChild(newItem);
    });
</script>
@endsection