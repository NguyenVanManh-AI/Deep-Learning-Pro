<?php

namespace App\Http\Requests;

use App\Rules\CustomContentIdsValidation;
use App\Rules\SentAtDifference;
use App\Traits\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateBroadcast extends FormRequest
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
        return [
            'title' => 'required|string',
            'content_ids' => ['required', 'min:1', 'max:5', new CustomContentIdsValidation], '',
            'sent_at' => ['required', 'date_format:Y-m-d H:i:s', new SentAtDifference], // giờ này sẽ là 24h
            'status' => 'required|in:scheduled,draf',
            // 'sent_at' => 'required|date_format:Y-m-d H:i:s',
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
