@extends('layouts.app')

@section('title', '勤怠登録画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance">
    <div class="attendance__inner">

        <div class="punch-panel">

            <div class="punch-panel__status">
                {{-- 勤務状況に応じて --off-duty / --on-duty を切り替え --}}
                <span class="punch-panel__badge punch-panel__badge--off-duty">勤務外</span>
            </div>

            <div class="punch-panel__date">
                <p class="punch-panel__date-text">
                    {{ \Carbon\Carbon::now()->isoFormat('YYYY年M月D日(ddd)') }}
                </p>
            </div>

            <div class="punch-panel__time">
                <p id="current-time" class="punch-panel__time-text">08:00</p>
            </div>

            <div class="punch-panel__actions">
                <form action="{{ route('attendance.punch') }}" method="POST" class="punch-panel__form">
                    @csrf
                    {{-- ボタン文言も状況に合わせて「出勤」「退勤」に --}}
                    <button type="submit" class="punch-panel__button">出勤</button>
                </form>
            </div>
            
        </div>

    </div>
</div>
@endsection