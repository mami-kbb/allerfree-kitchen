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
    <header>
        <div class="bg-primary m-auto p-2">
            <a class="text-4xl font-bold font-roboto text-accent" href="{{ route('recipes.list') }}">Allerfree Kitchen</a>
            <div>@yield('nav')</div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>