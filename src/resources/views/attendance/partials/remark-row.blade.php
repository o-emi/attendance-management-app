<div class="attendance-detail__group">
    <label class="attendance-detail__label">備考</label>

    <div class="attendance-detail__content">

        @if($attendance->status === '承認待ち')
            <span class="attendance-detail__text">{{ $attendance->remark }}</span>
        @else
            <textarea name="remark" class="attendance-detail__textarea">{{ old('remark', $attendance->remark) }}</textarea>
        @endif

        @error('remark')
            <p class="attendance-detail__error-text">{{ $message }}</p>
        @enderror
    </div>
</div>