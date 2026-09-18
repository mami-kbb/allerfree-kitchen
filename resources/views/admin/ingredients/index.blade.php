@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">食材管理・設定</h2>
        <div class="w-full text-center">
            <h3 class="inline font-bold text-lg md:text-xl bg-gray-200 border-mist-500 border rounded-full px-3 py-1">食材設定</h3>
            @if($incompleteIngredients->isNotEmpty())
            <p class="my-3 text-accent">※以下の食材の設定を行ってください</p>
            <div class="hidden md:grid md:grid-cols-[0.7fr_1fr_1.2fr_2fr_auto] gap-3 text-start">
                <p class="font-bold text-lg">食材名</p>
                <p class="font-bold text-lg">読み方</p>
                <p class="font-bold text-lg">食材カテゴリー</p>
                <p class="font-bold text-lg">アレルギーカテゴリー</p>
                <p></p>
            </div>

            @php
                $errorIngredientId = old('ingredient_id');
            @endphp
            @foreach($incompleteIngredients as $incompleteIngredient)
            <form class="w-full border-b border-dashed mb-2 pb-2" action="{{ route('ingredient.update', ['ingredient_id' => $incompleteIngredient->id]) }}" method="post">
                @csrf
                @method('PUT')
                    <div class="w-full md:grid md:grid-cols-[0.7fr_1fr_1.2fr_2fr_auto] md:items-start gap-3">
                        <div class="flex mb-2 md:mb-0 md:items-center md:text-start">
                            <p class="font-bold md:hidden">食材名：</p>
                            <p>{{ $incompleteIngredient->name }}</p>
                            <input type="hidden" name="ingredient_id" value="{{ $incompleteIngredient->id }}">
                        </div>
                        <div class="text-start mb-2 md:mb-0">
                            <label class="font-bold md:hidden">読み方：</label>
                            <input class="border rounded-md py-1 px-2 @if($errorIngredientId == $incompleteIngredient->id) @error('reading') border-error @enderror @endif" type="text" id="reading-{{ $incompleteIngredient->id }}" name="reading" value="{{ $errorIngredientId == $incompleteIngredient->id ? old('reading') : $incompleteIngredient->reading }}">
                            @if($errorIngredientId == $incompleteIngredient->id)
                                @error('reading')
                                <p class="text-error">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                        <div class="text-start mb-2 md:mb-0">
                            <p class="font-bold md:hidden">食材カテゴリー：</p>
                            <div class="category-select relative">
                                <button type="button" class="category-selected w-full border rounded-md bg-white px-3 py-2 text-start cursor-pointer @if($errorIngredientId == $incompleteIngredient->id) @error('category') border-error @enderror @endif">
                                    <span class="category-label text-gray-500">選択してください</span>
                                    <span class="float-right">▼</span>
                                </button>
                                <ul class="category-options hidden absolute z-10 mt-1 w-full rounded-md border bg-white shadow-md">
                                    @foreach($categories as $category)
                                        <li>
                                            <button type="button" class="category-option w-full px-3 py-2 text-start hover:bg-gray-100" data-value="{{ $category }}">{{ $category }}</button>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="category" class="category-value">
                            </div>
                            @if($errorIngredientId == $incompleteIngredient->id)
                                @error('category')
                                <p class="text-error">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                        <div class="text-start mb-2 md:mb-0">
                            <p class="font-bold md:hidden">アレルギーカテゴリー：</p>

                            <div class="allergy-select relative">
                                <button type="button" class="allergy-selected w-full min-h-10 border rounded-md bg-white px-3 py-2 text-start cursor-pointer @if($errorIngredientId == $incompleteIngredient->id) @error('allergy_categories') border-error @enderror @endif">
                                    <span class="text-gray-500">選択してください</span>
                                    <span class="float-right">▼</span>
                                </button>
                                <div class="allergy-options hidden absolute z-10 mt-1 w-full rounded-md border bg-white p-2 shadow-md">
                                    @foreach($allergyCategories as $allergyCategory)
                                    <label class="flex items-center gap-2 px-2 py-2 cursor-pointer hover:bg-gray-100">
                                        <input type="checkbox" name="allergy_categories[]" class="allergy-checkbox" value="{{ $allergyCategory->id }}" data-label="{{ $allergyCategory->category }}">
                                        <span>{{ $allergyCategory->category }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @if($errorIngredientId == $incompleteIngredient->id)
                                @error('allergy_categories')
                                <p class="text-error">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <button class="self-start justify-self-start bg-taupe-200 hover:shadow-md border border-accent text-accent px-3 py-1 md:my-0 rounded-md font-semibold cursor-pointer" type="submit">設定</button>
                    </div>
            </form>
            @endforeach
            @else
            <div class="my-3">すべて設定済みです。登録が必要な食材はありません。</div>
            @endif
        </div>
        <div class="w-full md:w-1/2 md:mx-auto text-center mt-6">
            <h3 class="inline font-bold text-lg md:text-xl my-4 bg-gray-200 border rounded-full border-mist-500 px-3 py-1">食材リスト</h3>

            @foreach($categories as $category)
            <h4 class="font-bold md:text-lg text-yellow-900 bg-amber-50 mt-5">{{ $category }}</h4>
            <table class="w-full ">
                <thead>
                    <tr class="grid grid-cols-[1fr_1fr_2fr] border-b border-double mt-2">
                        <th>食材名</th>
                        <th>読み方</th>
                        <th>アレルギーカテゴリー</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ingredients->where('category', $category) as $ingredient)
                    <tr class="grid grid-cols-[1fr_1fr_2fr] border-b border-dashed my-2">
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ $ingredient->reading }}</td>
                        <td>{{ $ingredient->allergyCategories->pluck('category')->join('、') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
        </div>
    </div>
</div>

<script>
    //全部取得して。それぞれに処理を設定する。
    document.querySelectorAll('.category-select').forEach(select => {
        const selected = select.querySelector('.category-selected');
        const optionsBox = select.querySelector('.category-options');
        const options = select.querySelectorAll('.category-option');
        const hiddenInput = select.querySelector('.category-value');

        selected.addEventListener('click', () => {
            optionsBox.classList.toggle('hidden');
        });

        document.addEventListener('click', event => {
            document.querySelectorAll('.category-select').forEach(select => {
                if (!select.contains(event.target)) {
                    select.querySelector('.category-options').classList.add('hidden');
                }
            });
            document. querySelectorAll('.category-select').forEach(select => {
                if (!select.contains(event.target)) {
                    select.querySelector('.allergy-options').classList.add('hidden');
                }
            });
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.dataset.value;
                const label = option.textContent.trim();
                const selectedLabel = selected.querySelector('.category-label');

                selectedLabel.textContent = label;
                selectedLabel.classList.remove('text-gray-500');
                hiddenInput.value = value;
                optionsBox.classList.add('hidden');
            });
        });
    });

    document.querySelectorAll('.allergy-select').forEach(select => {
        const selected = select.querySelector('.allergy-selected');
        const optionsBox =select.querySelector('.allergy-options');
        const checkboxes = select.querySelectorAll('.allergy-checkbox');

        selected.addEventListener('click', () => {
            optionsBox.classList.toggle('hidden');
        });

        document.addEventListener('click', event => {
            document.querySelectorAll('.allergy-select').forEach(select => {
                if (!select.contains(event.target)) {
                    select.querySelector('.allergy-options').classList.add('hidden');
                }
            });
            document. querySelectorAll('.allergy-select').forEach(select => {
                if (!select.contains(event.target)) {
                    select.querySelector('.allergy-options').classList.add('hidden');
                }
            });
        });

        //チェック状態が変わったら表示を更新
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateSelected();
            });
        });

        function updateSelected() {
            const checked = select.querySelectorAll('.allergy-checkbox:checked');

            selected.innerHTML = '';

            if (checked.length === 0) {
                const placeholder = document.createElement('span');
                placeholder.className = 'text-gray-500';
                placeholder.textContent = '選択してください';

                selected.appendChild(placeholder)
            }

            checked.forEach(checkbox => {
                const tag = document.createElement('span');
                tag.className = 'inline-block mr-1 mb-1 rounded-full bg-taupe-300 px-2 py-1 text-sm';
                tag.textContent = checkbox.dataset.label;
                selected.appendChild(tag);
            });

            const arrow = document.createElement('span');
            arrow.className = 'float-right';
            arrow.textContent = '▼';

            selected.appendChild(arrow);
        }
    });
</script>
@endsection