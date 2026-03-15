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
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',

            'break_start.*.after' => '休憩時間が不適切な値です',
            'break_start.*.before' => '休憩時間が不適切な値です',

            'break_end.*.before' => '休憩時間もしくは退勤時間が不適切な値です',

            'remark.required' => '備考を記入してください',
        ];
    }
}
