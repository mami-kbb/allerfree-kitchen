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
            <a class="text-4xl font-bold font-roboto text-accent" href="{{ Auth::check() && Auth::user()->isAdmin()  ? route('admin.recipe') : route('recipes.list') }}">Allerfree Kitchen</a>
            @if( !in_array(Route::currentRouteName(), ['login', 'register', 'admin.login']) )
            <ul id="menu" class="hidden gap-4 md:flex md:flex-row md:items-center md:gap-4">
                <li>
                    <button type="button" data-about-open class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer">このアプリについて</button>
                </li>
                @auth
                @if (Auth::user()->isAdmin())
                <li>
                    <a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer" href="{{ route('ingredients.list') }}">食材管理</a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="post">
                        @csrf
                        <button class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer">ログアウト</button>
                    </form>
                </li>
                @elseif (Auth::user()->isUser())
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer" href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer" href="{{ route('recipe.create') }}">レシピ投稿</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer">ログアウト</button>
                    </form>
                </li>
                @endif
                @endauth
                @guest
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer" href="{{ route('login') }}">ログイン</a></li>
                <li><a class="border border-taupe-200 bg-white rounded-md px-4 py-2 hover:shadow-md cursor-pointer" href="{{ route('register') }}">新規登録</a></li>
                @endguest
            </ul>

            <button id="menu-button" class="md:hidden p-2 focus:outline-none">
                <span id="menu-icon" class="block w-6 h-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </span>
            </button>
            <ul id="mobile-menu" class="fixed top-12 right-0 w-64 bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden flex flex-col">
                <li class="flex-1 flex items-center justify-center border-b py-4">
                    <button type="button" data-about-open class="block w-full text-center text-lg cursor-pointer">このアプリについて</button>
                </li>
                @auth
                @if (Auth::user()->isAdmin())
                <li class="flex-1 flex items-center justify-center border-b py-4">
                    <a class="block w-full text-center text-lg cursor-pointer" href="{{ route('ingredients.list') }}">食材管理</a>
                </li>
                <li class="flex-1 flex items-center justify-center border-b py-4">
                    <form action="{{ route('admin.logout') }}" method="post">
                        @csrf
                        <button type="submit" class="block w-full text-center text-lg cursor-pointer">ログアウト</button>
                    </form>
                </li>
                @elseif (Auth::user()->isUser())
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg cursor-pointer" href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg cursor-pointer" href="{{ route('recipe.create') }}">レシピ投稿</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="block w-full text-center text-lg cursor-pointer">ログアウト</button>
                    </form>
                </li>
                @endif
                @endauth
                @guest
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('login') }}">ログイン</a></li>
                <li class="flex-1 flex items-center justify-center border-b py-4"><a class="block w-full text-center text-lg" href="{{ route('register') }}">新規登録</a></li>
                @endguest
            </ul>
            @endif
        </div>
        @if(in_array(Route::currentRouteName(), ['recipe.list']) )
        <p>本アプリは除外したいアレルギーを指定してレシピ検索を簡単に行えるアプリです。検索方法はこちらをクリック</p>
        @endif
        <div>@yield('nav')</div>
    </header>
    <main>
        @yield('content')
    </main>

    <div id="aboutModal"
         class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4"
         role="dialog" aria-modal="true" aria-labelledby="aboutModalTitle">
        <div class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 md:p-8">
            <button type="button" data-about-close
                    class="absolute top-3 right-4 text-2xl leading-none text-secondary hover:text-accent cursor-pointer"
                    aria-label="閉じる">&times;</button>

            <h2 id="aboutModalTitle" class="mb-4 text-xl font-bold text-accent">Allerfree Kitchen について</h2>

            <p class="mb-4 text-sm md:text-base">
                食物アレルギーを持つ子供を育てる親御さん向けの、レシピ投稿・検索サイトです。
                投稿時にアレルギー品目の申告が必須で、管理者の承認を通ったレシピだけが公開されます。
            </p>

            <h3 class="mb-2 font-bold text-accent">できる検索</h3>
            <ul class="mb-4 list-disc space-y-1 pl-5 text-sm md:text-base">
                <li><span class="font-semibold">キーワード</span>：レシピ名・食材名で探す</li>
                <li><span class="font-semibold">除外する食材</span>：「卵 牛乳」のようにスペース区切りで指定</li>
                <li><span class="font-semibold">除外アレルギー</span>：食品表示法の29品目から選択</li>
                <li><span class="font-semibold">除外アレルギーカテゴリー</span>：肉類・穀物など大まかな分類で除外</li>
            </ul>

            <div class="mb-4 rounded-xl bg-taupe-100 px-4 py-3 text-sm">
                <span class="font-bold">使い方のコツ：</span>
                画面上部の検索バーをクリックすると、除外条件の設定が開きます。
                ログイン中は、マイページで登録したアレルギーが自動で除外されます。
            </div>

            <p class="text-xs text-orange-900">
                ※レシピに登録された食材情報をもとに検索しています。調味料や加工食品の原材料までは判定対象外です。必ず商品表示をご確認ください。
            </p>

            <div class="mt-6 text-center">
                <button type="button" data-about-close
                        class="rounded-md border border-accent bg-taupe-200 px-6 py-2 font-semibold text-accent hover:shadow-md cursor-pointer">閉じる</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        /* ===== ハンバーガーメニュー ===== */
        const menuButton = document.getElementById('menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        // ログイン画面などではメニュー自体が出力されないため、存在チェックが必要
        if (menuButton && mobileMenu && menuIcon) {
            const barsIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>';
            const closeIcon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"></path></svg>';

            menuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('translate-x-full');
                menuIcon.innerHTML = mobileMenu.classList.contains('translate-x-full') ? barsIcon : closeIcon;
            });
        }

        /* ===== このアプリについて モーダル ===== */
        const aboutModal = document.getElementById('aboutModal');
        let lastFocused = null;

        function openAbout() {
            lastFocused = document.activeElement;

            // モバイルメニューが開いていたら先に閉じる（既存のクリック処理を再利用）
            if (menuButton && mobileMenu && !mobileMenu.classList.contains('translate-x-full')) {
                menuButton.click();
            }

            aboutModal.classList.remove('hidden');
            aboutModal.classList.add('flex');
            document.body.classList.add('overflow-hidden'); // 背面のスクロールを止める
            aboutModal.querySelector('[data-about-close]').focus();
        }

        function closeAbout() {
            aboutModal.classList.add('hidden');
            aboutModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            if (lastFocused) lastFocused.focus(); // 開く前のボタンにフォーカスを戻す
        }

        if (aboutModal) {
            // 「開く」ボタンはいくつあってもまとめて拾う（イベント委譲）
            document.addEventListener('click', function (e) {
                if (e.target.closest('[data-about-open]')) {
                    openAbout();
                }
            });

            // 「×」「閉じる」ボタン
            aboutModal.querySelectorAll('[data-about-close]').forEach(function (btn) {
                btn.addEventListener('click', closeAbout);
            });

            // 背景（暗い部分）クリックで閉じる
            aboutModal.addEventListener('click', function (e) {
                if (e.target === aboutModal) closeAbout();
            });

            // Escキーで閉じる
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !aboutModal.classList.contains('hidden')) {
                    closeAbout();
                }
            });
        }
    });
    </script>
</body>
</html>

