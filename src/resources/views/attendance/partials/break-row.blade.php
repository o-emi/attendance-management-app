@php
    $latestRequest = $attendance->correctionRequests()->latest()->first();
@endphp

@if($attendance->status === '承認待ち')

    @foreach ($latestRequest->break_times ?? [] as $index => $break)
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">
                休憩{{ $index + 1 }}
            </label>

            <div class="attendance-detail__content attendance-detail__input-row">
                <span class="attendance-detail__text">
                    {{ $break['start'] ?? '' }}
                </span>
                <span class="attendance-detail__separator">〜</span>
                <span class="attendance-detail__text">
                    {{ $break['end'] ?? '' }}
                </span>
            </div>
        </div>
    @endforeach

@else

    @foreach ($attendance->breakTimes as $index => $break)
        <div class="attendance-detail__group">
            <label class="attendance-detail__label">
                休憩{{ $index + 1 }}
            </label>

            <div class="attendance-detail__content attendance-detail__input-row">
                <input type="time"
                    name="break_start[]"
                    class="attendance-detail__input"
                    value="{{ old('break_start.'.$index, $break->break_start ? $break->break_start->format('H:i') : '') }}">
                <span class="attendance-detail__separator">〜</span>
                <input type="time"
                    name="break_end[]"
                    class="attendance-detail__input"
                    value="{{ old('break_end.'.$index, $break->break_end ? $break->break_end->format('H:i') : '') }}">
            </div>
        </div>
    @endforeach

@endif