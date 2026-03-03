@extends('layouts.app')

@section('title', '勤怠登録画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css')}}">
@endsection

@section('content')
<div class="attendance">

    <div class="attendance__inner">

        <div class="punch-panel">

            <div class="punch-panel__status">
                <span class="punch-panel__badge
                    @if($status==='勤務外') punch-panel__badge--off-duty
                    @else punch-panel__badge--on-duty
                    @endif">
                        {{ $status }}
                </span>
            </div>

            <div class="punch-panel__date">
                <p class="punch-panel__date-text">
                    {{ \Carbon\Carbon::now()->isoFormat('YYYY年M月D日(ddd)') }}
                </p>
            </div>

            <div class="punch-panel__time">
                <p id="current-time" class="punch-panel__time-text">08:00</p>
            </div>

            <div class="punch-panel__actions">

                @if($status === '勤務外')
                <form method="POST" action="{{ route('attendance.punch') }}">
                    @csrf
                    <button type="submit" class="punch-panel__button">出勤</button>
                </form>

                @elseif($status === '出勤中')
                    <form method="POST" action="{{ route('attendance.punch') }}">
                        @csrf
                        <button type="submit" class="punch-panel__button">退勤</button>
                    </form>

                    <form method="POST" action="{{ route('attendance.break.start') }}">
                        @csrf
                        <button type="submit" class="punch-panel__button">休憩入</button>
                    </form>


                @elseif($status === '休憩中')
                    <form method="POST" action="{{ route('attendance.break.end') }}">
                        @csrf
                        <button type="submit" class="punch-panel__button">休憩戻</button>
                    </form>

                @elseif($status === '退勤済')
                    <p class="punch-panel__message">お疲れ様でした。</p>
                @endif

            </div>
        </div>
    </div>

    <script>
    function updateTime() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        document.getElementById('current-time').textContent = `${h}:${m}:${s}`;
    }
    updateTime();
    setInterval(updateTime, 1000);
    </script>

</div>
@endsection