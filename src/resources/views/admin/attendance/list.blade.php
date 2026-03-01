@extends('layouts.admin')

@section('title', '勤怠一覧(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance/list.css')}}">
@endsection

@section('content')
<div class="attendance">
    <div class="attendance__inner">
        <h2 class="attendance__title">2023年6月1日の勤怠</h2>

        <nav class="attendance__nav date-nav">
            <a href="#" class="date-nav__link date-nav__link--prev">← 前日</a>
            <div class="date-nav__current">
                <span class="date-nav__icon">📅</span> 2023/06/01
            </div>
            <a href="#" class="date-nav__link date-nav__link--next">翌日 →</a>
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
                    <tr class="attendance-table__row">
                        <td class="attendance-table__item">山田 太郎</td>
                        <td class="attendance-table__item">09:00</td>
                        <td class="attendance-table__item">18:00</td>
                        <td class="attendance-table__item">1:00</td>
                        <td class="attendance-table__item">8:00</td>
                        <td class="attendance-table__item">
                            <a href="#" class="attendance-table__link">詳細</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection