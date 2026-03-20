<div class="attendance-detail__group">
    <label class="attendance-detail__label">出勤・退勤</label>

    <div class="attendance-detail__content attendance-detail__input-row">

        @if($attendance->status === '承認待ち')
            <span class="attendance-detail__text">
                {{ $attendance->clock_in?->format('H:i') }}
            </span>
            <span class="attendance-detail__separator">〜</span>
            <span class="attendance-detail__text">
                {{ $attendance->clock_out?->format('H:i') }}
            </span>
        @else
            <input type="time"
                name="clock_in"
                class="attendance-detail__input"
                value="{{ old('clock_in', $attendance->clock_in?->format('H:i')) }}">
            <span class="attendance-detail__separator">〜</span>
            <input type="time"
                name="clock_out"
                class="attendance-detail__input"
                value="{{ old('clock_out', $attendance->clock_out?->format('H:i')) }}">
        @endif

        @error('clock_time')
            <p class="attendance-detail__error-text">{{ $message }}</p>
        @enderror
    </div>
</div>