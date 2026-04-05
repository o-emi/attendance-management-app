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
                        {{ \Carbon\Carbon::parse($correctionRequest->start_time)->format('H:i') }}
                    </span>
                    <span class="attendance-detail__separator">〜</span>
                    <span class="attendance-detail__time">
                        {{ \Carbon\Carbon::parse($correctionRequest->end_time)->format('H:i') }}
                    </span>
                </td>
            </tr>

            @foreach($correctionRequest->breakTimes ?? [] as $index => $breakTime)
                <tr class="attendance-detail__row">
                    <th class="attendance-detail__label">
                        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                    </th>

                    <td class="attendance-detail__data">
                        <span class="attendance-detail__time">
                            {{ \Carbon\Carbon::parse($breakTime->break_start)->format('H:i') }}
                        </span>
                        <span class="attendance-detail__separator">〜</span>
                        <span class="attendance-detail__time">
                            {{ \Carbon\Carbon::parse($breakTime->break_end)->format('H:i') }}
                        </span>
                    </td>
                </tr>
            @endforeach

            <tr class="attendance-detail__row">
                <th class="attendance-detail__label">備考</th>
                <td class="attendance-detail__data">
                    {{ $correctionRequest->note}}
                </td>
            </tr>
        </table>
    </div>

    <div class="admin-approval__actions">
        @if($correctionRequest->status === 'pending')
            <form action="{{ route('admin.request.approve', $correctionRequest->id) }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="admin-approval__button admin-approval__button--approve"
                >
                    承認
                </button>
            </form>
        @else
            <button
                type="button"
                class="admin-approval__button admin-approval__button--approved"
                disabled
            >
                承認済み
            </button>
        @endif
    </div>
</div>
@endsection