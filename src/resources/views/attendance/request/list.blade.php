@extends('layouts.app')

@section('title', '申請一覧画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/request/list.css')}}">
@endsection

@section('content')
<div class="requests">
    <h2 class="requests__title">申請一覧</h2>

    <nav class="requests__nav">
        <ul class="requests__tabs">
            <li class="requests__tab">
                <a href="#" class="requests__tab-link requests__tab-link--active">承認待ち</a>
            </li>
            <li class="requests__tab">
                <a href="#" class="requests__tab-link">承認済み</a>
            </li>
        </ul>
    </nav>

    <div class="requests__content">
        <table class="requests-table">
            <thead class="requests-table__head">
                <tr class="requests-table__row">
                    <th class="requests-table__header">状態</th>
                    <th class="requests-table__header">名前</th>
                    <th class="requests-table__header">対象日時</th>
                    <th class="requests-table__header">申請理由</th>
                    <th class="requests-table__header">申請日時</th>
                    <th class="requests-table__header">詳細</th>
                </tr>
            </thead>
            <tbody class="requests-table__body">
                @foreach($pendingRequests as $request)
                <tr class="requests-table__row">
                    <td class="requests-table__item">承認待ち</td>
                    <td class="requests-table__item">{{ $request->user->name }}</td>
                    <td class="requests-table__item">{{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y/m/d') }}</td>
                    <td class="requests-table__item">{{ $request->note }}</td>
                    <td class="requests-table__item">{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                    <td class="requests-table__item">
                        <a href="#" class="requests-table__link">詳細</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection