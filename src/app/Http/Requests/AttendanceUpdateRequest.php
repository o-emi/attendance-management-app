<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clock_in'  => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i'],

            'break_start.*' => ['nullable', 'date_format:H:i'],
            'break_end.*'   => ['nullable', 'date_format:H:i'],

            'remark' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required'  => '出勤時間を入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.after'    => '出勤時間もしくは退勤時間が不適切な値です',

            'break_start.*.date_format' => '休憩時間が不適切な値です',
            'break_end.*.date_format'   => '休憩時間が不適切な値です',

            'remark.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->clock_in && $this->clock_out) {

                $clockIn  = Carbon::createFromFormat('H:i', $this->clock_in);
                $clockOut = Carbon::createFromFormat('H:i', $this->clock_out);

                if ($clockIn->gte($clockOut)) {
                    $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
                }
    }

            $starts = $this->break_start ?? [];
            $ends   = $this->break_end ?? [];

            foreach ($starts as $i => $start) {

                $end = $ends[$i] ?? null;

                if (!$start && !$end) {
                    continue;
                }

                if ($start && !$end) {
                    $validator->errors()->add("break_end.$i", '休憩時間を入力してください');
                    continue;
                }

                if (!$start && $end) {
                    $validator->errors()->add("break_start.$i", '休憩時間を入力してください');
                    continue;
                }

                $startTime = Carbon::createFromFormat('H:i', $start);
                $endTime   = Carbon::createFromFormat('H:i', $end);

                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $validator->errors()->add("break_end.$i", '休憩時間が不適切な値です');
                }

                if ($startTime->lt($clockIn)) {
                    $validator->errors()->add("break_start.$i", '休憩時間が不適切な値です');
                }

                if ($endTime->gt($clockOut)) {
                    $validator->errors()->add("break_end.$i", '休憩時間もしくは退勤時間が不適切な値です');
                }
            }
        });
    }
}