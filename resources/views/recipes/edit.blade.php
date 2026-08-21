@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white md:mx-6 p-4 md:py-6 md:px-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">レシピ編集</h2>
        <div>
            <form class="w-full md:3/4 mx-auto md:my-8 px-6" action="{{ route('recipe.update', ['recipe_id' => $recipe->id]) }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="flex flex-col items-center gap-4" id="list">
                    <img class="w-full md:w-xl rounded-2xl object-cover" src="{{ asset('storage/'. $recipe->image) }}" alt="レシピ画像">
                    <label class="block text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" for="image">レシピ画像を選択する</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg" hidden>
                    @error('image')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col items-center">
                    <div class="my-4">
                        <h3 class="ml-4 mb-6 font-semibold text-xl">***レシピ名と紹介***</h3>
                        <div class="mb-4">
                            <label class="font-semibold mr-2 md:text-lg" for="name">レシピ名</label>
                            <input class="w-full border rounded-md  py-1 px-2" type="text" id="name" name="name" value="{{ old('name', $recipe->name) }}">
                            @error('name')
                            <p>{{ $message }}</p>
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
                            <div>
                                @error('allergy_recipe')
                                {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="font-semibold md:text-lg">レシピの紹介</label>
                            <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y" name="description" cols="30" rows="5" id="description">{{ old('description', $recipe->description) }}</textarea>
                        </div>
                    </div>
                    <div>
                        <h3 class="ml-4 mb-6 font-semibold text-xl">***材料と作り方***</h3>
                        <div>
                            <label class="font-semibold md:text-lg">出来上がり量</label>
                            <input class="border rounded-md  py-1 px-2" type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings', $recipe->servings) }}">
                        </div>
                        <div class="w-full md:flex mx-auto md:my-8 gap-6">
                            <div class="md:w-1/2">
                                <label class="font-semibold md:text-lg">材料</label>
                                <div id="ingredient-list">
                                    @foreach($recipe->ingredients as $i => $ingredient)
                                    <div class="flex gap-2 mb-2">
                                        <input class="border rounded-md  py-1 px-2 flex-1" type="text" name="ingredients[]" placeholder="材料名" value="{{ old('ingredients.'.$i, $ingredient->name) }}">
                                        <input class="w-1/3 border rounded-md  py-1 px-2" type="text" name="quantities[]" placeholder="分量" value="{{ old('quantities.'.$i, $ingredient->pivot->quantity) }}">
                                    </div>
                                    @endforeach
                                    <div class="flex gap-2 mb-2">
                                        <input class="flex-1 border rounded-md  py-1 px-2" type="text" name="ingredients[]" placeholder="材料名">
                                        <input class="w-1/3 border rounded-md  py-1 px-2" type="text" name="quantities[]" placeholder="分量">
                                    </div>
                                </div>
                                <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="button" id="add-ingredient">+ 材料を追加</button>
                                @error('ingredients.0')
                                <p>{{ $message }}</p>
                                @enderror
                                @error('quantities')
                                <p>{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-full md:flex-1">
                                <label class="font-semibold md:text-lg">作り方</label>
                                <div id="step-list">
                                    @foreach ($recipe->steps as $i => $step)
                                    <div class="flex w-full mb-2">
                                        <label>{{ $i +1 }}：</label>
                                        <input class="w-full border rounded-md  py-1 px-2" type="text" name="steps[]" placeholder="作り方" value="{{ old('steps.'.$i, $step->content) }}">
                                    </div>
                                    @endforeach
                                    <div class="flex mb-2">
                                        <label>{{ $recipe->steps->count() + 1 }}：</label>
                                        <input class="w-full border rounded-md  py-1 px-2" type="text" name="steps[]" placeholder="作り方">
                                    </div>
                                </div>
                                <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="button" id="add-step">+ 工程を追加</button>
                                @error('steps.0')
                                <p>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold md:text-lg">コツ・ポイント</label>
                        <textarea name="tips" id="tips" cols="30" rows="5">{{ old('tips', $recipe->tips) }}</textarea>
                    </div>
                </div>
                <div>
                    <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">更新</button>
                </div>
            </form>
            <div>
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