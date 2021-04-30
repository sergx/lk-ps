<?php

use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//   return $request->user();
// });




Route::post('/user/auth-data', [UserController::class, 'authData']);
Route::post('/quiz', [QuizController::class, 'index']);

Route::middleware(['role:Admin'])->group(
  function () {
    Route::post('/permissions-and-roles/', [PermissionController::class, 'getPermissionsAndRoles']);
    Route::post('/permissions-and-roles/permission/add', [PermissionController::class, 'addPermission']);
    Route::post('/permissions-and-roles/role/add', [PermissionController::class, 'addRole']);
    Route::post('/permissions-and-roles/assign-permission-to-role', [PermissionController::class, 'assignPermissionToRole']);
    Route::post('/permissions-and-roles/revoke-permission-from-role', [PermissionController::class, 'revokePermissionFromRole']);
    Route::post('/permissions-and-roles/remove-permission', [PermissionController::class, 'removePermission']);

    Route::post('/user/create', [UserController::class, 'create']);
    Route::post('/user/get-users-with-roles', [UserController::class, 'getUsersWithRoles']);
    Route::post('/user/toggle-user-role', [PermissionController::class, 'toggleUserRole']);
    Route::post('/user/{id}/data', [UserController::class, 'userData']);
    Route::post('/user/{id}/update', [UserController::class, 'update']);
    Route::post('/user/{id}/destroy', [UserController::class, 'destroy']);

    Route::post('/quiz/file-upload', [QuizController::class, 'fileUpload']);
    Route::post('/quiz/save', [QuizController::class, 'save']);
    Route::post('/quiz/{id}/edit', [QuizController::class, 'edit']);
    Route::post('/quiz/{id}/show', [QuizController::class, 'show']);
    Route::post('/quiz/{id}/destroy', [QuizController::class, 'destroy']);

    Route::post('/quiz-answer', [QuizAnswerController::class, 'index']);
    Route::post('/quiz-answer/save', [QuizAnswerController::class, 'save']);
    Route::post('/quiz-answer/show', [QuizAnswerController::class, 'show']);
    Route::post('/quiz-answer/{id}/destroy', [QuizAnswerController::class, 'destroy']);
  }
);






    //Route::post('/quiz/store', [QuizController::class, 'store']);
