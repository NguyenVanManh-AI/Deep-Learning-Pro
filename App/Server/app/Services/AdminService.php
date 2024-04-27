<?php

namespace App\Services;

use App\Http\Requests\RequestAddManager;
use App\Http\Requests\RequestChangeIsBlock;
use App\Http\Requests\RequestChangeIsBlockMany;
use App\Http\Requests\RequestLogin;
use App\Jobs\SendMailNotify;
use App\Models\Admin;
use App\Models\Channel;
use App\Models\User;
use App\Repositories\AdminInterface;
use App\Repositories\UserRepository;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class AdminService
{
    use APIResponse;

    protected AdminInterface $adminRepository;

    public function __construct(
        AdminInterface $adminRepository,
    ) {
        $this->adminRepository = $adminRepository;
    }

    public function login(RequestLogin $request)
    {
        try {
            $admin = Admin::where('email', $request->email)->first();
            if (empty($admin)) {
                return $this->responseError('Email does not exist !');
            }
            $credentials = request(['email', 'password']);
            if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
                return $this->responseError('Email or password is incorrect!');
            }
            $admin->access_token = $token;
            $admin->token_type = 'bearer';
            $admin->expires_in = auth()->guard('admin_api')->factory()->getTTL() * 60;

            return $this->responseSuccessWithData($admin, 'Logged in successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            auth('admin_api')->logout();

            return $this->responseSuccess('Log out successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function profile(Request $request)
    {
        try {
            $admin = auth('admin_api')->user();

            return $this->responseSuccessWithData($admin, 'Get information account successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function addManager(RequestAddManager $request)
    {
        DB::beginTransaction();
        try {
            $new_password = Str::random(8);
            $data = array_merge($request->all(), [
                'password' => Hash::make($new_password),
                'role' => 'manager',
                'is_delete' => 0,
                'is_block' => 0,
                'email_verified_at' => now(),
            ]);

            $manager = User::create($data);
            $channel = Channel::create(['account_manager_id' => $manager->id]);
            $manager->update(['channel_id' => $channel->id]);

            $content = 'Below is your account information, please use it to log in to the system, then change your 
            password to ensure account security. <br> Email : <strong>' . $manager->email .
            '</strong> <br> Password : <strong>' . $new_password . '</strong>';

            Queue::push(new SendMailNotify($manager->email, $content));

            DB::commit();

            return $this->responseSuccessWithData($manager, 'Added manager account successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function getManagers(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'name';
                    break;

                case 'address':
                    $orderBy = 'address';
                    break;

                case 'phone':
                    $orderBy = 'phone';
                    break;

                case 'gender':
                    $orderBy = 'gender';
                    break;

                case 'new':
                    $orderBy = 'id';
                    break;

                default:
                    $orderBy = 'id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'role' => $request->role ?? 'all',
                'is_delete' => $request->is_delete ?? 'all',
                'is_block' => $request->is_block ?? 'all',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            $managers = UserRepository::getAllUsers($filter);
            if (!(empty($request->paginate))) {
                $managers = $managers->paginate($request->paginate);
            } else {
                $managers = $managers->get();
            }

            return $this->responseSuccessWithData($managers, 'Get managers information successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsBlockManager(RequestChangeIsBlock $request, $id_user)
    {
        DB::beginTransaction();
        try {
            $manager = User::find($id_user);
            if ($manager) {
                $manager->update(['is_block' => $request->is_block]);

                if($request->is_block == 1) $content = '<strong style="color:red">Your account has been locked by admin, if you think this is a mistake please contact the system !</strong>';
                else $content = '<strong style="color:green">Your account has been unlocked !</strong>';
    
                Queue::push(new SendMailNotify($manager->email, $content));

                DB::commit();

                return $this->responseSuccessWithData($manager, 'Change is block manager successfully !');
            } else {
                DB::commit();

                return $this->responseError(404, 'Not found manager !');
            }
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function changeIsBlockManyManager(RequestChangeIsBlockMany $request)
    {
        DB::beginTransaction();
        try {
            User::whereIn('id', $request->ids_manager)->update(['is_block' => $request->is_block]);
            $users = User::whereIn('id', $request->ids_manager)->get();
            if($request->is_block == 1) $content = '<strong style="color:red">Your account has been locked by admin, if you think this is a mistake please contact the system !</strong>';
            else $content = '<strong style="color:green">Your account has been unlocked !</strong>';
            foreach($users as $user) Queue::push(new SendMailNotify($user->email, $content));

            DB::commit();

            return $this->responseSuccess('Change is block many manager successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }
}
