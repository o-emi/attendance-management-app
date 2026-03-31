@extends('layouts.admin')

@section('title', 'スタッフ別勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-detail">
    <div class="attendance-detail__inner">
        <h2 class="attendance-detail__title">{{ $user->name }}さんの勤怠</h2>

        @php
            $current = \Carbon\Carbon::parse($currentMonth . '-01');
            $prevMonth = $current->copy()->subMonth()->format('Y-m');
            $nextMonth = $current->copy()->addMonth()->format('Y-m');
        @endphp

        <div class="attendance-detail__nav">
            <a
                href="{{ route('admin.staff.attendance', ['id' => $user->id, 'month' => $prevMonth]) }}"
                class="attendance-detail__nav-link attendance-detail__nav-link--prev">
                ← 前月
            </a>

            <div class="attendance-detail__current-month">
                <img src="{{ asset('images/icon/calendar.png') }}" alt="" class="attendance-detail__calendar-icon">
                <span class="attendance-detail__month-text">
                    {{ \Carbon\Carbon::parse($currentMonth . '-01')->format('Y/m') }}
                </span>
            </div>

            <a
                href="{{ route('admin.staff.attendance', ['id' => $user->id, 'month' => $nextMonth]) }}"
                class="attendance-detail__nav-link attendance-detail__nav-link--next">
                翌月 →
            </a>
        </div>

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
                        <td class="attendance-detail__table-item">{{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d(D)') }}
                        </td>

                        <td class="attendance-detail__table-item">{{ $attendance->clock_in ?? '' }}
                        </td>

                        <td class="attendance-detail__table-item">{{ $attendance->clock_out ?? '' }}
                        </td>

                        <td class="attendance-detail__table-item">{{ $attendance->break_time ?? '' }}
                        </td>

                        <td class="attendance-detail__table-item">{{ $attendance->total_work_time ?? '' }}
                        </td>

                        <td class="attendance-detail__table-item">
                            <a href="{{ route('admin.attendance.show', $attendance->id) }}"
                            class="attendance-detail__link">
                                詳細
                            </a>
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