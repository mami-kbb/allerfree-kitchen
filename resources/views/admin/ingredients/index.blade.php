@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">食材管理・設定</h2>
        <div class="w-full text-center">
            <h3 class="font-bold md:text-xl">食材設定</h3>
            @if($incompleteIngredients->isNotEmpty())
            <p class="mb-2">以下の食材の設定を行ってください</p>
            <div class="hidden md:grid grid-cols-5 md:text-start">
                <label class="font-bold text-lg">食材名</label>
                <label class="font-bold text-lg">読み方</label>
                <label class="font-bold text-lg">食材カテゴリー</label>
                <label class="font-bold text-lg">アレルギーカテゴリー</label>
                <label></label>
            </div>

            @foreach($incompleteIngredients as $incompleteIngredient)
            <form class="w-full border-b border-dashed mb-2 pb-2" action="{{ route('ingredient.update', ['ingredient_id' => $incompleteIngredient->id]) }}" method="post">
                @csrf
                @method('PUT')
                    <div class="w-full md:grid md:grid-cols-5">
                        <div class="flex mb-2">
                            <p class="font-bold md:hidden">食材名：
                            </p>
                            <p>{{ $incompleteIngredient->name }}</p>
                        </div>
                        <div class="text-start mb-2">
                            <label class="font-bold md:hidden">読み方：</label>
                            <input class="border rounded-md py-1 px-2 @error('name') border-error @enderror" type="text" id="reading-{{ $incompleteIngredient->id }}" name="reading" value="{{ old('reading', $incompleteIngredient->reading) }}">
                            @error('reading')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-start mb-2">
                            <label class="font-bold md:hidden">食材カテゴリー：</label>
                            <select class="border rounded-md py-1 px-2 cursor-pointer @error('name') border-error @enderror" name="category" id="category-{{ $incompleteIngredient->id }}">
                            <option value="">選択してください</option>
                            @foreach($categories as $category)
                            <option value="{{ $category}}">{{ $category }}</option>
                            @endforeach
                            </select>
                            @error('category')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-start mb-2">
                            <label class="font-bold md:hidden">アレルギーカテゴリー：</label>
                            <select class="border rounded-md py-1 px-2 cursor-pointer @error('name') border-error @enderror" name="allergy_categories[]" id="allergy-categories-{{ $incompleteIngredient->id }}" multiple>
                            <option value=""></option>
                            @foreach($allergyCategories as $allergyCategory)
                            <option value="{{ $allergyCategory->id }}">{{ $allergyCategory->category }}</option>
                            @endforeach
                            </select>
                            @error('allergy_categories')
                            <p class="text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="text-center bg-taupe-200 hover:shadow-md border border-accent text-accent px-4 py-2 md:my-4 rounded-md font-semibold cursor-pointer" type="submit">設定</button>
                    </div>
            </form>
            @endforeach
            @else
            <div>すべて設定済みです。登録が必要な食材はありません。</div>
            @endif
        </div>
        <div>
            <h3>食材リスト</h3>

            @foreach($categories as $category)
            <h4>{{ $category }}</h4>
            <table>
                <thead>
                    <tr>
                        <th>食材名</th>
                        <th>読み方</th>
                        <th>アレルギーカテゴリー</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ingredients->where('category', $category) as $ingredient)
                    <tr>
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
@endsection