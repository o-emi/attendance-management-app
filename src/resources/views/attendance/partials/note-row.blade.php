@php
    $isPending = $isPending ?? false;
    $remark = $remark ?? '';
@endphp

<div class="attendance-detail__group">
    <label class="attendance-detail__label">備考</label>

    <div class="attendance-detail__content">
        @if($isPending)
            <span class="attendance-detail__textarea-span">
                {{ $remark }}
            </span>
        @else
            <textarea
                name="remark"
                class="attendance-detail__textarea"
            >{{ old('remark', $remark) }}</textarea>

            @error('remark')
                <p class="attendance-detail__error-text">{{ $message }}</p>
            @enderror
        @endif
    </div>
</div>