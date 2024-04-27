<?php

namespace App\Http\Requests;

use App\Models\Channel;
use App\Traits\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RequestUpdateChannel extends FormRequest
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
        $account_manager_id = Auth::guard('user_api')->user()->id;
        $channelId = Channel::where('account_manager_id', $account_manager_id)->first()->id;

        return [
            'channel_id' => ['required', Rule::unique('channels')->ignore($channelId)],
            'channel_name' => 'required|min:1|max:60',
            'channel_secret' => ['required', Rule::unique('channels')->ignore($channelId)],
            'channel_access_token' => ['required', Rule::unique('channels')->ignore($channelId)],
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
