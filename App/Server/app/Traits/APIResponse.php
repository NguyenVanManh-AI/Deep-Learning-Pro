<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HTTPStatus;

trait APIResponse
{
    /**
     * @param mixed $messages
     */
    public function responseSuccess($messages = ['Success !'], int $status = HTTPStatus::HTTP_OK): JsonResponse
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        return Response::json([
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }

    /**
     * @param mixed $messages
     * @param mixed $data
     */
    public function responseSuccessWithData($data, $messages = ['Success !'], int $status = HTTPStatus::HTTP_OK): JsonResponse
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        return Response::json([
            'data' => $data,
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }

    /**
     * @param mixed $messages
     */
    public function responseError($messages = ['Failed !'], int $status = HTTPStatus::HTTP_BAD_REQUEST): JsonResponse
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        return Response::json([
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }

    /**
     * @param string $messages
     * @param mixed $errors
     */
    public function responseErrorWithData($errors, $messages = ['Failed !'], int $status = HTTPStatus::HTTP_BAD_REQUEST): JsonResponse
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }

        return Response::json([
            'errors' => $errors,
            'messages' => $messages,
            'status' => $status,
        ], $status);
    }

    public function responseErrorValidate($errors, $validator, int $status = HTTPStatus::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'messages' => $errors,
            'status' => $status,
        ], 422));
    }
}

/*
    + Nên dùng lỗi 400 của HTTP_BAD_REQUEST thay vì lỗi 500 của HTTP_INTERNAL_SERVER_ERROR
    + Cách dung app\Traits\APIResponse.php trong Service :
        + $messages
            => cả error và success thì đều có messages mặc định , nếu không truyền vào gì cả thì vẫn được
                + return $this->responseError();
                + return $this->responseSuccess();
                => vẫn được
            => trường hợp có truyền vào thì nếu truyền vào chuỗi thì tự ép kiểu về mảng , còn truyền vào mảng thì thôi không cần ép kiểu nữa
            => luôn là mảng để về dưới FE for ra để hiển thị ra Toastr
        + $status
            => mặc định là 200 hoặc 400 , có thể custom lại
        + $data nếu có thì dùng responseSuccessWithData , responseErrorWithData còn không thì dùng những cái trên
    + Những file Request thì dùng hàm responseErrorValidate

    + Một số ví dụ :
        return $this->responseError();
        return $this->responseSuccess();

        return $this->responseError('messages fail 2');
        return $this->responseSuccess('Login successful !');

        return $this->responseErrorWithData($data);
        return $this->responseSuccessWithData($data);

        return $this->responseError(['messages fail 1', 'messages fail 2']);
        return $this->responseError('Login successful !', 422);
        return $this->responseError(['messages fail 1', 'messages fail 2'], 422);

        return $this->responseErrorWithData($data, 'Login fail !');
        return $this->responseErrorWithData($data, ['messages fail 1', 'messages fail 2']);
        return $this->responseErrorWithData($data, 'Login fail !', 422);

        return $this->responseSuccess('Login successful !');
        return $this->responseSuccess('Login successful !', 201);

        return $this->responseSuccessWithData($data, 'Login successful !');
        return $this->responseSuccessWithData($data, 'Login successful !', 201);
*/
