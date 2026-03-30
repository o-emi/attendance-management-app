<div class="attendance-detail__group">
    <label class="attendance-detail__label">
        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
    </label>

    <div class="attendance-detail__content attendance-detail__input-row">
        @if($isPending)
            <span class="attendance-detail__text">
                {{
                    is_array($break)
                        ? ($break['start'] ?? $break['break_start'] ?? '')
                        : ($break->start ?? $break->break_start ?? '')
                }}
            </span>

            <span class="attendance-detail__separator">〜</span>

            <span class="attendance-detail__text">
                {{
                    is_array($break)
                        ? ($break['end'] ?? $break['break_end'] ?? '')
                        : ($break->end ?? $break->break_end ?? '')
                }}
            </span>
        @else
            <input
                type="time"
                name="break_start[]"
                class="attendance-detail__input"
                value="{{
                    is_array($break)
                        ? ($break['start'] ?? $break['break_start'] ?? '')
                        : ($break->start ?? $break->break_start ?? '')
                }}"
            >

            <span class="attendance-detail__separator">〜</span>

            <input
                type="time"
                name="break_end[]"
                class="attendance-detail__input"
                value="{{
                    is_array($break)
                        ? ($break['end'] ?? $break['break_end'] ?? '')
                        : ($break->end ?? $break->break_end ?? '')
                }}"
            >
        @endif
    </div>
</div>