<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&display=swap" rel="stylesheet">
    <title>Allerfree Kitchen</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="bg-primary">
        <div class="flex justify-between m-auto p-2">
            <a class="text-4xl font-bold font-roboto text-accent" href="{{ route('recipes.list') }}">Allerfree Kitchen</a>
            @if( !in_array(Route::currentRouteName(), ['login', 'register']) )
            <ul class="flex justify-end items-center gap-4 m-4">
            @auth
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md" href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md" href="{{ route('recipe.create') }}">レシピ投稿</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md">ログアウト</button>
                    </form>
                </li>
                @else
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md " href="{{ route('login') }}">ログイン</a></li>
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md" href="{{ route('register') }}">新規登録</a></li>
                @endauth
            </ul>
            @endif
        </div>
        <div>@yield('nav')</div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>