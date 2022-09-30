<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'type' => ['required', 'in:employee,supervisor,manager'],
            'code' => ['required', 'numeric'],
            'extension' => ['sometimes'],
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $user = $this->route()->parameter('user');

            $rules['email'] = 'required|email|unique:users,id,' . $user->id;
            $rules['password'] = '';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if (!$this->type) {
            return $this->merge([
                'type' => 'employee'
            ]);
        }

        return $this;
    }
}
