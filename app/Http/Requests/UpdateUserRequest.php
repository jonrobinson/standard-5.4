<?php

namespace App\Http\Requests;

use App\Helpers\Responder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'max:255',
            'last_name' => 'max:255',
            'email' => 'email|max:255|unique:users,email,' . $user->id, // do not validate unique against current user, note: no spaces allowed between arguments
        ];
    }

    protected function formatErrors(Validator $validator)
    {
        return Responder::noJsonError($validator->errors()->all(), '');
    }
}
