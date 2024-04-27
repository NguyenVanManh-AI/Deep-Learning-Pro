<?php

namespace App\Http\Requests;

use App\Traits\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RequestAddContent extends FormRequest
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
        $rules = [
            'content_type' => 'required|string|in:text,sticker,image',
        ];
        // pass qua rule của content_type trước rồi mới đến switch
        $contentType = $this->input('content_type');
        switch ($contentType) {
            case 'text':
                $rules = array_merge($rules, [
                    'content_data.type' => 'required|string|in:text',
                    'content_data.text' => 'required|string|min:1|max:200',
                ]);
                break;
            case 'sticker':
                $rules = array_merge($rules, [
                    'content_data.type' => 'required|string|in:sticker',
                    'content_data.packageId' => 'required|string',
                    'content_data.stickerId' => 'required|string',
                ]);
                break;
            case 'image':
                $rules = array_merge($rules, [
                    'image_content' => 'required|image|mimes:jpeg,png,jpg,gif',
                ]);
                break;
        }

        return $rules;
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
