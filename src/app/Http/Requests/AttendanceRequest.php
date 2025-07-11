<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in' => 'required|before:clock_out',
            'clock_out' => 'required',
            'start.*' => 'after:clock_in',
            'end.*' => 'before:clock_out',
            'notes' => 'required',
        ];

    }

    public function messages()
    {
        return [
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'start.*.after' => '休憩時間が勤務時間外です',
            'end.*.before' => '休憩時間が勤務時間外です',
            'notes.required' => '備考を記入してください',
        ];
    }
}
