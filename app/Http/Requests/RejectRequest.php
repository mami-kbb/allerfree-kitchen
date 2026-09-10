<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:300'],
        ];
    }

    public function message()
    {
        return [
            'rejection_reason.required' => '差戻し理由を入力してください',
            'rejection_reason.required' => '差戻し理由は300文字以内で入力してください',
        ];
    }
}
