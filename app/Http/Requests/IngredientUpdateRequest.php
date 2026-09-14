<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientUpdateRequest extends FormRequest
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
            'reading' => ['required', 'string', 'regex:/^[ぁ-んー]+$/u'],
            'category' => ['required', 'string'],
            'allergy_categories' => ['required', 'array'],
            //配列の中の1つ1つにルールを適用する
            'allergy_categories.*' => ['integer', 'exists:allergy_categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'reading.required' => '読み方を入力してください',
            'reading.regex' => '読み方はひらがなで入力してください',
            'category.required' => '食材カテゴリーを選択してください',
            'allergy_categories.required' => 'アレルギーカテゴリーを選択してください',
        ];
    }
}
