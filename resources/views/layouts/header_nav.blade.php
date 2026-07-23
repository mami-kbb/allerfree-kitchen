<form action="{{ route('logout') }}" method="post">
    @csrf
    <button>ログアウト</button>
</form>