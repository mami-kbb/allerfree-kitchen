@extends('layouts.app')

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6 ">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-accent">ログイン</h1>
        <form class="flex flex-col items-center w-full max-w-md my-4" action="{{ route('login') }}" method="post" novalidate>
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
                    <input class="bg-white md:w-64 border rounded-md py-1 px-2" type="password" id="password" name="password">
                </div>
                @error('password')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-taupe-200 hover:bg-taupe-300 active:bg-taupe-400 text-accent px-4 py-2 border rounded-md font-semibold shadow-md my-4" type="submit">ログイン</button>
        </form>
        <p>
            <a class="text-blue-800 active:text-blue-900 hover:shadow-md" href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
        </p>
    </div>
</div>
@endsection