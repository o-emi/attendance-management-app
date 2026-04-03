@extends('layouts.admin')

@section('title', '勤怠詳細(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/show.css')}}">
@endsection

@section('content')
<div class="attendance-detail">
    <div class="attendance-detail__inner">
        <h2 class="attendance-detail__title">勤怠詳細</h2>

        @if(session('message'))
            <p class="attendance-detail__success">{{ session('message') }}</p>
        @endif

        <form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST" class="attendance-detail__form">
            @csrf
            @method('PUT')

            <div class="attendance-detail__group">
                <label class="attendance-detail__label">名前</label>
                <div class="attendance-detail__content">
                    <span class="attendance-detail__text">{{ $attendance->user->name }}</span>
                </div>
            </div>

            <div class="attendance-detail__group">
                <label class="attendance-detail__label">日付</label>
                <div class="attendance-detail__content">
                    <span class="attendance-detail__text--bold">{{ $attendance->work_date->format('Y年n月j日') }}</span>
                </div>
            </div>

            @include('attendance.partials.clock-row')

            @php
                $breakTimes = $attendance->status === '承認待ち' && $latestRequest
                    ? $latestRequest->breakTimes
                    : $attendance->breakTimes;
            @endphp

            @foreach($breakTimes as $index => $break)
                @include('attendance.partials.break-row', [
                    'index' => $index,
                    'break' => $break,
                    'isPending' => $attendance->status === '承認待ち'
                ])
            @endforeach

            @if($attendance->status !== '承認待ち')
                @include('attendance.partials.break-row-add', [
                    'index' => $breakTimes->count()
                ])
            @endif

            @include('attendance.partials.note-row')

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