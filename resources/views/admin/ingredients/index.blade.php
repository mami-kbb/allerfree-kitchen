@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-8">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">食材管理・設定</h2>
        <div>
            <h3>食材設定</h3>
            @if($incompleteIngredients->isNotEmpty())
            <p>以下の食材の設定を行ってください</p>
            <label>食材名</label>
            <label>読み方</label>
            <label>食材カテゴリー</label>
            <label>アレルギーカテゴリー</label>
            @foreach($incompleteIngredients as $incompleteIngredient)
            <form action="" method="post">
                @csrf
                @method('PUT')
                    <div>
                        <p>{{ $incompleteIngredient->name }}</p>
                        <input type="text" id="reading-{{ $incompleteIngredient->id }}" name="reading" value="{{ old('reading', $incompleteIngredient->reading) }}">
                        <select name="category" id="category-{{ $incompleteIngredient->id }}">
                            <option value="">選択してください</option>
                            @foreach($categories as $category)
                            <option value="{{ $category}}">{{ $category }}</option>
                            @endforeach
                        </select>
                        <select name="allergy_categories[]" id="allergy-categories-{{ $incompleteIngredient->id }}" multiple>
                            <option value="">選択してください</option>
                            @foreach($allergyCategories as $allergyCategory)
                            <option value="{{ $allergyCategory->id }}">{{ $allergyCategory->category }}</option>
                            @endforeach
                        </select>
                        <button type="submit">設定</button>
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