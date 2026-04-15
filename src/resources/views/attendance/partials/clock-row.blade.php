<div class="attendance-detail__group">
    <label class="attendance-detail__label">出勤・退勤</label>

    <div class="attendance-detail__content attendance-detail__input-row">

        @php
            $latestRequest = $attendance->correctionRequests()->latest()->first();
        @endphp

        @if($isPending)
            <span class="attendance-detail__text">
                {{ $latestRequest?->start_time
                    ? \Carbon\Carbon::parse($latestRequest->start_time)->format('H:i')
                    : $attendance->clock_in?->format('H:i') }}
            </span>

            <span class="attendance-detail__separator">〜</span>

            <span class="attendance-detail__text">
                {{ $latestRequest?->end_time
                    ? \Carbon\Carbon::parse($latestRequest->end_time)->format('H:i')
                    : $attendance->clock_out?->format('H:i') }}
            </span>

        @else
            <div class="attendance-detail__field">
                <input
                    type="time"
                    name="clock_in"
                    class="attendance-detail__input"
                    value="{{ old('clock_in', $attendance->clock_in?->format('H:i')) }}"
                >

                @error('clock_in')
                    <p class="attendance-detail__error-text">{{ $message }}</p>
                @enderror
            </div>

            <span class="attendance-detail__separator">〜</span>

            <div class="attendance-detail__field">
                <input
                    type="time"
                    name="clock_out"
                    class="attendance-detail__input"
                    value="{{ old('clock_out', $attendance->clock_out?->format('H:i')) }}"
                >

                @error('clock_out')
                    <p class="attendance-detail__error-text">{{ $message }}</p>
                @enderror
            </div>
        @endif

    </div>
</div>