@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('body-class', 'login-page')

@section('content')
<div class="auth-card">
    <h1>ログイン</h1>
    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label>メールアドレス</label>

            @error('email')
            <div style="color:red;">{{ $message }}</div>
            @enderror
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>パスワード</label>

            @error('password')
            <div style="color:red;">{{ $message }}</div>
            @enderror
            <input type="password" name="password">
        </div>

        <button type="submit">ログインする</button>
    </form>
    <p class="auth-switch">
        <a href="/register">会員登録はこちら</a>
    </p>
</div>
@endsection