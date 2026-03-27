@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/correction_requests/index.css') }}">
@endsection

<title>@yield('title', '申請一覧')</title>

@section('content')
<div class="request-list">
    <h2 class="request-list__title">申請一覧</h2>

    <div class="request-list__tabs">
        <a
            href="{{ route('admin.request.list', ['status' => 'pending']) }}"
            class="request-list__tab {{ $status === 'pending' ? 'request-list__tab--active' : '' }}"
        >
            承認待ち
        </a>

        <a
            href="{{ route('admin.request.list', ['status' => 'approved']) }}"
            class="request-list__tab {{ $status === 'approved' ? 'request-list__tab--active' : '' }}"
        >
            承認済み
        </a>
    </div>

    <div class="request-list__content">
        <table class="request-table">
            <thead class="request-table__head">
                <tr class="request-table__row">
                    <th class="request-table__header">状態</th>
                    <th class="request-table__header">名前</th>
                    <th class="request-table__header">対象日時</th>
                    <th class="request-table__header">申請理由</th>
                    <th class="request-table__header">申請日時</th>
                    <th class="request-table__header">詳細</th>
                </tr>
            </thead>

            <tbody class="request-table__body">
                @foreach($requests as $request)
                    <tr class="request-table__row">
                        <td class="request-table__item">
                            {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}
                        </td>

                        <td class="request-table__item">
                            {{ $request->user->name }}
                        </td>

                        <td class="request-table__item">
                            {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y/m/d') }}
                        </td>

                        <td class="request-table__item">
                            {{ $request->reason }}
                        </td>

                        <td class="request-table__item">
                            {{ $request->created_at->format('Y/m/d') }}
                        </td>

                        <td class="request-table__item">
                            <a
                                href="{{ route('admin.request.show', $request->id) }}"
                                class="request-table__link"
                            >
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