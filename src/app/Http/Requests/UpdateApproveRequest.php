<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
class UpdateApproveRequest extends FormRequest
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
            'request_clock_in' => 'required',
            'request_clock_out' => 'required',
            'note' => 'required',

            'breaks.*.break_start'
            => 'nullable|required_with:breaks.*.break_end',

            'breaks.*.break_end'
            => 'nullable|required_with:breaks.*.break_start',
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }


    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            // 出勤 >= 退勤
            if ($this->request_clock_in >= $this->request_clock_out) {

                $validator->errors()->add(
                    'request_clock_in',
                    '出勤時間もしくは退勤時間が不適切な値です'
                );
            }

            // 休憩チェック
            foreach ($this->breaks ?? [] as $index => $break) {

                if (
                    empty($break['break_start']) ||
                    empty($break['break_end'])
                ) {
                continue;
                }

                // 休憩開始が勤務時間外
                if (
                    $break['break_start'] < $this->request_clock_in
                    ||
                    $break['break_start'] > $this->request_clock_out
                ) {

                    $validator->errors()->add(
                    'breaks.'. $index . '.break_start',
                    '休憩時間が不適切な値です'
                    );
                }

                // 休憩終了が退勤後
                if (
                    $break['break_end'] > $this->request_clock_out
                ) {

                    $validator->errors()->add(
                       'breaks.'. $index . '.break_end',
                        '休憩時間もしくは退勤時間が不適切な値です'
                    );
                }
            }
        });
    }
}
