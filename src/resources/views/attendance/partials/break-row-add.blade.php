<div class="attendance-detail__group">
    <label class="attendance-detail__label">
        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
    </label>

    <div class="attendance-detail__content attendance-detail__input-row">
        @if($isPending)
            <span class="attendance-detail__text">
                {{
                    is_array($break)
                        ? ($break['break_start'] ?? '')
                        : ($break?->break_start
                            ? \Carbon\Carbon::parse($break->break_start)->format('H:i')
                            : '')
                }}
            </span>

            <span class="attendance-detail__separator">〜</span>

            <span class="attendance-detail__text">
                {{
                    is_array($break)
                        ? ($break['break_end'] ?? '')
                        : ($break?->break_end
                            ? \Carbon\Carbon::parse($break->break_end)->format('H:i')
                            : '')
                }}
            </span>

        @else
            <div class="attendance-detail__field">
                <input
                    type="time"
                    name="break_start[]"
                    class="attendance-detail__input"
                    value="{{ old('break_start.' . $index, is_array($break)
                        ? ($break['break_start'] ?? '')
                        : ($break?->break_start
                            ? \Carbon\Carbon::parse($break->break_start)->format('H:i')
                            : '')
                    ) }}"
                >

                @error("break_start.$index")
                    <p class="attendance-detail__error-text">{{ $message }}</p>
                @enderror
            </div>

            <span class="attendance-detail__separator">〜</span>

            <div class="attendance-detail__field">
                <input
                    type="time"
                    name="break_end[]"
                    class="attendance-detail__input"
                    value="{{ old('break_end.' . $index, is_array($break)
                        ? ($break['break_end'] ?? '')
                        : ($break?->break_end
                            ? \Carbon\Carbon::parse($break->break_end)->format('H:i')
                            : '')
                    ) }}"
                >

                @error("break_end.$index")
                    <p class="attendance-detail__error-text">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>
</div>