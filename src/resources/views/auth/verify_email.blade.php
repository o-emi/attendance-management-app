@extends('layouts.login_register')

@section('title', 'メール認証誘導画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css')}}">
@endsection

@section('content')
<div class="verify-email-form">
    <div class="verify-email-form__box">
        <p class="verify-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        @if (app()->environment('local'))
            <div class="verify-email-form__action">
                <a href="http://localhost:8025" target="_blank" class="btn-verify">
                認証はこちらから
                </a>
            </div>
        @endif

        @if (session('message'))
            <p class="success-message">
                {{ session('message') }}
            </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
            @csrf
            <button type="submit" class="btn-resend">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection