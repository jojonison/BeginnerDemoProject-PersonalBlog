<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/logIn', [AuthenticationController::class, 'login']);
Route::get('/list-articles', [ArticleController::class, 'listLatestArticles']);
Route::get('/show-article/{id}', [ArticleController::class, 'showArticle']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/create-article', [ArticleController::class, 'createArticle']);
    Route::post('/update-article/{id}', [ArticleController::class, 'editArticle']);
    Route::delete('/destroy-article/{id}', [ArticleController::class, 'destroyArticle']);
    Route::post('restore-article/{id}', [ArticleController::class, 'restoreArticle']);
    Route::get('/my-articles', [ArticleController::class, 'listOwnArticles']);

    Route::get('/list-categories', [CategoryController::class, 'listCategories']);
    Route::post('/create-category', [CategoryController::class, 'createCategory']);
    Route::delete('/destroy-category/{id}', [CategoryController::class, 'destroyCategory']);

    Route::get('/list-tags', [TagController::class, 'listTags']);
    Route::post('/create-tag', [TagController::class, 'createTag']);
    Route::delete('/destroy-tag/{id}', [TagController::class, 'destroyTag']);
});

Route::middleware(['auth:sanctum', 'role_or_permission:user|admin'])->group(function () {
    Route::post('/logOut', [AuthenticationController::class, 'logout']);
});

