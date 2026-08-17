<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allerfree Kitchen</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <div>
            <a class="text-4xl font-bold text-blue-500" href="{{ route('recipes.list') }}">Allerfree Kitchen</a>
            <div>@yield('nav')</div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>