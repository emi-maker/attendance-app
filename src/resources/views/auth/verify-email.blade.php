@extends('layouts.app')

@section('content')

<div class="verify-container">

    <p class="verify-text">
        登録していただいたメールアドレスに認証メールを送信しました。<br>
        メール認証を完了してください。
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="verify-button">
            認証はこちらから
        </button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="resend-link">
            認証メールを再送する
        </button>
    </form>

</div>

@endsection