<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UsersControllers;
use App\Http\Controllers\Api\CategoriesControllers;
use App\Http\Controllers\Api\OffersControllers;
use App\Http\Controllers\Api\LocationsControllers;
/*
|--------------------------------------------------------------------------
| API Routes 
|--------------------------------------------------------------------------
|
|
*/

Route::prefix('v1')->group(function () {

    /*
    | Public Routes Her
    */
    Route::post('/register', [AuthController::class, 'register']);
    Route::prefix('oauth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    /*
    | Protected Routes 
    */
    Route::middleware('auth:api')->group(function () {

        /*
        | Users Routes
        */
        Route::prefix('users')->group(function () {
            Route::get('/get/me', [UsersControllers::class, 'show']);
            Route::put('/update/me', [UsersControllers::class, 'update']);
        });

        /*
        | Categories Routes
        */
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoriesControllers::class, 'index']);
        });

        /*
        | Offers Routes
        */
        Route::prefix('offers')->group(function () {
            Route::get('/', [OffersControllers::class, 'index']);
            Route::get('/favoris/me', [OffersControllers::class, 'favori']);
            Route::get('/favoris/me/ids', [OffersControllers::class, 'favorionlyid']);
            Route::post('/favoris/add', [OffersControllers::class, 'add']);
            Route::post('/favoris/remove', [OffersControllers::class, 'remove']);
        });

        /*
        | Locations Routes
        */
        Route::prefix('locations')->group(function () {
            Route::get('/', [LocationsControllers::class, 'index']);
        });
    });
});
