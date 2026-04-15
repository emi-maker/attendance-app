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
            'request_clock_in' => ['required'],
            'request_clock_out' => ['required', 'after:request_clock_in'],
            'note' => ['required'],

            'breaks.*.break_start' => ['nullable'],
            'breaks.*.break_end' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'request_clock_in.required' => '出勤時間を入力してください',
            'request_clock_out.required' => '退勤時間を入力してください',
            'request_clock_out.after' => '退勤時間は出勤時間より後の時間を入力してください',
            'note.required' => '備考を入力してください',
            
        ];
    }
}
