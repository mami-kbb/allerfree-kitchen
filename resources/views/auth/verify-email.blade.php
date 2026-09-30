@extends('layouts.app')

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10 text-center">
        <p class="my-6">
        登録していただいたメールアドレスに認証メールを送信しました。<br>
        メール認証を完了してください。
        </p>
        <p class="text-sm text-secondary my-2">
            ※このデモ環境では、メール送信の制限により認証メールが届きません。<br>
            新規登録しても、投稿・お気に入り・コメントなどは利用できませんので、<BR>
            お試しの際は<a class="underline text-blue-800" href="{{ route('login') }}">ログイン画面</a>の「デモユーザーでログイン」をご利用ください。
        </p>
        <form class="my-6" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button class="text-blue-600 cursor-pointer hover:shadow-md" type="submit">認証メールを再送する</button>
        </form>
    </div>

</div>
@endsection