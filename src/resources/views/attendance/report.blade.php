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
                    <td class="attendance-report__cell">2025-12</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>

                <tr class="attendance-report__row">
                    <td class="attendance-report__cell">2026-01</td>
                    <td class="attendance-report__cell">120h 0m</td>
                    <td class="attendance-report__cell">0h 0m</td>
                </tr>
            </tbody>
        </table>
    </section>
</div>
@endsection