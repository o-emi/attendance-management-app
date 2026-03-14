@extends('layouts.app')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/layouts/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance/list.css') }}">
@endsection

@section('content')
<div class="attendance">

    <h2 class="attendance__title">勤怠一覧</h2>

    <div class="attendance__nav month-selector">

        <a href="{{ route('attendance.list',
            ['month' => $month->copy()->subMonth()->format('Y-m')]) }}"
            class="month-selector__link month-selector__link--prev">
            ← 前月
        </a>

        <div class="month-selector__current">
            <span class="month-selector__icon">📅</span>
                {{ $month->format('Y/m') }}
        </div>

        <a href="{{ route('attendance.list',
            ['month' => $month->copy()->addMonth()->format('Y-m')]) }}"
            class="month-selector__link month-selector__link--next">
            翌月 →
        </a>

    </div>

    <div class="attendance__table-wrapper">

        <table class="attendance-table">

            <thead class="attendance-table__head">
                <tr class="attendance-table__row">
                    <th class="attendance-table__header">日付</th>
                    <th class="attendance-table__header">出勤</th>
                    <th class="attendance-table__header">退勤</th>
                    <th class="attendance-table__header">休憩</th>
                    <th class="attendance-table__header">合計</th>
                    <th class="attendance-table__header">詳細</th>
                </tr>
            </thead>

            <tbody class="attendance-table__body">

            @foreach($attendances as $attendance)

            @php
                $breakTotal = 0;

                foreach ($attendance->breakTimes as $break) {
                    if ($break->break_start && $break->break_end) {
                        $breakTotal += \Carbon\Carbon::parse($break->break_start)
                            ->diffInMinutes(\Carbon\Carbon::parse($break->break_end));
                    }
                }

                $workTotal = 0;

                if ($attendance->clock_in && $attendance->clock_out) {
                    $workTotal = \Carbon\Carbon::parse($attendance->clock_in)
                        ->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) - $breakTotal;
                }

                $breakHour = floor($breakTotal / 60);
                $breakMinute = $breakTotal % 60;

                $workHour = floor($workTotal / 60);
                $workMinute = $workTotal % 60;
            @endphp

            <tr class="attendance-table__row">

            <td class="attendance-table__item">
            {{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d(D)') }}
            </td>

            <td class="attendance-table__item">
            {{ optional($attendance->clock_in)->format('H:i') }}
            </td>

            <td class="attendance-table__item">
            {{ optional($attendance->clock_out)->format('H:i') }}
            </td>

            <td class="attendance-table__item">
            {{ sprintf('%d:%02d', $breakHour, $breakMinute) }}
            </td>

            <td class="attendance-table__item">
            {{ sprintf('%d:%02d', $workHour, $workMinute) }}
            </td>

            <td class="attendance-table__item">
            <a href="{{ route('attendance.show', $attendance->id) }}"
            class="attendance-table__link">
            詳細
            </a>
            </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>
@endsection