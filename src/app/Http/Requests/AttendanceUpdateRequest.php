<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clock_in' => ['required','date_format:H:i'],
            'clock_out' => ['required','date_format:H:i','after:clock_in'],

            'break_start.*' => ['nullable','date_format:H:i','after:clock_in','before:clock_out'],
            'break_end.*' => ['nullable','date_format:H:i','before:clock_out'],

            'remark' => ['required','string'],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',

            'break_start.*.after' => '休憩時間が不適切な値です',
            'break_start.*.before' => '休憩時間が不適切な値です',

            'break_end.*.before' => '休憩時間もしくは退勤時間が不適切な値です',

            'remark.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $starts = $this->break_start;
            $ends = $this->break_end;

            if (!$starts || !$ends) {
                return;
            }

            foreach ($starts as $i => $start) {

                $end = $ends[$i] ?? null;

                if (!$start && !$end) {
                    continue;
                }

                if ($start && !$end) {
                    $validator->errors()->add("break_end.$i",'休憩時間を入力してください');
                    continue;
                }

                if (!$start && $end) {
                    $validator->errors()->add("break_start.$i",'休憩時間を入力してください');
                    continue;
                }

                if ($end <= $start) {
                    $validator->errors()->add("break_end.$i",'休憩時間が不適切な値です');
                }
            }

        });
    }
}
