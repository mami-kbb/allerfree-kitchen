@extends('layouts.app')

@section('content')
<div class="bg-primary min-h-screen p-4 sm:p-6">
    <div class="rounded-2xl bg-white md:mx-6 px-4 py-6 md:px-6 md:py-10 text-center">
        <p class="my-6">
        登録していただいたメールアドレスに認証メールを送信しました。<br>
        メール認証を完了してください。
        </p>
        <form class="my-6" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button class="text-blue-600 cursor-pointer hover:shadow-md" type="submit">認証メールを再送する</button>
        </form>
    </div>

</div>
@endsection