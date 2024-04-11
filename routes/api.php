<?php

use App\Http\Middleware\{
    AdminMiddleware,
    BouquetAuthorMiddleware,
    MediaAuthorMiddleware,
    MemorialAuthorMiddleware,
    UserMiddleware,
};
use App\Http\Controllers\API\V1\{
    AuthController,
    BouquetsController,
    BouquetTypesController,
    MediaController,
    MemorialsController,
    UsersController
};
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
], function () {
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('', 'register')->name('register');
            Route::post('me/session', 'login')->name('login');

            Route::middleware('auth:api')->group(function () {
                Route::post('logout', 'logout')->name('logout');
                Route::post('refresh', 'refresh')->name('refresh');
            });
        });

        Route::group([
            'middleware' => 'auth:api',
            'controller' => UsersController::class,
        ], function () {
            Route::get('me', 'shortDetails')->name('short-details');
            Route::get('{user}', 'shortDetails')->name('short-details');
            Route::group([
                'middleware' => UserMiddleware::class,
            ], function () {
                Route::get('me/details', 'fullDetails')->name('full-details');
                Route::get('{user}/details', 'fullDetails')->name('full-details');
                Route::post('me/avatar', 'uploadAvatar')->name('upload-avatar');
                Route::post('{user}/avatar', 'uploadAvatar')->name('upload-avatar');
                Route::put('{user}', 'update')->name('update');
            });
            Route::patch('{user}', 'destroy')
                 ->middleware(AdminMiddleware::class)
                 ->name('destroy');
        });
    });

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::group([
            'controller' => BouquetTypesController::class,
        ], function () {
            Route::get('bouquet-types', 'index')->name('bouquet-types');
        });

        Route::group([
            'controller' => BouquetsController::class,
        ], function () {
            Route::group([
                'prefix' => 'memorials',
                'as' => 'memorials.',
            ], function () {
                Route::get('{memorial}/bouquets', 'index')->name('bouquets');
                Route::post('{memorial}/bouquets', 'create')->name('create-bouquet');
            });

            Route::group([
                'middleware' => BouquetAuthorMiddleware::class,
            ], function () {
                Route::group([
                    'prefix' => 'bouquets',
                    'as' => 'bouquets.',
                ], function () {
                    Route::put('{bouquet}', 'update')->name('update');
                });
            });
        });

        Route::group([
            'controller' => MemorialsController::class,
            'as' => 'memorials.',
        ], function () {
            Route::group([
                'prefix' => 'memorials',
            ], function () {
                Route::post('create', 'create')->name('create');
                Route::get('{memorial}', 'show')->name('memorial');
                Route::group([
                    'middleware' => MemorialAuthorMiddleware::class,
                ], function () {
                    Route::put('{memorial}', 'update')->name('update');
                    Route::delete('{memorial}', 'destroy')->name('delete');
                    Route::patch('{memorial}', 'changeStatus')->name('change-status');
                    Route::post('{memorial}/avatar', 'uploadAvatar')->name('upload-avatar');
                });
            });
            Route::get('users/{user}/memorials', 'index')
                ->name('memorials');
        });

        // Media
        Route::group([
            'controller' => MediaController::class,
        ], function () {
            Route::group([
                'prefix' => 'media',
                'as' => 'media.',
            ], function () {
                Route::delete('{medium}', 'destroy')
                     ->middleware(MediaAuthorMiddleware::class)
                     ->name('delete');
            });

            // bouquets media
            Route::group([
                'prefix' => 'bouquets',
                'as' => 'bouquets.',
            ], function () {
                Route::post('{bouquet}/media', 'uploadBouquetMedium')
                     ->middleware(BouquetAuthorMiddleware::class)
                     ->name('upload-medium');
            });

            // memorial media
            Route::group([
                'prefix' => 'memorials',
                'as' => 'memorials.',
            ], function () {
                Route::group([
                    'middleware' => MemorialAuthorMiddleware::class,
                ], function () {
                    Route::post('{memorial}/media', 'uploadMemorialMedia')
                         ->name('upload-media');
                    Route::delete('{memorial}/media', 'destroyMemorialMedia')
                         ->name('delete-media');
                    Route::get('{memorial}/media', 'getMemorialMedia')
                         ->name('get-media');
                    Route::get('{memorial}/videos', 'getMemorialVideos')
                         ->name('get-videos');
                    Route::get('{memorial}/images', 'getMemorialImages')
                         ->name('get-images');
                });
                Route::delete('media/{medium}', 'destroyMemorialMedium')
                     ->name('delete-medium');
            });
        });
    });
});
