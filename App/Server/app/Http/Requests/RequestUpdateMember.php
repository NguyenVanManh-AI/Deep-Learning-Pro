<?php

namespace App\Http\Requests;

use App\Traits\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestUpdateMember extends FormRequest
{
    use APIResponse;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // $idMember = $this->input('id_user'); // nếu muốn bỏ vào request
        $idMember = $this->route('id_user'); // nếu muốn bỏ vào route : update-member/{id_user}  và lấy ra từ đó

        return [
            'email' => ['required', 'max:60' , 'email', Rule::unique('users')->ignore($idMember)],
            'name' => 'required|string|max:60',
            'line_user_id' => ['required', 'string', Rule::unique('users')->ignore($idMember)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        return $this->responseErrorValidate($errors, $validator);
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'body.required' => 'Body is required',
        ];
    }
}
