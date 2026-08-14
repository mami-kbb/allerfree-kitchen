@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div>
    <h2>プロフィール設定</h2>
    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        <div>
            <div id="list">
                @if ($profile?->profile_image)
                <img src="{{ asset('storage/'. $profile->profile_image) }}" alt="ユーザーアイコン">
                @else
                <img src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
                @endif
            </div>
            <div >
                <label for="image">画像を選択する</label>
                <input type="file" id="image" name="profile_image" accept="image/png, image/jpeg" hidden>
                <div>
                    @error('profile_image')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <label for="name">ユーザー名</label>
        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
        <div>
            @error('name')
            {{ $message }}
            @enderror
        </div>

        <div>
            <label>アレルギー</label>
            <div>
                @php
                $selectedAllergies = old('allergy_user', $selectedAllergies);
                @endphp
                @foreach($allergies as $allergy)
                <input type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_user[]" {{ in_array($allergy->id, $selectedAllergies) ? 'checked' : '' }} >
                <label for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                @endforeach
            </div>
        </div>

        <label for="comment">自己PR</label>
        <textarea name="comment" id="comment">{{ old('comment', $profile->comment ?? '') }}</textarea>
        <div>
            @error('comment')
            {{ $message }}
            @enderror
        </div>
        <button type="submit">更新する</button>
    </form>
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
</script>
@endsection