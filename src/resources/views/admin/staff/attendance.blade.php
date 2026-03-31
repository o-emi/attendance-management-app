@extends('layouts.admin')

@section('title', 'スタッフ別勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <div class="attendance-detail__inner">
        <h2 class="attendance-detail__title">西玲奈さんの勤怠</h2>

        {{-- ページネーション・日付選択 --}}
        <div class="attendance-detail__nav">
            <a href="#" class="attendance-detail__nav-link attendance-detail__nav-link--prev">← 前月</a>
            <div class="attendance-detail__current-month">
                <img src="{{ asset('images/icon/calendar.png') }}" alt="" class="attendance-detail__calendar-icon">
                <span class="attendance-detail__month-text">2023/06</span>
            </div>
            <a href="#" class="attendance-detail__nav-link attendance-detail__nav-link--next">翌月 →</a>
        </div>

        {{-- 勤怠テーブル --}}
        <div class="attendance-detail__table-wrapper">
            <table class="attendance-detail__table">
                <thead>
                    <tr class="attendance-detail__table-row">
                        <th class="attendance-detail__table-header">日付</th>
                        <th class="attendance-detail__table-header">出勤</th>
                        <th class="attendance-detail__table-header">退勤</th>
                        <th class="attendance-detail__table-header">休憩</th>
                        <th class="attendance-detail__table-header">合計</th>
                        <th class="attendance-detail__table-header">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance) {{-- 仮の変数名です --}}
                    <tr class="attendance-detail__table-row">
                        <td class="attendance-detail__table-item">{{ $attendance->date }}</td>
                        <td class="attendance-detail__table-item">{{ $attendance->start_time }}</td>
                        <td class="attendance-detail__table-item">{{ $attendance->end_time }}</td>
                        <td class="attendance-detail__table-item">{{ $attendance->break_time }}</td>
                        <td class="attendance-detail__table-item">{{ $attendance->total_time }}</td>
                        <td class="attendance-detail__table-item">
                            <a href="#" class="attendance-detail__link">詳細</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- CSV出力ボタン --}}
        <div class="attendance-detail__action">
            <button type="button" class="attendance-detail__csv-btn">CSV出力</button>
        </div>
    </div>
</div>
@endsection