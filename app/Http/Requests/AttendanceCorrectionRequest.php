<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class AttendanceCorrectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'request_clock_in' => ['required' ,'date_format:H:i'],
            'request_clock_out' => ['required', 'date_format:H:i', 'after:request_clock_in'],
            'note' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
        'request_clock_in.required' => '出勤時間を入力してください',
        'request_clock_in.date_format' => '出勤時間を入力してください',

        'request_clock_out.required' => '退勤時間を入力してください',
        'request_clock_out.date_format' => '退勤時間を入力してください',
        'request_clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',

        'breaks.*.break_start.date_format' => '休憩時間が不適切な値です',
        'breaks.*.break_start.after_or_equal' => '休憩時間が不適切な値です',
        'breaks.*.break_start.before' => '休憩時間が不適切な値です',

        'note.required' => '備考を入力してください',
            
            
        ];
    }

    public function withValidator($validator)
    {
    $validator->after(function ($validator) {

        foreach ($this->breaks ?? [] as $index => $break)   {
            if (!empty($break['break_start'])) {
                $validator->errors()->add(
                    "breaks.$index.break_start",
                    '休憩時間が不適切な値です'
                    );
                }
            }
        });
    }
}