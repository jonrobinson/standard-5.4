<?php

namespace App\Http\Requests;

use App\Helpers\Responder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordRequest extends FormRequest
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
        $user = Auth::user();
        return [
            'password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        return Responder::noJsonError($validator->errors()->all());
    }
}
