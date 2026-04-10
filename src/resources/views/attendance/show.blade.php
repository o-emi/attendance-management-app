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

        @php
            $latestRequest = $attendance->correctionRequests()->latest()->first();

            $isPending = $latestRequest && $latestRequest->status === 'pending';

            $displayBreaks = $isPending
                ? $latestRequest->breakTimes
                : $attendance->breakTimes;

            $displayNote = $isPending
                ? ($latestRequest->note ?? '')
                : ($attendance->remark ?? '');
        @endphp

        <form action="{{ route('attendance.request', $attendance->id) }}" method="POST" class="attendance-detail__form">
            @csrf

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
                        <span class="attendance-detail__text--bold">{{ $attendance->work_date->format('Y年') }}</span>
                        <span class="attendance-detail__text--bold">{{ $attendance->work_date->format('n月j日') }}</span>
                    </div>
                </div>

                @include('attendance.partials.clock-row', [
                    'isPending' => $isPending,
                    'latestRequest' => $latestRequest
                ])

                @foreach($displayBreaks as $index => $break)
                    @include('attendance.partials.break-row', [
                        'index' => $index,
                        'break' => $break,
                        'isPending' => $isPending
                    ])
                @endforeach

                @if(!$isPending)
                    @include('attendance.partials.break-row-add', [
                        'index' => $displayBreaks->count(),
                        'isPending' => $isPending
                    ])
                @endif

                @include('attendance.partials.note-row', [
                    'note' => $displayNote,
                    'isPending' => $isPending
                ])
            </div>

            @if(!$isPending)
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