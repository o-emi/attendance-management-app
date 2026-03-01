@extends('layouts.admin')

@section('title', '勤怠詳細(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/show.css')}}">
@endsection

@section('content')
<div class="attendance-detail">
    <h2 class="attendance-detail__title">勤怠詳細</h2>

    <form action="#" method="POST" class="attendance-detail__card">
        @csrf
        {{-- 名前行 --}}
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">名前</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text">{{ $attendance->user->name }}</span>
            </div>
        </div>

        {{-- 日付行 --}}
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">日付</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text--bold">2023年</span>
                <span class="attendance-detail__text--bold">6月1日</span>
            </div>
        </div>

        {{-- 出勤・退勤行 --}}
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">出勤・退勤</label>
            <div class="attendance-detail__content">
                <input type="text" class="attendance-detail__input" value="{{ $attendance->clock_in }}">
                <span class="attendance-detail__separator">〜</span>
                <input type="text" class="attendance-detail__input" value="{{ $attendance->clock_out }}">
            </div>
        </div>

        {{-- 休憩行 --}}
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">休憩</label>
            <div class="attendance-detail__content">
                <input type="text" class="attendance-detail__input" value="12:00">
                <span class="attendance-detail__separator">〜</span>
                <input type="text" class="attendance-detail__input" value="13:00">
            </div>
        </div>

        {{-- 備考行 --}}
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">備考</label>
            <div class="attendance-detail__content">
                <textarea class="attendance-detail__textarea"></textarea>
            </div>
        </div>

        {{-- ボタンエリア --}}
        <div class="attendance-detail__actions">
            <button type="submit" class="attendance-detail__submit-btn">修正</button>
        </div>
    </form>
</div>
@endsection