<?php

use App\Http\Controllers\Api\PostControllerRestFull;
use App\Http\Controllers\api\v1\PostController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('v1/posts/index', [PostController::class, 'index']);
Route::get('v1/posts/cari/{id}', [PostController::class, 'cariId']);
Route::post('v1/posts/store', [PostController::class, 'store']);
Route::post('v1/posts/update', [PostController::class, 'update']);
Route::delete('v1/posts/delete/{id}', [PostController::class, 'delete']);

// restful API
Route::get('v2/posts', [PostControllerRestFull::class, 'index']);
Route::post('v2/tambah_data', [PostControllerRestFull::class, 'tambahData']);
Route::get('v2/cari_data/{post_2}', [PostControllerRestFull::class, 'cariData']);
Route::post('v2/update_data/{post_2}', [PostControllerRestFull::class, 'updateData']);
Route::delete('v2/delete_data/{post_2}', [PostControllerRestFull::class, 'deleteData']);
