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
                <p class="attendance-report__summary-value">{{ $totalHours }}h {{ $totalMinutes }}m</p>
            </div>

            <div class="attendance-report__summary-card">
                <p class="attendance-report__summary-label">総残業時間</p>
                <p class="attendance-report__summary-value">{{ $totalOvertimeHours }}h {{ $totalOvertimeMinutes }}m</p>
            </div>

            <div class="attendance-report__summary-card">
                <p class="attendance-report__summary-label">平均労働時間 / 日</p>
                <p class="attendance-report__summary-value">8h 5m</p>
            </div>
        </div>
    </section>

    <section class="attendance-report__section">
        <h2 class="attendance-report__section-title">
            月次推移（過去6ヶ月）
        </h2>

        <table class="attendance-report__table">
            <thead>
                <tr class="attendance-report__row">
                    <th class="attendance-report__header">月</th>
                    <th class="attendance-report__header">労働時間</th>
                    <th class="attendance-report__header">残業時間</th>
                </tr>
            </thead>

            <tbody>
                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-02</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>

                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-03</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>

                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-04</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>

                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-05</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>

                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-06</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="attendance-report__section">
        <h2 class="attendance-report__section-title">今月の異常検知</h2>

        <p class="attendance-report__standard">
            基準: 始業09:00 / 終業18:00 / 長時間労働は1日10時間超
        </p>

        <div class="attendance-report__anomaly-list">
            <div class="attendance-report__anomaly-card">
                <p class="attendance-report__anomaly-label">遅刻回数</p>
                <p class="attendance-report__anomaly-value">2回</p>
            </div>

            <div class="attendance-report__anomaly-card">
                <p class="attendance-report__anomaly-label">早退回数</p>
                <p class="attendance-report__anomaly-value">1回</p>
            </div>

            <div class="attendance-report__anomaly-card">
                <p class="attendance-report__anomaly-label">長時間労働日数</p>
                <p class="attendance-report__anomaly-value">1日</p>
            </div>
        </div>
    </section>
</div>
@endsection