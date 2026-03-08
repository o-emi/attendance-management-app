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

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">名前</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text">{{ $attendance->user->name }}</span>
            </div>
        </div>

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">日付</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text--bold">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('Y年') }}</span>
                <span class="attendance-detail__text--bold">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('n月j日') }}</span>
            </div>
        </div>

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">出勤・退勤</label>
            <div class="attendance-detail__content">
                <input type="time"
                        name="clock_in"
                        class="attendance-detail__input"
                        value="{{ $attendance->clock_in?->format('H:i') }}">

                <span class="attendance-detail__separator">〜</span>

                <input type="time"
                    name="clock_out"
                    class="attendance-detail__input"
                    value="{{ $attendance->clock_out?->format('H:i') }}">
            </div>
        </div>

                @foreach($attendance->breakTimes as $index => $break)
                    <div class="attendance-detail__group">
                        <label class="attendance-detail__label">
                            休憩{{ $index > 0 ? $index + 1 : '' }}
                        </label>

                        <div class="attendance-detail__content">
                            <input type="text"
                                class="attendance-detail__input"
                                value="{{ \Carbon\Carbon::parse($break->break_start)->format('H:i') }}">

                            <span class="attendance-detail__separator">〜</span>

                            <input type="text"
                                class="attendance-detail__input"
                                value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                        </div>
                    </div>
                @endforeach

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">備考</label>
            <div class="attendance-detail__content">
                <textarea class="attendance-detail__textarea"></textarea>
            </div>
        </div>

        <div class="attendance-detail__actions">
            <button type="submit" class="attendance-detail__submit-btn">修正</button>
        </div>

    </form>
</div>
@endsection