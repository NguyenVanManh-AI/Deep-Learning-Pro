<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddManager;
use App\Http\Requests\RequestChangeIsBlock;
use App\Http\Requests\RequestChangeIsBlockMany;
use App\Http\Requests\RequestLogin;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function login(RequestLogin $request)
    {
        return $this->adminService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->adminService->logout($request);
    }

    public function profile(Request $request)
    {
        return $this->adminService->profile($request);
    }

    public function addManager(RequestAddManager $request)
    {
        return $this->adminService->addManager($request);
    }

    public function getManagers(Request $request)
    {
        return $this->adminService->getManagers($request);
    }

    public function changeIsBlockManager(RequestChangeIsBlock $request, $id_user)
    {
        return $this->adminService->changeIsBlockManager($request, $id_user);
    }

    public function changeIsBlockManyManager(RequestChangeIsBlockMany $request)
    {
        return $this->adminService->changeIsBlockManyManager($request);
    }
}
