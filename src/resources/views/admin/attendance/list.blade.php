@extends('layouts.admin')

@section('title', '勤怠一覧(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/list.css')}}">
@endsection

@section('content')
<div class="attendance">
    <div class="attendance__inner">

        <h2 class="attendance__title">
            {{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠
        </h2>

        <nav class="attendance__nav date-nav">
            <a href="{{ route('admin.attendance.list', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}"
                class="date-nav__link date-nav__link--prev">
                ← 前日
            </a>

            <div class="date-nav__current">
                <span class="date-nav__icon">📅</span>
                {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
            </div>

            <a href="{{ route('admin.attendance.list', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}"
                class="date-nav__link date-nav__link--next">
                翌日 →
            </a>
        </nav>

        <div class="attendance__table-wrapper">
            <table class="attendance-table">
                <thead class="attendance-table__head">
                    <tr class="attendance-table__row">
                        <th class="attendance-table__header">名前</th>
                        <th class="attendance-table__header">出勤</th>
                        <th class="attendance-table__header">退勤</th>
                        <th class="attendance-table__header">休憩</th>
                        <th class="attendance-table__header">合計</th>
                        <th class="attendance-table__header">詳細</th>
                    </tr>
                </thead>

                <tbody class="attendance-table__body">

                @foreach($users as $user)
                    @php
                        $attendance = $user->attendances->first();
                            $breakTotal = 0;
                            $workTotal = 0;
                            $actualWork = 0;

                            if ($attendance) {

                                foreach ($attendance->breakTimes as $break) {
                                    if ($break->break_start && $break->break_end) {
                                        $breakTotal += \Carbon\Carbon::parse($break->break_start)
                                            ->diffInSeconds($break->break_end);
                                    }
                                }

                                if ($attendance->clock_in && $attendance->clock_out) {
                                    $workTotal = \Carbon\Carbon::parse($attendance->clock_in)
                                        ->diffInSeconds($attendance->clock_out);

                                    $actualWork = $workTotal - $breakTotal;
                                }
                            }
                        @endphp

                    <tr class="attendance-table__row">
                        <td class="attendance-table__item">
                            {{ $user->name }}
                        </td>

                        <td class="attendance-table__item">
                            {{ $attendance?->clock_in
                                ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                                : '' }}
                        </td>

                        <td class="attendance-table__item">
                            {{ $attendance?->clock_out
                                ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                                : '' }}
                        </td>

                        <td class="attendance-table__item">
                            {{ $breakTotal ? gmdate('H:i', $breakTotal) : '' }}
                        </td>

                        <td class="attendance-table__item">
                            {{ $actualWork ? gmdate('H:i', $actualWork) : '' }}
                        </td>

                        <td class="attendance-table__item">
                            @if($attendance)
                                <a href="{{ route('admin.attendance.show', $attendance->id) }}"
                                    class="attendance-table__link">
                                    詳細
                                </a>
                            @endif
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection