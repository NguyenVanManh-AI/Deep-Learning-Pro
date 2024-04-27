<?php

namespace App\Services;

use App\Enums\UserEnum;
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
use App\Jobs\SendForgotPasswordEmail;
use App\Jobs\SendMailNotify;
use App\Models\Channel;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use App\Traits\APIResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class UserService
{
    use APIResponse;

    protected UserInterface $userRepository;

    public function __construct(
        UserInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function login(RequestLogin $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (empty($user)) {
                return $this->responseError('Email does not exist !');
            }
            if ($user->is_delete == 1) {
                return $this->responseError('Your account has been locked !');
            }
            if ($user->is_block == 1) {
                return $this->responseError('Your account has been locked !');
            }

            $credentials = request(['email', 'password']);
            if (!$token = auth()->guard('user_api')->attempt($credentials)) {
                return $this->responseError('Email or password is incorrect!');
            }
            $user->access_token = $token;
            $user->token_type = 'bearer';
            $user->expires_in = auth()->guard('user_api')->factory()->getTTL() * 60;

            return $this->responseSuccessWithData($user, 'Logged in successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            auth('user_api')->logout();

            return $this->responseSuccess('Log out successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = auth('user_api')->user();

            return $this->responseSuccessWithData($user, 'Get information account successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function changePassword(RequestChangePassword $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            if (!(Hash::check($request->get('current_password'), $user->password))) {
                return $this->responseError('Your password is incorrect !');
            }
            $data = ['password' => Hash::make($request->get('new_password'))];
            $user->update($data);
            DB::commit();

            return $this->responseSuccess('Password change successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function forgotSend(RequestSendForgot $request)
    {
        DB::beginTransaction();
        try {
            $email = $request->email;
            $findUser = User::where('email', $email)->first();
            if (empty($findUser)) {
                return $this->responseError('No account found in the system !', 400);
            }
            $token = Str::random(32);
            $role = 'user';
            $user = PasswordReset::where('email', $email)->where('role', $role)->first();
            if ($user) {
                $user->update(['token' => $token]);
            } else {
                PasswordReset::create(['email' => $email, 'token' => $token, 'role' => $role]);
            }
            $url = UserEnum::FORGOT_FORM_USER . $token;
            Log::info("Add jobs to Queue , Email: $email with URL: $url");
            Queue::push(new SendForgotPasswordEmail($email, $url));
            DB::commit();

            return $this->responseSuccess('Password reset email sent successfully, please check your email !', 201);
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function forgotUpdate(RequestCreatePassword $request)
    {
        DB::beginTransaction();
        try {
            $token = $request->token ?? '';
            $new_password = Hash::make($request->new_password);
            $passwordReset = PasswordReset::where('token', $token)->first();
            if ($passwordReset) {
                $user = User::where('email', $passwordReset->email);
                if ($passwordReset->role == 'user' && !empty($user)) {
                    $user->update(['password' => $new_password]);
                    $passwordReset->delete();

                    DB::commit();

                    return $this->responseSuccess('Reset password successfully !');
                } else {
                    return $this->responseError('Account not found !', 404);
                }
            } else {
                DB::commit();

                return $this->responseError('Token has expired !', 400);
            }
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function updateProfile(RequestUpdateProfileUser $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            if ($request->hasFile('avatar')) {
                // upload file
                $image = $request->file('avatar');
                $uploadedFile = Cloudinary::upload($image->getRealPath(), ['folder' => 'avatars', 'resource_type' => 'auto']);
                $avatar = $uploadedFile->getSecurePath();
                // delete old file
                if ($user->avatar) {
                    $id_file = explode('.', implode('/', array_slice(explode('/', $user->avatar), 7)))[0];
                    Cloudinary::destroy($id_file);
                }
                // upload profile
                $data = array_merge($request->all(), ['avatar' => $avatar]);
                $user->update($data);
            } else {
                $request['avatar'] = $user->avatar;
                $user->update($request->all());
            }

            DB::commit();

            return $this->responseSuccessWithData($user, 'Update profile successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function getInforChannel(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth('user_api')->user()->id);
            $channel = Channel::find($user->channel_id);
            if($user->role == 'user') {
                $channel->channel_id = null;
                $channel->channel_secret = null;
                $channel->channel_access_token = null;
            }
            DB::commit();

            return $this->responseSuccessWithData($channel, 'Get information channel successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function updateChannel(RequestUpdateChannel $request)
    {
        DB::beginTransaction();
        try {
            $channel = Channel::where('account_manager_id', auth('user_api')->user()->id)->first();
            $channel->update($request->all());
            DB::commit();

            return $this->responseSuccessWithData($channel, 'Update information channel successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function addMember(RequestAddMember $request)
    {
        DB::beginTransaction();
        try {
            $manager = User::find(auth('user_api')->user()->id);
            $new_password = Str::random(8);
            $data = array_merge($request->all(), [
                'password' => Hash::make($new_password),
                'role' => 'user',
                'is_delete' => 0,
                'is_block' => 0,
                'channel_id' => $manager->channel_id,
                'email_verified_at' => now(),
            ]);
            $member = User::create($data);
            $content = 'Below is your account information, please use it to log in to the system, then change your 
            password to ensure account security. <br> Email : <strong>' . $member->email .
            '</strong> <br> Password : <strong>' . $new_password . '</strong>';
            Queue::push(new SendMailNotify($member->email, $content));
            DB::commit();

            return $this->responseSuccessWithData($member, 'Added member account successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }

    public function getMembers(Request $request)
    {
        try {
            $manager = User::find(auth('user_api')->user()->id);
            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'name';
                    break;

                case 'address':
                    $orderBy = 'address';
                    break;

                case 'gender':
                    $orderBy = 'gender';
                    break;

                case 'phone':
                    $orderBy = 'phone';
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
                'channel_id' => $manager->channel_id,
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

            return $this->responseSuccessWithData($managers, 'Get members information successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function updateMember(RequestUpdateMember $request, $id_user)
    {
        DB::beginTransaction();
        try {
            $manager = User::find(auth('user_api')->user()->id);
            $member = User::find($id_user);
            $oldEmail = $member->email;
            if (empty($member)) {
                return $this->responseError('User Not Found !', 404);
            }
            if ($manager->channel_id != $member->channel_id || $manager->id == $member->id) {
                return $this->responseError('You have no rights !', 400);
            }
            $member->update($request->all());

            $content = 'Your information has been updated by the channel manager as follows. <br> Email : <strong>' . $member->email .
            '</strong> <br> Name : <strong>' . $member->name . '</strong>' . 
            '<br> LINE User ID : <strong>' . $member->line_user_id . '</strong>';
            Queue::push(new SendMailNotify($member->email, $content));
            if($oldEmail != $request->email) Queue::push(new SendMailNotify($oldEmail, $content));

            DB::commit();

            return $this->responseSuccessWithData($member, 'Update information member successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function changeIsDeleteMember(RequestChangeIsDeleteMember $request, $id_user)
    {
        DB::beginTransaction();
        try {
            $manager = User::find(auth('user_api')->user()->id);
            $member = User::find($id_user);
            if (empty($member)) {
                return $this->responseError('User Not Found !', 404);
            }
            if ($manager->channel_id != $member->channel_id || $manager->id == $member->id) {
                return $this->responseError('You have no rights !', 400);
            }
            $member->update(['is_delete' => $request->is_delete]);

            if($request->is_delete == 1) $content = '<strong style="color:red">Your account has been deleted by manager, if you think this is a mistake please contact the system !</strong>';
            else $content = '<strong style="color:green">Your account has been reinstated by the manager !</strong>';

            Queue::push(new SendMailNotify($member->email, $content));

            DB::commit();

            return $this->responseSuccessWithData($member, 'Change is delete user successful !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function changeIsDeleteManyMember(RequestChangeIsDeleteManyMember $request)
    {
        DB::beginTransaction();
        try {
            $manager = User::find(auth('user_api')->user()->id);
            User::whereIn('id', $request->ids_member)->where('channel_id', $manager->channel_id)
                ->where('id', '!=', $manager->id)->update(['is_delete' => $request->is_delete]);

            $users = User::whereIn('id', $request->ids_member)->where('channel_id', $manager->channel_id)
                ->where('id', '!=', $manager->id)->get();

            if($request->is_delete == 1) $content = '<strong style="color:red">Your account has been deleted by manager, if you think this is a mistake please contact the system !</strong>';
            else $content = '<strong style="color:green">Your account has been reinstated by the manager !</strong>';
            foreach($users as $user) Queue::push(new SendMailNotify($user->email, $content));

            DB::commit();

            return $this->responseSuccess('Change is delete many member successfully !');
        } catch (Throwable $e) {
            DB::rollback();

            return $this->responseError($e->getMessage());
        }
    }
}
