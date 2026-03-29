@extends('layouts.admin')

@section('title', '修正申請承認画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/correction_requests/show.css') }}">
@endsection

@section('content')
<div class="admin-approval">
    <h2 class="admin-approval__title">修正申請承認</h2>

    <div class="attendance-detail">
        <table class="attendance-detail__table">
            <tr class="attendance-detail__row">
                <th class="attendance-detail__label">名前</th>
                <td class="attendance-detail__data">
                    {{ $correctionRequest->user->name }}
                </td>
            </tr>

            <tr class="attendance-detail__row">
                <th class="attendance-detail__label">日付</th>
                <td class="attendance-detail__data">
                    <span class="attendance-detail__year">
                        {{ \Carbon\Carbon::parse($correctionRequest->attendance->work_date)->format('Y年') }}
                    </span>
                    <span class="attendance-detail__date">
                        {{ \Carbon\Carbon::parse($correctionRequest->attendance->work_date)->format('n月j日') }}
                    </span>
                </td>
            </tr>

            <tr class="attendance-detail__row">
                <th class="attendance-detail__label">出勤・退勤</th>
                <td class="attendance-detail__data">
                    <span class="attendance-detail__time">
                        {{ \Carbon\Carbon::parse($correctionRequest->clock_in)->format('H:i') }}
                    </span>
                    <span class="attendance-detail__separator">〜</span>
                    <span class="attendance-detail__time">
                        {{ \Carbon\Carbon::parse($correctionRequest->clock_out)->format('H:i') }}
                    </span>
                </td>
            </tr>

            @foreach($correctionRequest->break_times as $index => $break)
                <tr class="attendance-detail__row">
                    <th class="attendance-detail__label">
                        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                    </th>

                    <td class="attendance-detail__data">
                        <span class="attendance-detail__time">
                            {{ \Carbon\Carbon::parse($break['start'])->format('H:i') }}
                        </span>
                        <span class="attendance-detail__separator">〜</span>
                        <span class="attendance-detail__time">
                            {{ \Carbon\Carbon::parse($break['end'])->format('H:i') }}
                        </span>
                    </td>
                </tr>
            @endforeach

            <tr class="attendance-detail__row">
                <th class="attendance-detail__label">備考</th>
                <td class="attendance-detail__data">
                    {{ $correctionRequest->reason }}
                </td>
            </tr>
        </table>
    </div>

    <div class="admin-approval__actions">
        <form action="{{ route('admin.request.approve', $correctionRequest->id) }}" method="POST">
            @csrf
            <button
                type="submit"
                class="admin-approval__button admin-approval__button--approve"
            >
                承認
            </button>
        </form>
    </div>
</div>
@endsection