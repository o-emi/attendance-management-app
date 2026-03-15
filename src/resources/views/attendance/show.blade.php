@extends('layouts.app')

@section('title','勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/layouts/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <div class="attendance-detail__inner">
        <h2 class="attendance-detail__title">勤怠詳細</h2>

        @if(session('message'))
        <p class="attendance-detail__success">
            {{ session('message') }}
        </p>
        @endif

        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="attendance-detail__form">
            @csrf
            @method('PUT')

            <div class="attendance-detail__table">
                {{-- 名前 --}}
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">名前</label>
                    <div class="attendance-detail__content">
                        <span class="attendance-detail__text">{{ $attendance->user->name }}</span>
                    </div>
                </div>

                {{-- 日付 --}}
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">日付</label>
                    <div class="attendance-detail__content">
                        <span class="attendance-detail__text--bold">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}
                        </span>
                        <span class="attendance-detail__text--bold">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('n月j日') }}
                        </span>
                    </div>
                </div>

                {{-- 出勤・退勤 --}}
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">出勤・退勤</label>
                    <div class="attendance-detail__content">
                        <div class="attendance-detail__input-row">
                            <input type="time" name="clock_in" class="attendance-detail__input"
                                value="{{ old('clock_in', $attendance->clock_in?->format('H:i')) }}"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                            <span class="attendance-detail__separator">〜</span>
                            <input type="time" name="clock_out" class="attendance-detail__input"
                                value="{{ old('clock_out', $attendance->clock_out?->format('H:i')) }}"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                        </div>
                        @error('clock_out')
                            <p class="attendance-detail__error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 休憩時間（ループ処理） --}}
                @foreach($attendance->breakTimes as $index => $break)
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">
                        休憩{{ $index > 0 ? $index + 1 : '' }}
                    </label>
                    <div class="attendance-detail__content">
                        <div class="attendance-detail__input-row">
                            <input type="time" name="break_start[]" class="attendance-detail__input"
                                value="{{ old('break_start.'.$index, $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '') }}"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                            <span class="attendance-detail__separator">〜</span>
                            <input type="time" name="break_end[]" class="attendance-detail__input"
                                value="{{ old('break_end.'.$index, $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '') }}"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- 追加の休憩入力 --}}
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">
                        休憩{{ $attendance->breakTimes->count() + 1 }}
                    </label>
                    <div class="attendance-detail__content">
                        <div class="attendance-detail__input-row">

                            <input type="time" name="break_start[]"     class="attendance-detail__input"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>

                            <span class="attendance-detail__separator">〜</span>

                            <input type="time" name="break_end[]" class="attendance-detail__input"
                                {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>

                        </div>
                    </div>
                </div>

                {{-- 備考 --}}
                <div class="attendance-detail__group">
                    <label class="attendance-detail__label">備考</label>
                    <div class="attendance-detail__content">
                        <textarea name="remark" class="attendance-detail__textarea"
                            {{ $attendance->status === '承認待ち' ? 'disabled' : '' }}>{{ old('remark', $attendance->remark) }}</textarea>
                        @error('remark')
                            <p class="attendance-detail__error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 修正ボタン（承認待ちでない場合のみ表示） --}}
            @if($attendance->status !== '承認待ち')
            <div class="attendance-detail__actions">
                <button type="submit" class="attendance-detail__submit-btn">修正</button>
            </div>
            @else
            <div class="attendance-detail__error">
                <p class="attendance-detail__error-text">承認待ちのため修正はできません。</p>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection