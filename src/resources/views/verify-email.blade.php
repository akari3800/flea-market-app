@extends('header')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css')}}">
@endsection

@section('content')
<div class="verify-page">

    <p class="verify-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a href="http://localhost:8025" target="_blank" class="verify-button">
        認証はこちらから
    </a>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">
        認証メールを再送する
        </button>
    </form>

</div>

@endsection