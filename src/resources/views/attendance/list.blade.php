@extends('layouts.app')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/list.css')}}"><link rel="stylesheet" href="{{ asset('css/attendance/layouts/common.css')}}">
@endsection

@section('content')
<div class="attendance"> <h2 class="attendance__title">勤怠一覧</h2> {{-- 月選択ナビゲーション --}}
    <div class="attendance__nav month-selector"> <a href="#" class="month-selector__link month-selector__link--prev">← 前月</a> <div class="month-selector__current">
            <span class="month-selector__icon">📅</span> 2023/06
        </div>
        <a href="#" class="month-selector__link month-selector__link--next">翌月 →</a> </div>

    {{-- 勤怠テーブル --}}
    <div class="attendance__table-wrapper">
        <table class="attendance-table"> <thead class="attendance-table__head">
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
                {{-- データがある行 --}}
                <tr class="attendance-table__row">
                    <td class="attendance-table__item">06/01(木)</td>
                    <td class="attendance-table__item">09:00</td>
                    <td class="attendance-table__item">18:00</td>
                    <td class="attendance-table__item">1:00</td>
                    <td class="attendance-table__item">8:00</td>
                    <td class="attendance-table__item">
                        <a href="#" class="attendance-table__link">詳細</a>
                    </td>
                </tr>
                {{-- 休日などデータがない行 (Modifierでグレーアウト等の制御も可能) --}}
                <tr class="attendance-table__row attendance-table__row--empty">
                    <td class="attendance-table__item">06/04(日)</td>
                    <td class="attendance-table__item"></td>
                    <td class="attendance-table__item"></td>
                    <td class="attendance-table__item"></td>
                    <td class="attendance-table__item"></td>
                    <td class="attendance-table__item">
                        <a href="#" class="attendance-table__link">詳細</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
