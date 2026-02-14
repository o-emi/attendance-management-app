@extends('layouts.login_register')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading content__heading">ログイン</h2>
    <div class="login-form__inner">
        <form class="login-form__form" action="{{ route('login') }}" method="post" novalidate>
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="email">メールアドレス</label>
                <input class="login-form__input" type="email" name="email" value="{{ old('email') }}">
                <p class="login-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" name="password" id="password">
                <p class="login-form__error-message">
                    @if($errors->has('password') && $errors->first('password') !== 'パスワードと一致しません')
                        {{ $errors->first('password') }}
                    @endif
                </p>
            </div>

            <input class="login-form__btn btn" type="submit" value="ログインする">
        </form>

        <div class="register-link-area">
            <a href="/register" class="register-link">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection