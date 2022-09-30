<?php

namespace App\Http\Requests\Admin;

use App\Models\BreakModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BreakStoreRequest extends FormRequest
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
            'time' => ['required', 'numeric', Rule::in(BreakModel::$times)],
            'reason' => ['required', 'string', Rule::in(BreakModel::$reasons)]
        ];
    }
}
