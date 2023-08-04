<?php

use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => config('property.property_route_prefix')], function () {
    Route::get('/', 'PropertyController@index')->name('property.search'); // Search
    Route::get('/map', 'PropertyController@index')->name('property.search._layout'); // Search
    Route::get('/{slug}', 'PropertyController@detail')->name('property.detail'); // Detail

    Route::get('/', 'PropertyController@dokan_index')->name('property.search'); // Search
    Route::get('/{slug}/{dokan_slug}', 'PropertyController@dokan_detail')->name('property.detail'); // Detail
    Route::get('/{slug}/{dokan_slug}/{product_slug}', 'PropertyController@product_detail')->name('property.detail'); // Detail

    Route::get('/search/searchForSelect2', 'PropertyController@searchForSelect2')->name("property.searchForSelect");; // Search for select 2

});


Route::group(['prefix' => 'user/' . config('property.property_route_prefix'), 'middleware' => ['auth', 'verified']], function () {
    Route::match(['get'], '/', 'ManagePropertyController@manageProperty')->name('property.vendor.index');
    Route::match(['get'], '/create', 'ManagePropertyController@createProperty')->name('property.vendor.create');
    Route::match(['get'], '/edit/{id}', 'ManagePropertyController@editProperty')->name('property.vendor.edit');
    Route::match(['get', 'post'], '/del/{id}', 'ManagePropertyController@deleteProperty')->name('property.vendor.delete');

    Route::match(['post'], '/store/{id}', 'ManagePropertyController@store')->name('property.vendor.store');
    Route::get('bulkEdit/{id}', 'ManagePropertyController@bulkEditProperty')->name("property.vendor.bulk_edit");
    Route::get('/booking-report', 'ManagePropertyController@bookingReport')->name("property.vendor.booking_report");
    Route::get('/booking-report/bulkEdit/{id}', 'ManagePropertyController@bookingReportBulkEdit')->name("property.vendor.booking_report.bulk_edit");
    Route::get('clone/{id}', 'ManagePropertyController@cloneProperty')->name("property.vendor.clone");
    Route::get('/contact', 'ManagePropertyController@showContact')->name('property.vendor.contact');
    Route::group(['prefix' => 'availability'], function () {
        Route::get('/', 'AvailabilityController@index')->name('property.vendor.availability.index');
        Route::get('/loadDates', 'AvailabilityController@loadDates')->name('property.vendor.availability.loadDates');
        Route::post('/store', 'AvailabilityController@store')->name('property.vendor.availability.store');
    });
    Route::group(['prefix' => 'dokan'], function () {
        Route::get('{property_id}/index', 'VendorDokanController@index')->name('property.vendor.dokan.index');
        Route::get('{property_id}/create', 'VendorDokanController@create')->name('property.vendor.dokan.create');
        Route::get('{property_id}/edit/{id}', 'VendorDokanController@edit')->name('property.vendor.dokan.edit');
        Route::post('{property_id}/store/{id}', 'VendorDokanController@store')->name('property.vendor.dokan.store');
        Route::get('{property_id}/del/{id}', 'VendorDokanController@delete')->name('property.vendor.dokan.delete');
        Route::get('{property_id}/bulkEdit/{id}', 'VendorDokanController@bulkEdit')->name('property.vendor.dokan.bulk_edit');

        Route::group(['prefix' => 'product'], function () {
            Route::get('{dokan_id}/index', 'VendorProductController@index')->name('property.vendor.product.index');
            Route::get('{dokan_id}/create', 'VendorProductController@create')->name('property.vendor.product.create');
            Route::get('{dokan_id}/edit/{id}', 'VendorProductController@edit')->name('property.vendor.product.edit');
            Route::post('{dokan_id}/store/{id}', 'VendorProductController@store')->name('property.vendor.product.store');
            Route::get('{dokan_id}/del/{id}', 'VendorProductController@delete')->name('property.vendor.product.delete');
            Route::get('{dokan_id}/bulkEdit/{id}', 'VendorProductController@bulkEdit')->name('property.vendor.product.bulk_edit');
        });
    });
});

Route::group(['prefix' => 'user/' . config('property.property_route_prefix')], function () {
    Route::group(['prefix' => 'availability'], function () {
        Route::get('/', 'AvailabilityController@index')->name('property.vendor.dokan.availability.index');
        Route::get('/loadDates', 'AvailabilityController@loadDates')->name('property.vendor.dokan.availability.loadDates');
        Route::match(['post'], '/store', 'AvailabilityController@store')->name('property.vendor.dokan.availability.store');
    });
});

Route::get('/category', 'CategoryController@index')->name('property.category.index');

Route::group(['prefix' => config('property.property_cat_route_prefix')], function () {
    Route::get('/{slug}', 'CategoryController@detail')->name('property.category.detail');
});

Route::get('/attribute', 'AttributeController@index')->name('property.attribute.index');

Route::get('/collections', 'PropertyController@product_index')->name('property.product.index');

Route::group(['prefix' => config('property.property_attribute_route_prefix')], function () {
    Route::get('/{slug}', 'AttributeController@detail')->name('property.attribute.detail');
    Route::get('/{slug}/{term_slug}', 'AttributeController@term_detail')->name('property.attribute.term-detail');

    Route::get('getForSelect2', 'AttributeController@getForSelect2')->name('property.attribute.term.getForSelect2');
});
