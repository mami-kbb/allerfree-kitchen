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
    <header class="bg-primary relative">
        <div class="flex justify-between items-center m-auto p-2">
            <a class="text-4xl font-bold font-roboto text-accent" href="{{ route('recipes.list') }}">Allerfree Kitchen</a>
            @if( !in_array(Route::currentRouteName(), ['login', 'register']) )
            <ul id="menu" class="hidden gap-4 md:flex md:flex-row md:items-center md:gap-4">
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

            <button id="menu-button" class="md:hidden p-2 focus:outline-none">
                <span id="menu-icon" class="block w-6 h-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </span>
            </button>
            <ul id="mobile-menu" class="fixed top-12 right-0 w-64 bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden flex flex-col">
                @auth
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('recipe.create') }}">レシピ投稿</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="block w-full text-center text-lg">ログアウト</button>
                    </form>
                </li>
                @else
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('login') }}">ログイン</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('register') }}">新規登録</a></li>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        menuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('translate-x-full');
        
            if (mobileMenu.classList.contains('translate-x-full')) {
                menuIcon.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                `;
            } else {
                menuIcon.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"></path>
                </svg>
                `;
            }
        });
    })

</script>