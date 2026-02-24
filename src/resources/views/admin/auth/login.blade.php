@extends('layouts.login_register')

@section('title', 'ログイン(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/auth/login.css')}}">
@endsection

@section('content')
<div class="admin-login-form">
    <h2 class="admin-login-form__heading content__heading">管理者ログイン</h2>
    <div class="admin-login-form__inner">
        <form class="admin-login-form__form" action="{{ route('login') }}" method="post" novalidate>
            @csrf
            <div class="admin-login-form__group">
                <label class="admin-login-form__label" for="email">メールアドレス</label>
                <input class="admin-login-form__input" type="email" name="email" value="{{ old('email') }}">
                <p class="admin-login-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="admin-login-form__group">
                <label class="admin-login-form__label" for="password">パスワード</label>
                <input class="admin-login-form__input" type="password" name="password" id="password">
                <p class="admin-login-form__error-message">
                    @if($errors->has('password') && $errors->first('password') !== 'パスワードと一致しません')
                        {{ $errors->first('password') }}
                    @endif
                </p>
            </div>

            <input class="admin-login-form__btn btn" type="submit" value="管理者ログインする">
        </form>
    </div>
</div>
@endsection