<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddMember;
use App\Http\Requests\RequestChangeIsDeleteManyMember;
use App\Http\Requests\RequestChangeIsDeleteMember;
use App\Http\Requests\RequestChangePassword;
use App\Http\Requests\RequestCreatePassword;
use App\Http\Requests\RequestLogin;
use App\Http\Requests\RequestSendForgot;
use App\Http\Requests\RequestUpdateChannel;
use App\Http\Requests\RequestUpdateMember;
use App\Http\Requests\RequestUpdateProfileUser;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(RequestLogin $request)
    {
        return $this->userService->login($request);
    }

    public function profile(Request $request)
    {
        return $this->userService->profile($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }

    public function changePassword(RequestChangePassword $request)
    {
        return $this->userService->changePassword($request);
    }

    public function forgotSend(RequestSendForgot $request)
    {
        return $this->userService->forgotSend($request);
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        return $this->userService->forgotUpdate($request);
    }

    public function updateProfile(RequestUpdateProfileUser $request)
    {
        return $this->userService->updateProfile($request);
    }

    public function updateChannel(RequestUpdateChannel $request)
    {
        return $this->userService->updateChannel($request);
    }

    public function getInforChannel(Request $request)
    {
        return $this->userService->getInforChannel($request);
    }

    public function addMember(RequestAddMember $request)
    {
        return $this->userService->addMember($request);
    }

    public function getMembers(Request $request)
    {
        return $this->userService->getMembers($request);
    }

    public function updateMember(RequestUpdateMember $request, $id_user)
    {
        return $this->userService->updateMember($request, $id_user);
    }

    public function changeIsDeleteMember(RequestChangeIsDeleteMember $request, $id_user)
    {
        return $this->userService->changeIsDeleteMember($request, $id_user);
    }

    public function changeIsDeleteManyMember(RequestChangeIsDeleteManyMember $request)
    {
        return $this->userService->changeIsDeleteManyMember($request);
    }
}
