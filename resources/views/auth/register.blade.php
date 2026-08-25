@extends('layouts.app')

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-accent">会員登録</h1>
        <form class="flex flex-col items-center gap-3 w-full max-w-md my-4" method="post" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="w-full">
                <div class="flex justify-between items-center">
                    <label class="text-lg" for="name">ユーザー名：</label>
                    <input class="border rounded-md bg-white w-64 py-1 px-2" type="text" id="name" name="name" value="{{ old('name') }}">
                </div>
                @error('name')
                    <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full">
                <div class="flex justify-between items-center">
                    <label class="text-lg" for="email">メールアドレス：</label>
                    <input class="bg-white border rounded-md w-64 py-1 px-2" type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
                @error('email')
                    <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full">
                <div class="flex justify-between items-center">
                    <label class="text-lg" for="password">パスワード：</label>
                    <input class="bg-white border rounded-md w-64 py-1 px-2" type="password" id="password" name="password" required>
                </div>
                @error('password')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full flex justify-between items-center">
                <label class="text-lg" for="password_confirmation">パスワード（確認）：</label>
                <input class="bg-white border rounded-md w-64 py-1 px-2" type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button class="bg-taupe-200 hover:bg-taupe-300 active:bg-taupe-400 text-accent px-4 py-2 border rounded-md font-semibold shadow-md my-4" type="submit">登録する</button>
        </form>

        <p>
            <a class="text-blue-800 active:text-blue-900 hover:shadow-md" href="{{ route('login') }}">ログインはこちら</a>
        </p>
    </div>

</div>
@endsection