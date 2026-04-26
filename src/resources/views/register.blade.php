@extends('header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css')}}">
@endsection

@section('content')
<div class="register-page">
    <div class="register-body">
        <form method="POST" action="/register" novalidate>
            @csrf

            <div class="register-contents">
                <h1 class="member-registration">会員登録</h1>

                <div class="username-field">
                    <label>
                        ユーザー名
                        @error('name')
                            <span class="error">※{{ $message }}</span>
                        @enderror
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" class="username-input">
                </div>

                <div class="email-field">
                    <label>
                        メールアドレス
                        @error('email')
                            <span class="error">※{{ $message }}</span>
                        @enderror
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="email-input">
                </div>

                <div class="password-field">
                    <label>
                        パスワード
                        @error('password')
                            <span class="error">※{{ $message }}</span>
                        @enderror
                    </label>
                    <input type="password" name="password" value="{{ old('password') }}" class="password-input">
                </div>

                <div class="password-confirmation-field">
                    <label>
                        確認用パスワード
                        @error('password_confirmation')
                            <span class="error">※{{ $message }}</span>
                        @enderror
                    </label>
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="password-confirmation-input">
                </div>
            </div>

            <div class="form-button">
                <button type="submit" class="button-text">登録する</button>
            </div>
        </form>
    </div>


    <a class="login-page" href="/login">ログインはこちら</a>
</div>

@endsection