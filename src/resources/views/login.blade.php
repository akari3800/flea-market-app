@extends('header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css')}}">
@endsection

@section('content')
<div class="login-page">
    <div class="login-area">
        <h1 class="login-title">ログイン</h1>

        <form action="/login" method="POST">
            @csrf

            <div class="email-field">
                <label>
                    メールアドレス
                    @error('email')
                        <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="text" name="email">
            </div>

            <div class="password-field">
                <label>
                    パスワード
                    @error('password')
                        <span class="error">※{{ $message }}</span>
                    @enderror
                </label>
                <input type="password" name="password">
            </div>

            <div class="login-button">
                <button type="submit" class="button-text">
                ログインする
                </button>
            </div>

            <a class="register-page" href="/register">会員登録はこちら</a>
        </form>
    </div>
</div>

@endsection