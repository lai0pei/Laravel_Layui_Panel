<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Foundation\Auth\AuthMenuController;
use App\Http\Controllers\Foundation\Auth\AuthPermissionController;
use App\Http\Controllers\Foundation\Index\IndexController;
use App\Http\Controllers\Foundation\Auth\LoginController;
use App\Http\Controllers\Foundation\Auth\RoleController;
use App\Http\Controllers\Foundation\Auth\AdminController;
use App\Http\Controllers\Foundation\System\FileController;
use App\Http\Controllers\Foundation\System\LogController;
use App\Http\Controllers\Foundation\System\SysLogController;
use App\Http\Controllers\Foundation\System\SysConfigController;


$secure = config('conf.secure_url');


Route::prefix($secure)->group(function(){
    Route::get('/f_captcha', [LoginController::class, 'captcha'])->name('f_captcha');
    Route::post('/login_action', [LoginController::class, 'login'])->name('f_login_action');
});

Route::middleware(['backendAuth'])->prefix($secure)->group(function () {

    Route::get('/login', [LoginController::class, 'index'])->name('f_login');
    Route::get('/', [IndexController::class, 'index'])->name('f_home');
    Route::get('/blank', [IndexController::class, 'blank'])->name('f_blank');
    Route::prefix('/index')->group(function () {
        Route::get('/selfPassView', [IndexController::class, 'selfPasswordView']);
        Route::post('/getMenu', [IndexController::class, 'menu'])->name('auth_menu');
        Route::post('/clearCache', [IndexController::class, 'clearCache'])->name('f_clear_cache');
        Route::post('/toSelfPassword', [IndexController::class, 'setSelfPassword']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('f_logout');
    });



    Route::prefix('auth')->group(
        function () {
            Route::get('/', [AuthMenuController::class, 'index'])->name('f_auth_menu');

            Route::get('/addView', [AuthMenuController::class, 'add_view']);
            Route::get('/updateView', [AuthMenuController::class, 'update_view']);
            Route::get('/view', [AuthMenuController::class, 'view']);

            Route::post('/toList', [AuthMenuController::class, 'listControl']);
            Route::post('/toUpdate', [AuthMenuController::class, 'updateControl']);
            Route::post('/toAdd', [AuthMenuController::class, 'addControl']);
            Route::post('/toDelete', [AuthMenuController::class, 'deleteControl']);
        }
    );

    Route::prefix('permission')->group(function () {
        Route::get('/', [AuthPermissionController::class, 'index'])->name('f_auth_permission');

        Route::get('/addView', [AuthPermissionController::class, 'add_view']);
        Route::get('/updateView', [AuthPermissionController::class, 'update_view']);
        Route::get('/view', [AuthPermissionController::class, 'view']);

        Route::post('/toList', [AuthPermissionController::class, 'listControl']);
        Route::post('/toUpdate', [AuthPermissionController::class, 'updateControl']);
        Route::post('/toAdd', [AuthPermissionController::class, 'addControl']);
        Route::post('/toDelete', [AuthPermissionController::class, 'deleteControl']);
        Route::post('/toDeleteBulk', [AuthPermissionController::class, 'deleteBulkControl']);
    });

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('f_auth_role');

        Route::get('/addView', [RoleController::class, 'add_view']);
        Route::get('/updateView', [RoleController::class, 'update_view']);
        Route::get('/view', [RoleController::class, 'view']);
        Route::get('/authView', [RoleController::class, 'authView']);

        Route::post('/toList', [RoleController::class, 'listControl']);
        Route::post('/toUpdate', [RoleController::class, 'updateControl']);
        Route::post('/toAdd', [RoleController::class, 'addControl']);
        Route::post('/toDelete', [RoleController::class, 'deleteControl']);
        Route::post('/toDeleteBulk', [RoleController::class, 'deleteBulkControl']);
        Route::post('/toSetAuth', [RoleController::class, 'setAuthControl']);
    });

    Route::prefix('admin')->group(
        function () {
            Route::get('/', [AdminController::class, 'index'])->name('f_admin');

            Route::get('/addView', [AdminController::class, 'add_view']);
            Route::get('/updateView', [AdminController::class, 'update_view']);
            Route::get('/view', [AdminController::class, 'view']);

            Route::get('/allPasswordView', [AdminController::class, 'allPasswordView']);

            Route::post('/toList', [AdminController::class, 'listControl']);
            Route::post('/toUpdate', [AdminController::class, 'updateControl']);
            Route::post('/toAdd', [AdminController::class, 'addControl']);
            Route::post('/toDelete', [AdminController::class, 'deleteControl']);

            Route::post('/toSetPassword', [AdminController::class, 'setPassword']);
        }
    );

    Route::prefix('file')->group(
        function () {
            Route::get('/', [FileController::class, 'index'])->name('f_system_file');

            Route::get('/addView', [FileController::class, 'add_view']);
            Route::get('/updateView', [FileController::class, 'update_view']);
            Route::get('/view', [FileController::class, 'view']);

            Route::post('/toList', [FileController::class, 'listControl']);
            Route::post('/toUpdate', [FileController::class, 'updateControl']);
            Route::post('/toAdd', [FileController::class, 'addControl']);
            Route::post('/toDelete', [FileController::class, 'deleteControl']);
            Route::post('/toDeleteBulk', [FileController::class, 'deleteBulkControl']);
            Route::post('/toUpload', [FileController::class, 'toUpload']);
            Route::get('/toDownload', [FileController::class, 'toDownload']);
            Route::post('/toDeleteFile', [FileController::class, 'toDeleteFile']);

            Route::get('/commonUploadView', [FileController::class, 'commonUploadView'])->name('f_commonUpload');

        }
    );


    Route::prefix('adminLog')->group(
        function () {
            Route::get('/', [LogController::class, 'index'])->name('f_admin_log');
            Route::get('/view', [LogController::class, 'view']);

            Route::post('/toList', [LogController::class, 'listControl']);
            Route::post('/toDelete', [LogController::class, 'deleteControl']);
            Route::post('/toDeleteBulk', [LogController::class, 'deleteBulkControl']);
        }
    );

    Route::prefix('sysLog')->group(
        function () {
            Route::get('/', [SysLogController::class, 'index'])->name('f_system_log');
            Route::get('/view', [SysLogController::class, 'view']);

            Route::post('/toList', [SysLogController::class, 'listControl']);
            Route::post('/toDelete', [SysLogController::class, 'deleteControl']);
            Route::post('/toDeleteBulk', [SysLogController::class, 'deleteBulkControl']);
        }
    );

    Route::prefix('sysConfig')->group(
        function () {
            Route::get('/', [SysConfigController::class, 'index'])->name('f_system_config');
            Route::post('/toSysConfig', [SysConfigController::class, 'toSysConfig']);
        }
    );
});
   

// Route::fallback(function () {
//     return view('foundation/index/index');
// });
