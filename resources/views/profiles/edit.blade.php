@extends('layouts.app')

@section('nav')
@include('layouts.header_nav')
@endsection

@section('content')
<div class="bg-primary min-h-screen md:pb-6">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:py-10 md:px-10">
        <h2 class="text-center text-2xl font-bold text-accent mb-10">プロフィール設定</h2>
        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="md:flex gap-6">
                <div class="md:w-1/3">
                    <div class="flex items-center gap-4 md:flex-col md:justify-center" id="list">
                        @if ($profile?->profile_image)
                        <img class="block shrink-0 w-48 h-48 md:w-64 md:h-64 rounded-full object-cover" src="{{ asset('storage/'. $profile->profile_image) }}" alt="ユーザーアイコン">
                        @else
                        <img class="block shrink-0 w-48 h-48 md:w-64 md:h-64 rounded-full object-cover" src="{{ asset('/images/icon.png') }}" alt="ユーザーアイコン">
                        @endif
                        <label class="block w-48 text-center bg-white hover:shadow-md border border-accent text-accent px-4 py-2 ml-auto my-4 rounded-md font-semibold cursor-pointer" for="image">画像を選択する</label>
                        <input type="file" id="image" name="profile_image" accept="image/png, image/jpeg" hidden>
                        @error('profile_image')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex-1">
                    <div class="my-2 md:my-4">
                        <label class="font-semibold md:text-lg" for="name">ユーザー名：</label>
                        <input class="border rounded-md  py-1 px-2" type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                        @error('name')
                        <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="my-2 md:my-4">
                        <label class="font-semibold md:text-lg">アレルギー：</label>
                        <div>
                            @php
                            $selectedAllergies = old('allergy_user', $selectedAllergies);
                            @endphp
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($allergies as $allergy)
                                <input class="peer sr-only allergy-checkbox" type="checkbox" id="allergy_{{ $allergy->id }}" value="{{ $allergy->id }}" name="allergy_user[]" {{ in_array($allergy->id, $selectedAllergies) ? 'checked' : '' }} >
                                <label class="border border-gray-300 bg-white rounded-full px-3 py-1 text-sm cursor-pointer peer-checked:bg-taupe-300" for="allergy_{{ $allergy->id }}">{{ $allergy->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <label class="font-semibold md:text-lg" for="comment">自己PR：</label>
                    <textarea class="my-2 border rounded-2xl w-full min-h-24 px-3 py-2 resize-y" name="comment" id="comment">{{ old('comment', $profile->comment ?? '') }}</textarea>
                    @error('comment')
                    <p class="text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="text-center">
                <button class="w-48 text-center bg-taupe-200 hover:bg-taupe-300 hover:shadow-md border border-accent text-accent px-4 py-2 my-4 rounded-md font-semibold cursor-pointer" type="submit">更新する</button>
            </div>
        </form>
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
</script>
@endsection