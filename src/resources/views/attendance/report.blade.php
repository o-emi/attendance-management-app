@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/report.css') }}">
@endsection

@section('content')
<div class="attendance-report">
    <h1 class="attendance-report__title">マイ勤怠レポート</h1>

    <p class="attendance-report__description">
        過去６ヶ月の勤怠データから集計しています。
    </p>

    <section class="attendance-report__section">
        <h2 class="attendance-report__section-title">基本サマリー</h2>

        <div class="attendance-report__summary-list">
            <div class="attendance-report__summary-card">
                <p class="attendance-report__summary-label">総労働時間</p>
                <p class="attendance-report__summary-value">744h 0m</p>
            </div>

            <div class="attendance-report__summary-card">
                <p class="attendance-report__summary-label">総残業時間</p>
                <p class="attendance-report__summary-value">10h 0m</p>
            </div>

            <div class="attendance-report__summary-card">
                <p class="attendance-report__summary-label">平均労働時間 / 日</p>
                <p class="attendance-report__summary-value">8h 5m</p>
            </div>
        </div>
    </section>
</div>
@endsection