@extends('layouts.admin')

@section('title', 'スタッフ一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/index.css') }}">
@endsection

@section('content')
<section class="staff-index">
    <div class="staff-index__inner">

        <div class="staff-index__header">
            <h2 class="staff-index__title">スタッフ一覧</h2>
        </div>

        <div class="staff-index__table-container">
            <table class="staff-table">
                <thead class="staff-table__head">
                    <tr class="staff-table__row">
                        <th class="staff-table__label">名前</th>
                        <th class="staff-table__label">メールアドレス</th>
                        <th class="staff-table__label">月次勤怠</th>
                    </tr>
                </thead>
                <tbody class="staff-table__body">
                    @foreach($users as $user)
                        <tr class="staff-table__row">
                            <td class="staff-table__data">{{ $user->name }}</td>
                            <td class="staff-table__data">{{ $user->email }}</td>
                            <td class="staff-table__data">
                                <a href="{{ route('admin.staff.attendance', $user->id) }}" class="staff-table__link">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection