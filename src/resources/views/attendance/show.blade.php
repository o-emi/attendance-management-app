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

        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST" class="attendance-detail__form">
            @csrf
            @method('PUT')

            <div class="attendance-detail__table">
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
                            {{ $attendance->work_date->format('Y年') }}
                        </span>
                        <span class="attendance-detail__text--bold">
                            {{ $attendance->work_date->format('n月j日') }}
                        </span>
                    </div>
                </div>

                @include('attendance.partials.clock-row')

                @foreach($attendance->breakTimes as $index => $break)
                    @include('attendance.partials.break-row')
                @endforeach

                @include('attendance.partials.break-row-add')

                @include('attendance.partials.remark-row')

            </div>

            @if ($errors->any())
                <div class="attendance-detail__error-box">
                    @foreach ($errors->all() as $error)
                        <p class="attendance-detail__error-text">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

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