<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
    <div>
        <h1>ログイン</h1>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password">
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">ログイン</button>
        </form>
        <p class="link">
            <a href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
        </p>
    </div>
</body>
</html>