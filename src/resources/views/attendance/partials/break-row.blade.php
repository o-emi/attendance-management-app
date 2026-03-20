<div class="attendance-detail__group">
    <label class="attendance-detail__label">
        休憩{{ $index > 0 ? $index + 1 : '' }}
    </label>

    <div class="attendance-detail__content attendance-detail__input-row">

        @if($attendance->status === '承認待ち')
            <span class="attendance-detail__text">
                {{ $break->break_start ? $break->break_start->format('H:i') : '' }}
            </span>
            <span class="attendance-detail__separator">〜</span>
            <span class="attendance-detail__text">
                {{ $break->break_end ? $break->break_end->format('H:i') : '' }}
            </span>
        @else
            <input type="time"
                name="break_start[]"
                class="attendance-detail__input"
                value="{{ old('break_start.'.$index, $break->break_start ? $break->break_start->format('H:i') : '') }}">
            <span class="attendance-detail__separator">〜</span>
            <input type="time"
                name="break_end[]"
                class="attendance-detail__input"
                value="{{ old('break_end.'.$index, $break->break_end ? $break->break_end->format('H:i') : '') }}">
        @endif

        @error('break_end.'.$index)
            @if(old('break_start.'.$index) || old('break_end.'.$index))
                <p class="attendance-detail__error-text">{{ $message }}</p>
            @endif
        @enderror
    </div>
</div>