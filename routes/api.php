<?php

use App\Http\Controllers\API\V1\{AuthController,
    BouquetsController,
    BouquetTypesController,
    Media\BouquetMediaController,
    Media\MemorialMediaController,
    MemorialsController,
    Payments\PaymentsController,
    Payments\WebhookController,
    UsersController};
use App\Http\Middleware\{
    AdminOrSelfMiddleware,
    BouquetAuthorMiddleware,
    MemorialAuthorOrViewerMiddleware,
    UserMiddleware
};
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
], function () {
    // Users
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
    ], function () {
        Route::group([
            'controller' => AuthController::class,
        ], function () {
            Route::post('', 'register')->name('register');
            Route::post('me/session', 'login')->name('login');

            Route::group([
                'middleware' => 'guest',
            ], function () {
                Route::post('forgot-password', 'forgotPassword')->name('password.email');
                Route::post('reset-password', 'resetPassword')->name('password.update');
            });

            Route::group([
                'middleware' => 'auth:api',
            ], function () {
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
                Route::post('me/avatar', 'uploadAvatar')->name('upload-avatar');
                Route::post('{user}/avatar', 'uploadAvatar')->name('upload-avatar');
                Route::put('{user}', 'update')->name('update');
            });
            Route::patch('{user}', 'destroy')
                 ->middleware(AdminOrSelfMiddleware::class)
                 ->name('destroy');
        });
    });

    // Bouquet types
    Route::group([
        'middleware' => 'auth:api',
        'controller' => BouquetTypesController::class,
    ], function () {
        Route::get('bouquet-types', 'index')->name('bouquet-types');
    });

    // Bouquets
    Route::group([
        'middleware' => 'auth:api',
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
                Route::get('{bouquet}/payment-info', 'paymentInfo')->name('payment-info');
            });
        });
    });

    // Memorials
    Route::group([
        'middleware' => 'auth:api',
        'controller' => MemorialsController::class,
        'as' => 'memorials.',
    ], function () {
        Route::group([
            'prefix' => 'memorials',
        ], function () {
            Route::post('create', 'create')->name('create');
            Route::get('{memorial}', 'show')->name('memorial');
            Route::group([
                'middleware' => MemorialAuthorOrViewerMiddleware::class,
            ], function () {
                Route::put('{memorial}', 'update')->name('update');
                Route::delete('{memorial}', 'destroy')->name('delete');
                Route::patch('{memorial}', 'changeStatus')->name('change-status');
                Route::post('{memorial}/avatar', 'uploadAvatar')->name('upload-avatar');
            });
            Route::post('{memorial}/bind-viewer', 'bindViewer')->name('bind-viewer');
        });
        Route::get('users/{user}/memorials', 'index')
             ->name('memorials');
    });

    // Memorials media
    Route::group([
        'middleware' => 'auth:api',
        'controller' => MemorialMediaController::class,
        'prefix'     => 'memorials',
        'as'         => 'memorials.',
    ], function () {
        Route::group([
            'middleware' => MemorialAuthorOrViewerMiddleware::class,
        ], function () {
            Route::post('{memorial}/medium', 'uploadMedium')
                 ->name('upload-medium');
            Route::post('{memorial}/media', 'uploadMedia')
                 ->name('upload-media');
            Route::delete('{memorial}/media', 'destroyMedia')
                 ->name('delete-media');
            Route::get('{memorial}/media', 'getMedia')
                 ->name('get-media');
            Route::get('{memorial}/videos', 'getVideos')
                 ->name('get-videos');
            Route::get('{memorial}/images', 'getImages')
                 ->name('get-images');
        });
        Route::group([
            'prefix' => 'media',
            'as' => 'media.',
        ], function () {
            Route::delete('{medium}', 'destroyMedium')
                 ->name('delete-medium');
            Route::delete('{memorial}', 'destroyMedia')
                 ->name('delete-media');
        });
    });

    // Bouquets media
    Route::group([
        'middleware' => 'auth:api',
        'controller' => BouquetMediaController::class,
    ], function () {
        Route::group([
            'prefix' => 'media',
            'as' => 'media.',
        ], function () {
            Route::delete('{medium}', 'destroyMedium')
                 ->name('delete-medium');
            Route::delete('{bouquet}', 'destroyMedia')
                 ->name('delete-media');
        });

        Route::group([
            'middleware' => BouquetAuthorMiddleware::class,
            'prefix' => 'bouquets',
            'as' => 'bouquets.',
        ], function () {
            Route::post('{bouquet}/medium', 'uploadMedium')
                 ->name('upload-medium');
            Route::post('{bouquet}/media', 'uploadMedia')
                 ->name('upload-media');
            Route::get('{bouquet}/media', 'getMedia')
                 ->name('get-media');
            Route::get('{bouquet}/videos', 'getVideos')
                 ->name('get-videos');
            Route::get('{bouquet}/images', 'getImages')
                 ->name('get-images');
        });
    });

    // Payments
    Route::group([
        'prefix' => 'payments',
        'as' => 'payments.',
    ], function () {
        Route::group([
            'middleware' => 'auth:api',
            'controller' => PaymentsController::class,
        ], function () {
            Route::post('bouquet-payment', 'bouquet')->name('bouquet-payment');
            Route::post('memorial-payment', 'memorial')->name('memorial-payment');
        });

        Route::group([
            'controller' => WebhookController::class,
        ], function () {
            Route::post('webhook', 'handle')->name('webhook');
        });
    });
});
