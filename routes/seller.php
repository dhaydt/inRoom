<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Seller', 'prefix' => 'seller', 'as' => 'seller.'], function () {
    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('forgot-password', 'ForgotPasswordController@forgot_password')->name('forgot-password');
        Route::post('forgot-password', 'ForgotPasswordController@reset_password_request');
        Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', 'ForgotPasswordController@reset_password_submit');
    });

    /*authenticated*/
    Route::group(['middleware' => ['seller']], function () {
        //dashboard routes

        Route::get('pending', 'DashboardController@counterOrder')->name('rent-pending');
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('dashboard', 'DashboardController@dashboard');
            Route::get('/', 'DashboardController@dashboard')->name('index');
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
            Route::post('business-overview', 'DashboardController@business_overview')->name('business-overview');
        });

        // occunpant management
        Route::group(['prefix' => 'booked', 'as' => 'booked.'], function () {
            Route::get('list/{status}', 'BookedController@list')->name('list');
            Route::get('detail/{order}', 'BookedController@detail')->name('detail');
        });

        Route::group(['prefix' => 'property', 'as' => 'property.'], function () {
            Route::get('list', 'KostController@index')->name('list');
            Route::get('new-property', 'KostController@create')->name('add-new');
            Route::post('new-property', 'KostController@store')->name('store');
            Route::get('edit-property/{id}', 'KostController@edit')->name('edit');
            Route::post('update-property/{id}', 'KostController@update')->name('update');
            Route::delete('delete/{id}', 'KostController@destroy')->name('delete');
        });

        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::post('image-upload', 'ProductController@imageUpload')->name('image-upload');
            Route::post('add-room', 'ProductController@add_room')->name('add-room');
            Route::post('del-room/{id}', 'ProductController@del_room')->name('del-room');
            Route::get('remove-image', 'ProductController@remove_image')->name('remove-image');
            Route::get('add-new', 'ProductController@add_new')->name('add-new');
            Route::post('change-room', 'ProductController@change_room')->name('change-room');
            Route::post('add-new', 'ProductController@store');
            Route::post('status-update', 'ProductController@status_update')->name('status-update');
            Route::post('room-update', 'ProductController@room_update')->name('room-update');
            Route::get('list', 'ProductController@list')->name('list');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('update/{id}', 'ProductController@update')->name('update');
            Route::post('sku-combination', 'ProductController@sku_combination')->name('sku-combination');
            Route::get('get-categories', 'ProductController@get_categories')->name('get-categories');

            Route::delete('delete/{id}', 'ProductController@delete')->name('delete');

            Route::get('view/{id}', 'ProductController@view')->name('view');
            Route::get('bulk-import', 'ProductController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'ProductController@bulk_import_data');
            Route::get('bulk-export', 'ProductController@bulk_export_data')->name('bulk-export');
        });

        Route::group(['prefix' => 'jobs', 'as' => 'jobs.'], function () {
            Route::get('list', 'JobController@index')->name('list');
            Route::get('create', 'JobController@create')->name('add-new');
            Route::get('approve-status', 'JobController@approve_status')->name('approve-status');
            Route::post('status-update', 'JobController@status_update')->name('status-update');
            Route::post('store', 'JobController@store')->name('store');
            Route::get('edit/{id}', 'JobController@edit')->name('edit');
            Route::post('update/{id}', 'JobController@update')->name('update');
            Route::delete('delete/{id}', 'JobController@destroy')->name('delete');
            Route::get('applied', 'JobController@applied')->name('applied');

            Route::get('details/{order_id}', 'JobController@details_applied')->name('details');
            Route::post('apply_status', 'JobController@apply_status')->name('apply_status');
        });

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::post('manual', 'OrderController@manual_upload')->name('manual-payment');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('status', 'OrderController@status')->name('status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::post('payment-status', 'OrderController@payment_status')->name('payment-status');
        });
        //Product Reviews

        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::get('list', 'ReviewsController@list')->name('list');
        });

        // Messaging
        Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
            Route::get('/chat', 'ChattingController@chat')->name('chat');
            Route::get('/message-by-user', 'ChattingController@message_by_user')->name('message_by_user');
            Route::post('/seller-message-store', 'ChattingController@seller_message_store')->name('seller_message_store');
        });
        // profile

        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            Route::get('update/{id}', 'ProfileController@edit')->name('update');
            Route::post('update/{id}', 'ProfileController@update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');

            Route::get('bank-edit/{id}', 'ProfileController@bank_edit')->name('bankInfo');
            Route::post('bank-update/{id}', 'ProfileController@bank_update')->name('bank_update');
        });
        Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
            Route::get('view', 'ShopController@view')->name('view');
            Route::get('edit/{id}', 'ShopController@edit')->name('edit');
            Route::post('update/{id}', 'ShopController@update')->name('update');
        });

        Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
            Route::post('request', 'WithdrawController@w_request')->name('request');
            Route::delete('close/{id}', 'WithdrawController@close_request')->name('close');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            Route::group(['prefix' => 'shipping-method', 'as' => 'shipping-method.'], function () {
                Route::get('add', 'ShippingMethodController@index')->name('add');
                Route::post('add', 'ShippingMethodController@store');
                Route::get('edit/{id}', 'ShippingMethodController@edit')->name('edit');
                Route::put('update/{id}', 'ShippingMethodController@update')->name('update');
                Route::post('delete', 'ShippingMethodController@delete')->name('delete');
                Route::post('status-update', 'ShippingMethodController@status_update')->name('status-update');
            });

            Route::group(['prefix' => 'withdraw', 'as' => 'withdraw.'], function () {
                Route::get('list', 'WithdrawController@list')->name('list');
                Route::get('cancel/{id}', 'WithdrawController@close_request')->name('cancel');
                Route::post('status-filter', 'WithdrawController@status_filter')->name('status-filter');
            });
        });
    });
});
