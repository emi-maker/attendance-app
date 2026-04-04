@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('body-class', 'admin-page')

@section('content')
<div class="auth-card">
    <h1>管理者ログイン</h1>
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

        <button type="submit">管理者ログインする</button>
    </form>
</div>
@endsection