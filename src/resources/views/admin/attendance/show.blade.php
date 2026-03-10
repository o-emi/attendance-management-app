@extends('layouts.admin')

@section('title', '勤怠詳細(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/show.css')}}">
@endsection

@section('content')
<div class="attendance-detail">
    <h2 class="attendance-detail__title">勤怠詳細</h2>

    <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST" class="attendance-detail__card">
        @csrf
        @method('PUT')

        @if($attendance->status === '承認待ち')
            <div class="attendance-detail__error">
                <p class="attendance-detail__error-text">
                    承認待ちのため修正はできません。
                </p>
            </div>
        @endif

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">名前</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text">{{ $attendance->user->name }}</span>
            </div>
        </div>

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">日付</label>
            <div class="attendance-detail__content">
                <span class="attendance-detail__text--bold">
                    {{ \Carbon\Carbon::parse($attendance->clock_in)->format('Y年') }}
                </span>
                <span class="attendance-detail__text--bold">
                    {{ \Carbon\Carbon::parse($attendance->clock_in)->format('n月j日') }}
                </span>
            </div>
        </div>

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">出勤・退勤</label>

            <div class="attendance-detail__content">

                <input type="time"
                    name="clock_in"
                    class="attendance-detail__input"
                    value="{{ old('clock_in', $attendance->clock_in?->format('H:i')) }}"
                    {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>

                <span class="attendance-detail__separator">〜</span>

                <input type="time"
                    name="clock_out"
                    class="attendance-detail__input"
                    value="{{ old('clock_out', $attendance->clock_out?->format('H:i')) }}"
                    {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
            </div>
        </div>

        @error('clock_time')
        <p class="attendance-detail__error-text">{{ $message }}</p>
        @enderror

        @foreach($attendance->breakTimes as $index => $break)
        <div class="attendance-detail__group">

            <label class="attendance-detail__label">
                休憩{{ $index > 0 ? $index + 1 : '' }}
            </label>

            <div class="attendance-detail__content">

                <input type="time"
                    name="break_start[]"
                    class="attendance-detail__input"
                    value="{{ old('break_start.'.$index, \Carbon\Carbon::parse($break->break_start)->format('H:i')) }}"
                    {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>

                <span class="attendance-detail__separator">〜</span>

                <input type="time"
                    name="break_end[]"
                    class="attendance-detail__input"
                    value="{{ old('break_end.'.$index, $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '') }}"
                    {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
            </div>
        </div>
        @endforeach

        @error('break_time')
            <p class="attendance-detail__error-text">{{ $message }}</p>
        @enderror

        <div class="attendance-detail__group">
            <label class="attendance-detail__label">備考</label>

            <div class="attendance-detail__content">
                <textarea
                    name="remark"
                    class="attendance-detail__textarea"
                    {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}
                >{{ old('remark', $attendance->remark) }}</textarea>
            </div>
        </div>

        @if($attendance->status !== '承認待ち')
        <div class="attendance-detail__actions">
            <button type="submit" class="attendance-detail__submit-btn">修正</button>
        </div>
        @endif

    </form>
</div>
@endsection