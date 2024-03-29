<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'api\v2', 'prefix' => 'v2', 'middleware' => ['api_lang']], function () {
    Route::get('panduan', 'seller\AttributeController@howToUse');
    Route::group(['prefix' => 'seller', 'namespace' => 'seller'], function () {
        Route::get('seller-info', 'SellerController@seller_info');
        Route::post('cm-firebase-token', 'SellerController@update_cm_firebase_token');
        Route::get('shop-product-reviews', 'SellerController@shop_product_reviews');
        Route::post('seller-update', 'SellerController@seller_info_update');
        Route::get('monthly-earning', 'SellerController@monthly_earning');
        Route::get('monthly-commission-given', 'SellerController@monthly_commission_given');

        Route::get('shop-info', 'SellerController@shop_info');
        Route::get('transactions', 'SellerController@transaction');
        Route::put('shop-update', 'SellerController@shop_info_update');

        Route::post('balance-withdraw', 'SellerController@withdraw_request');
        Route::delete('close-withdraw-request', 'SellerController@close_withdraw_request');

        Route::group(['prefix' => 'products'], function () {
            Route::post('upload-images', 'ProductController@upload_images');
            Route::post('add', 'ProductController@add_new');
            Route::get('list', 'ProductController@list');
            Route::get('edit/{id}', 'ProductController@edit');
            Route::put('update/{id}', 'ProductController@update');
            Route::delete('delete/{id}', 'ProductController@delete');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('list', 'OrderController@list');
            Route::get('/{id}', 'OrderController@details');
            Route::get('generate_invoice/{id}', 'OrderController@generate_invoice');
            Route::post('order-detail-status/{id}', 'OrderController@order_detail_status');
        });

        Route::group(['prefix' => 'booked'], function () {
            Route::get('list', 'BookedController@list');
            Route::get('detail/{id}', 'BookedController@detail');
        });

        Route::group(['prefix' => 'area'], function () {
            Route::get('province', 'AreaController@province');
            Route::get('city', 'AreaController@city');
            Route::get('district', 'AreaController@district');
        });

        Route::group(['prefix' => 'jobs'], function () {
            Route::get('list', 'JobsController@list');
            Route::post('add_job', 'JobsController@create');
            Route::post('edit_job', 'JobsController@update');
            Route::get('delete_job', 'JobsController@destroy');
            Route::post('update_job_status', 'JobsController@status_update');

            Route::get('list_applied', 'JobsController@applied');
            Route::post('apply_status', 'JobsController@apply_status');
        });

        Route::group(['prefix' => 'room'], function () {
            Route::get('room-detail/{id}', 'RoomController@roomDetail');
            Route::post('add-room', 'RoomController@addRoom');
            Route::post('room-status', 'RoomController@room_update');
            Route::get('delete-room/{id}', 'RoomController@deleteRoom');
        });

        Route::group(['prefix' => 'kost'], function () {
            Route::get('list', 'KostController@list');
            Route::post('add', 'KostController@create');
            Route::get('delete', 'KostController@destroy');
            Route::post('update', 'KostController@update');
        });

        Route::group(['prefix' => 'attribute'], function () {
            Route::get('fasilitas_kost', 'AttributeController@FasilitasKost');
            Route::get('fasilitas_kamar', 'AttributeController@FasilitasKamar');
            Route::get('category', 'AttributeController@category');
            Route::get('kampus', 'AttributeController@kampus');
            Route::get('aturan', 'AttributeController@rule');
        });

        Route::group(['prefix' => 'shipping-method'], function () {
            Route::get('list', 'ShippingMethodController@list');
            Route::post('add', 'ShippingMethodController@store');
            Route::get('edit/{id}', 'ShippingMethodController@edit');
            Route::put('status', 'ShippingMethodController@status_update');
            Route::put('update/{id}', 'ShippingMethodController@update');
            Route::delete('delete/{id}', 'ShippingMethodController@delete');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('list', 'ChatController@messages');
            Route::post('send', 'ChatController@send_message');
        });

        Route::group(['prefix' => 'auth', 'namespace' => 'auth'], function () {
            Route::post('login', 'LoginController@login');
            Route::post('register', 'RegisterController@registerSeller');
        });
    });
    Route::post('ls-lib-update', 'LsLibController@lib_update');
});
