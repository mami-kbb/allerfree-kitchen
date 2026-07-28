<nav>
    <ul>
        @auth
        <li><a href="{{ route('profile', ['user_id' => auth()->id()]) }}">マイページ</a></li>
        <li><a href="{{ route('recipe.create') }}">レシピ投稿</a></li>
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button>ログアウト</button>
            </form>
        </li>
        @else
        <li><a href="{{ route('login') }}">ログイン</a></li>
        @endauth
    </ul>
</nav>

