<div class="attendance-detail__group">
    <label class="attendance-detail__label">
        休憩{{ $attendance->breakTimes->count() + 1 }}
    </label>

    <div class="attendance-detail__content attendance-detail__input-row">

        @if($attendance->status === '承認待ち')
            <span class="attendance-detail__text"></span>
            <span class="attendance-detail__separator">〜</span>
            <span class="attendance-detail__text"></span>
        @else
            <input type="time" name="break_start[]" class="attendance-detail__input">
            <span class="attendance-detail__separator">〜</span>
            <input type="time" name="break_end[]" class="attendance-detail__input">
        @endif

    </div>
</div>