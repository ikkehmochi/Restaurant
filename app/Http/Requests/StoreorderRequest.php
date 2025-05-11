<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreOrderRequest extends FormRequest
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
        $rules = [
            'customer_name' => 'required|string|max:255',
            'table_id' => 'required|exists:tables,id',
            'total_price' => 'required|numeric',
            'notes' => 'nullable|string',
        ];
        if ($this->has('menus')) {
            foreach ($this->input('menus') as $menuId => $menuData) {
                if (isset($menuData['selected'])) {
                    $rules['menus.' . $menuId . '.quantity'] = 'required|numeric|min:1';
                }
            }
        }
        return $rules;
    }
}
