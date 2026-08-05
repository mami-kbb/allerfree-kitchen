<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeUpdateRequest extends FormRequest
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
            'image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'name' => ['required','string', 'max:50'],
            'allergy_recipe' => ['required', 'array'],
            'allergy_recipe.*' => ['exists:allergies,id'],
            'description' => ['max:500'],
            'ingredients.0' => ['required', 'string'],
            'ingredients.*' => ['nullable', 'string'],
            'quantities.*' => ['nullable', 'string'],
            'steps.0' => ['required', 'string'],
            'steps.*' => ['nullable', 'string'],
            'tips' => ['nullable', 'max:500'],
        ];
    }

    public function messages()
    {
        return [
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'name.required' => 'レシピ名を入力してください',
            'name.max' => 'レシピ名は50文字以内で入力してください',
            'allergy_recipe.required' => 'レシピのアレルギー情報を設定してください',
            'description.max' => 'レシピの説明は500文字以内で入力してください',
            'ingredients.0.required' => '材料を１つ以上入力してください',
            'steps.0.required' => '手順を１つ以上入力してください',
            'tips.max' => 'コツ・ポイントは500文字以内で入力してください。',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $ingredients = $this->ingredients ?? [];
            $quantities = $this->quantities ?? [];

            foreach ($ingredients as $index => $ingredient) {
                $quantity = $quantities[$index] ?? null;

                if ($ingredient && !$quantity) {
                    $validator->errors()->add("quantities.$index", '分量を入力してください');
                }

                if (!$ingredient && $quantity) {
                    $validator->errors()->add("ingredients.$index", '材料を入力してください');
                }
            }
        });
    }
}
