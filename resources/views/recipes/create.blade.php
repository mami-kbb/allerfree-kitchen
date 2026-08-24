@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white p-4 mb-6 md:mx-6  md:py-8 md:px-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">レシピ投稿</h2>
        <div>
            <form class="flex flex-col items-center gap-4 w-full md:w-3/4 mx-auto md:my-8 px-6" action="{{ route('recipe.store') }}" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="flex flex-col items-center">
                    <div class="border h-86 w-xl rounded-2xl @error('image') border-error @enderror overflow-hidden" id="list"></div>
                    <label class="text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" for="image">レシピ画像を選択する</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg" hidden>
                    @error('image')
                    <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="my-4 w-full">
                    <h3 class="ml-4 mb-6 font-semibold text-xl">***レシピ名と説明***</h3>
                    <div class="mb-4">
                        <label class="font-semibold mr-2 md:text-lg" for="name">レシピ名</label>
                        <input class="w-full border rounded-md py-1 px-2 @error('name') border-error @enderror" type="text" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                        <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="font-semibold md:text-lg">アレルギー</label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($allergies as $allergy)
                            <div class="mb-1">
                                <input class="peer sr-only allergy-checkbox" type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_recipe[]" {{ in_array($allergy->id, old('allergy_recipe', [])) ? 'checked' : '' }} >
                                <label class="border border-gray-300 bg-white rounded-full px-3 py-1 text-sm cursor-pointer peer-checked:bg-taupe-300" for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('allergy_recipe')
                        <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="font-semibold md:text-lg">レシピの説明</label>
                        <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y @error('description') border-error @enderror" name="description" cols="30" rows="5" id="description">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-error">{{ message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="my-4 w-full">
                    <h3 class="ml-4 mb-6 font-semibold text-xl">***材料と作り方***</h3>
                    <div class="mb-2">
                        <label class="font-semibold text-lg">出来上がり量</label>
                        <input class="border rounded-md  py-1 px-2" type="text" name="servings" id="servings" placeholder="例: 2人分、15cm型1台分" value="{{ old('servings') }}">
                    </div>
                    <div class="md:flex mx-auto md:my-8 gap-6">
                        <div class=" md:w-1/2">
                            <label class="font-semibold text-lg">材料</label>
                            <div class="my-2" id="ingredient-list">
                                @for ($i = 0; $i < max(2, count(old('ingredients', []))); $i++)
                                <div class="flex gap-2 mb-2">
                                    <input class="border rounded-md  py-1 px-2 flex-1 @if($errors->has('ingredients') || $errors->has('ingredients.'.$i)) border-error @endif" type="text" name="ingredients[]" placeholder="材料名" value="{{ old('ingredients.'.$i) }}">
                                    <input class="w-1/3 border rounded-md  py-1 px-2  @if($errors->has('ingredients') || $errors->has('quantities.'.$i)) border-error @endif" type="text" name="quantities[]" placeholder="分量" value="{{ old('quantities.'.$i) }}">
                                </div>
                                @endfor
                            </div>
                            <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="button" id="add-ingredient">+ 材料を追加</button>
                            @error('ingredients')
                            <p class="text-error">{{ $message }}</p>
                            @enderror

                            @foreach ($errors->get('ingredients.*') as $messages)
                                @foreach ($messages as $message)
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
                            <label class="font-semibold text-lg mb-2">作り方</label>
                            <div class="my-2" id="step-list">
                                @for ($i = 0; $i < max(2, count(old('steps', []))); $i++)
                                <div class="flex w-full mb-2 step-item">
                                    <label class="mr-2">{{ $i +1 }}:</label>
                                    <input class="w-full border rounded-md  py-1 px-2 @error('steps.'.$i) border-error @enderror" type="text" name="steps[]" placeholder="作り方" value="{{ old('steps.'.$i) }}">
                                </div>
                                @endfor
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
                    <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y @error('tips') border-error @enderror" name="tips" id="tips" cols="30" rows="5">{{ old('tips') }}</textarea>
                    @error('tips')
                        <p class="text-error">{{ message }}</p>
                        @enderror
                </div>
                <div>
                    <button class="block text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">投稿する</button>
                </div>
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
        img.className = 'w-full h-full rounded-2xl object-cover';

        list.innerHTML = '';
        list.classList.remove('border');
        list.appendChild(img);
    });

    document.getElementById('add-ingredient').addEventListener('click', function() {
        const container = document.getElementById('ingredient-list');
        const newItem = document.createElement('div');
        newItem.className = 'flex gap-2 mb-2';
        newItem.innerHTML = `
            <input type="text" name="ingredients[]" class="flex-1 border rounded-md py-1 px-2 @if($errors->has('ingredients') || $errors->has('ingredients.'.$i)) border-error @endif" placeholder="材料名">
            <input type="text" name="quantities[]" class="w-1/3 border rounded-md py-1 px-2 @if($errors->has('ingredients') || $errors->has('quantities.'.$i)) border-error @endif" placeholder="分量">
        `;
        container.appendChild(newItem);
    });

    document.getElementById('add-step').addEventListener('click', function() {
        const container = document.getElementById('step-list');
        const stepCount = container.getElementsByClassName('step-item').length + 1;
        const newItem = document.createElement('div');
        newItem.className = 'flex mb-2 step-item';
        newItem.innerHTML = `
            <span class="mr-2">${stepCount}:</span>
            <input type="text" name="steps[]" class="w-full border rounded-md py-1 px-2 @error('steps.'.$i) border-error @enderror" placeholder="作り方">
        `;
        container.appendChild(newItem);
    });
</script>
@endsection