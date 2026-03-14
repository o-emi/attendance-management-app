@extends('layouts.app')

@section('title','勤怠詳細')


@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/layouts/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')

<h2>勤怠詳細</h2>

<p>日付：{{ $attendance->work_date }}</p>
<p>出勤：{{ $attendance->clock_in }}</p>
<p>退勤：{{ $attendance->clock_out }}</p>

@endsection

