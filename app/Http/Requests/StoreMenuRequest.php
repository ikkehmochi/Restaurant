<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;  // Change to true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'category_id' => 'required|exists:menu_categories,id',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        if ($this->has('ingredients')) {
            foreach ($this->input('ingredients') as $ingredientId => $ingredientData) {
                if (isset($ingredientData['selected'])) {
                    $rules['ingredients.' . $ingredientId . '.quantity'] = 'required|numeric|min:1';
                }
            }
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => str_replace('.', '', $this->price),
        ]);
    }
}
