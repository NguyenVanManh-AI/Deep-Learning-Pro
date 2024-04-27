<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\ChannelStatisticalController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UploadFileS3Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::post('login', 'login');
    Route::middleware('check.auth:admin_api')->group(function () {
        Route::get('profile', 'profile');
        Route::get('logout', 'logout');
        Route::post('add-manager', 'addManager');
        Route::get('managers', 'getManagers');
        Route::post('block-manager/{id_user}', 'changeIsBlockManager');
        Route::post('block-many-manager', 'changeIsBlockManyManager');
    });
});

// User
Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('forgot-pw-sendcode', 'forgotSend');
    Route::post('forgot-update', 'forgotUpdate');
    Route::middleware('check.auth:user_api')->group(function () {
        Route::get('logout', 'logout');
        Route::get('profile', 'profile');
        Route::post('change-password', 'changePassword');
        Route::post('update', 'updateProfile');
        Route::get('members', 'getMembers');
        Route::get('infor-channel', 'getInforChannel');
    });
    Route::middleware(['check.auth:user_api', 'role:manager'])->group(function () {
        Route::post('update-channel', 'updateChannel');
        Route::post('add-member', 'addMember');
        Route::post('update-member/{id_user}', 'updateMember');
        Route::post('delete-member/{id_user}', 'changeIsDeleteMember');
        Route::post('delete-many-member', 'changeIsDeleteManyMember');
    });
});

// Content
Route::prefix('content')->controller(ContentController::class)->group(function () {
    Route::middleware('check.auth:user_api')->group(function () {
        Route::post('add', 'addContent');
        Route::get('detail/{id_content}', 'contentDetail');
        Route::get('all', 'getContents');
        Route::post('update/{id_content}', 'updateContent');
        Route::post('delete-content/{id_content}', 'changeIsDeleteContent');
        Route::post('delete-many-content', 'changeIsDeleteManyContent');
        Route::post('for-broadcast', 'getContentForBroadcast');
    });
});

// Broadcast
Route::prefix('broadcast')->controller(BroadcastController::class)->group(function () {
    Route::middleware('check.auth:user_api')->group(function () {
        Route::post('add', 'addBroadcast');
        Route::get('detail/{id_broadcast}', 'getDetailBroadcast');
        Route::post('update/{id_broadcast}', 'updateBroadcast');
        Route::post('send-now', 'sendNow');
        Route::get('all', 'getBroadcasts');
        Route::post('delete-broadcast/{id_broadcast}', 'changeIsDeleteBroadcast');
        Route::post('delete-many-broadcast', 'changeIsDeleteManyBroadcast');
        Route::post('test-send', 'testSend');
    });
});

Route::prefix('statistical')->controller(ChannelStatisticalController::class)->group(function () {
    Route::middleware(['check.auth:user_api', 'role:manager'])->group(function () {
        Route::get('/', 'Statistical');
    });
});

Route::prefix('s3')->controller(UploadFileS3Controller::class)->group(function () {
    Route::post('/upload', 'uploadFileToS3');
    Route::delete('/delete', 'deleteFileS3');
});

