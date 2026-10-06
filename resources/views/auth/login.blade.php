@extends('layouts.app')

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6 ">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-accent my-2">ログイン</h1>
        <form class="flex flex-col items-center w-full max-w-md my-2" action="{{ route('login') }}" method="post" novalidate>
            @csrf
            <div class="my-4 w-full">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <label class="text-lg" for="email">メールアドレス：</label>
                    <input class="bg-white md:w-64 border rounded-md py-1 px-2" type="email" id="email" name="email" value="{{ old('email') }}">
                </div>
                @error('email')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-4 w-full">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <label class="text-lg" for="password">パスワード：</label>
                    <div class="relative md:w-64">
                        <input class="bg-white w-full border rounded-md py-1 px-2 pr-10" type="password" id="password" name="password">
                        <button type="button" class="password-toggle absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 hover:text-gray-700" aria-label="パスワードを表示" aria-pressed="false">
                            <!-- 目（表示する） -->
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <!-- 斜線入りの目（隠す） -->
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>
                @error('password')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-taupe-200 hover:bg-taupe-300 active:bg-taupe-400 text-accent px-4 py-2 border rounded-md font-semibold shadow-md my-2" type="submit">ログイン</button>
        </form>
        <form class="my-3" action="{{ route('login') }}" method="post">
            @csrf
            <input type="hidden" name="email" value="user2@example.com">
            <input type="hidden" name="password" value="password">
            <button class="bg-white hover:shadow-md border border-accent text-accent px-4 py-2 rounded-md font-semibold cursor-pointer" type="submit">
                デモユーザーでログイン
            </button>
        </form>
        <p class="flex flex-col gap-4 items-center">
            <a class="text-blue-800 active:text-blue-900 hover:shadow-md" href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
            <a class="text-blue-800 active:text-blue-900 hover:shadow-md" href="{{ route('admin.login') }}">管理者ログインはこちら</a>
        </p>
    </div>
</div>
@endsection