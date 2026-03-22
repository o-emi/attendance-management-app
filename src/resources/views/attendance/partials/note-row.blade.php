<div class="attendance-detail__group">
    <label class="attendance-detail__label">備考</label>

    <div class="attendance-detail__content">

        <textarea name="note" class="attendance-detail__textarea">
            {{ old('note', $attendance->remark) }}
        </textarea>

        @error('note')
            <p class="attendance-detail__error-text">{{ $message }}</p>
        @enderror
    </div>
</div>