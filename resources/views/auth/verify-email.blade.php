@extends('layouts.app')

@section('content')
<div>
    <p>
        登録していただいたメールアドレスに認証メールを送信しました。<br>
        メール認証を完了してください。
    </p>
    <a href="http://localhost:8025" target="_blank">認証はこちらから</a>
    <form action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection