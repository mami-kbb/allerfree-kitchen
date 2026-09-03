@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white p-4 mb-6 md:mx-6 md:py-8 md:px-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">レシピ編集</h2>
        <div>
            <form id="recipe-update-form" class="w-full md:w-3/4 mx-auto md:my-8 px-6" action="{{ route('recipe.update', ['recipe_id' => $recipe->id]) }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="flex flex-col items-center gap-4" id="list">
                    <img class="w-full md:w-xl h-102 rounded-2xl object-cover" src="{{ $recipe->image_url }}" alt="レシピ画像">
                    <label class="block text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" for="image">レシピ画像を選択する</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg" hidden>
                    @error('image')
                    <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col items-center w-full">
                    <div class="my-4 w-full">
                        <h3 class="ml-4 mb-6 font-semibold text-xl">***レシピ名と紹介***</h3>
                        <div class="mb-4">
                            <label class="font-semibold mr-2 md:text-lg" for="name">レシピ名</label>
                            <input class="w-full border rounded-md mt-2 py-1 px-2 @error('name') border-error @enderror" type="text" id="name" name="name" value="{{ old('name', $recipe->name) }}">
                            @error('name')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="font-semibold md:text-lg">アレルギー</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @php
                                $selectedAllergies = old('allergy_recipe', $selectedAllergies);
                                @endphp
                                @foreach($allergies as $allergy)
                                <div class="mb-1">
                                    <input class="peer sr-only allergy-checkbox" type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_recipe[]" {{ in_array($allergy->id, $selectedAllergies) ? 'checked' : '' }} >
                                    <label class="border border-gray-300 bg-white rounded-full px-3 py-1 text-sm cursor-pointer peer-checked:bg-taupe-300" for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('allergy_recipe')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="font-semibold md:text-lg">レシピの紹介</label>
                            <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y @error('description') border-error @enderror" name="description" cols="30" rows="5" id="description">{{ old('description', $recipe->description) }}</textarea>
                            @error('description')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="my-4 w-full">
                        <h3 class="ml-4 mb-6 font-semibold text-xl">***材料と作り方***</h3>
                        <div>
                            <label class="font-semibold text-lg">出来上がり量</label>
                            <input class="border rounded-md  py-1 px-2" type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings', $recipe->servings) }}">
                        </div>
                        <div class="md:flex mx-auto md:my-8 gap-6">
                            <div class="my-6 md:my-auto md:w-1/2">
                                <label class="font-semibold text-lg">材料</label>
                                <div id="ingredient-list">
                                    @foreach($recipe->ingredients as $i => $ingredient)
                                    <div class="flex gap-2 mb-2 ingredient-item">
                                        <div class="relative flex-1">
                                            <input class="border rounded-md  py-1 px-2 w-full ingredient-input @if($errors->has('ingredients') || $errors->has('ingredients.'.$i)) border-error @endif" type="text" name="ingredients[]" placeholder="材料名" value="{{ old('ingredients.'.$i, $ingredient->name) }}" autocomplete="off">
                                            <ul class="ingredient-suggestions absolute z-10 bg-white border rounded-md w-full mt-1 max-h-48 overflow-y-auto hidden shadow-md"></ul>
                                        </div>
                                        
                                        <input class="w-1/3 border rounded-md  py-1 px-2 @if($errors->has('ingredients') || $errors->has('quantities.'.$i)) border-error @endif" type="text" name="quantities[]" placeholder="分量" value="{{ old('quantities.'.$i, $ingredient->pivot->quantity) }}">
                                    </div>
                                    @endforeach
                                    <div class="flex gap-2 mb-2 ingredient-item">
                                        <div class="relative flex-1">
                                            <input class="w-full border rounded-md  py-1 px-2 ingredient-input @if($errors->has('ingredients') || $errors->has('ingredients.'.$i)) border-error @endif" type="text" name="ingredients[]" placeholder="材料名" autocomplete="off">
                                            <ul class="ingredient-suggestions absolute z-10 bg-white border rounded-md w-full mt-1 max-h-48 overflow-y-auto hidden shadow-md"></ul>
                                        </div>

                                        <input class="w-1/3 border rounded-md  py-1 px-2 @if($errors->has('ingredients') || $errors->has('quantities.'.$i)) border-error @endif" type="text" name="quantities[]" placeholder="分量">
                                    </div>
                                </div>
                                <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="button" id="add-ingredient">+ 材料を追加</button>
                                @error('ingredients')
                                <p class="text-error">{{ $message }}</p>
                                @enderror

                                @foreach($errors->get('ingredients.*') as $messages)
                                    @foreach($messages as $message)
                                    <p class="text-error">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                                @foreach ($errors->get('quantities.*') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="text-error">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class="md:flex-1">
                                <label class="font-semibold text-lg">作り方</label>
                                <div id="step-list">
                                    @foreach ($recipe->steps as $i => $step)
                                    <div class="flex w-full mb-2 step-item">
                                        <label>{{ $i +1 }}：</label>
                                        <input class="w-full border rounded-md  py-1 px-2 @error('steps.'.$i) border-error @enderror" type="text" name="steps[]" placeholder="作り方" value="{{ old('steps.'.$i, $step->content) }}">
                                    </div>
                                    @endforeach
                                    <div class="flex mb-2 step-item">
                                        <label>{{ $recipe->steps->count() + 1 }}：</label>
                                        <input class="w-full border rounded-md  py-1 px-2 @error('steps.'.$i) border-error @enderror" type="text" name="steps[]" placeholder="作り方">
                                    </div>
                                </div>
                                <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="button" id="add-step">+ 工程を追加</button>
                                @error('steps.0')
                                <p class="text-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="w-full my-4 md:my-auto">
                        <label class="font-semibold text-lg">コツ・ポイント</label>
                        <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y @error('tips') border-error @enderror" name="tips" id="tips" cols="30" rows="5">{{ old('tips', $recipe->tips) }}</textarea>
                        @error('tips')
                        <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </form>
            <div class="flex justify-center gap-4 mt-6">
                <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit" form="recipe-update-form">更新</button>
                <form action="{{ route('recipe.delete', ['recipe_id' => $recipe->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="block text-center bg-accent hover:shadow-md border border-accent text-white px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">削除</button>
                </form>
            </div>
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
        newItem.className = 'flex gap-2 mb-2 ingredient-item';
        newItem.innerHTML = `
            <div class="relative flex-1">
                <input type="text" name="ingredients[]" class="border rounded-md py-1 px-2 w-full ingredient-input @if($errors->has('ingredients') || $errors->has('ingredients.'.$i)) border-error @endif" placeholder="材料名" autocomplete="off">
                <ul class="ingredient-suggestions absolute z-10 bg-white border rounded-md w-full mt-1 max-h-48 overflow-auto hidden shadow-md"></ul>
            </div>
            <input type="text" name="quantities[]" class="w-1/3 border rounded-md py-1 px-2 @if($errors->has('ingredients') || $errors->has('quantities.'.$i)) border-error @endif" placeholder="分量">
        `;
        container.appendChild(newItem);
    });

    const ingredientCandidates = @json($ingredients);

    function setupIngredientAutocomplete(container) {
        container.addEventListener('input', function (e) {
            if (!e.target.classList.contains('ingredient-input')) return;
            showSuggestions(e.target);
        });

        container.addEventListener('focus', function (e) {
            if (!e.target.classList.contains('ingredient-input')) return;
            showSuggestions(e.target);
        }, true);

        document.addEventListener('click', function (e) {
            if (!e.target.classList.contains('ingredient-input')) {
                document.querySelectorAll('.ingredient-suggestions').forEach(ul => ul.classList.add('hidden'));
            }
        });
    }

    function showSuggestions(input) {
        const keyword = input.value.trim();
        const ul = input.parentElement.querySelector('.ingredient-suggestions');
        ul.innerHTML = '';

        const matches = keyword === ''
            ? ingredientCandidates
            : ingredientCandidates.filter(item => item.name.includes(keyword) || (item.reading && item.reading.includes(keyword)));

        if (matches.length === 0) {
            ul.classList.add('hidden');
            return;
        }

        matches.slice(0, 10).forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.name;
            li.className = 'px-2 py-1 hover:bg-taupe-200 cursor-pointer';
            li.addEventListener('mousedown', function (e) {
                e.preventDefault();
                input.value = item.name;
                ul.classList.add('hidden');
            });

            ul.appendChild(li);
        });

        ul.classList.remove('hidden');
    }
    setupIngredientAutocomplete(document.getElementById('ingredient-list'));

    document.getElementById('add-step').addEventListener('click', function() {
        const container = document.getElementById('step-list');
        const stepCount = container.getElementsByClassName('step-item').length + 1;
        const newItem = document.createElement('div');
        newItem.className = 'flex mb-2 step-item';
        newItem.innerHTML = `
            <span class="step-number">${stepCount}：</span>
            <input type="text" name="steps[]" class="w-full border rounded-md py-1 px-2 @error('steps.'.$i) border-error @enderror" placeholder="作り方">
        `;
        container.appendChild(newItem);
    });
</script>
@endsection