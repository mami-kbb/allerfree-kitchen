<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allerfree Kitchen</title>
</head>
<body>
    <header>
        <div>
            <a href="{{ route('recipes.list') }}">Allerfree Kitchen</a>
            <div>@yield('nav')</div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>