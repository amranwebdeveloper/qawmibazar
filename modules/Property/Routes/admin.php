<?php

use \Illuminate\Support\Facades\Route;


Route::get('/','PropertyController@index')->name('property.admin.index');
Route::get('/create','PropertyController@create')->name('property.admin.create');
Route::get('/edit/{id}','PropertyController@edit')->name('property.admin.edit');
Route::post('/store/{id}','PropertyController@store')->name('property.admin.store');
Route::post('/bulkEdit','PropertyController@bulkEdit')->name('property.admin.bulkEdit');
Route::post('/bulkEdit','PropertyController@bulkEdit')->name('property.admin.bulkEdit');

Route::get('/contact','PropertyController@showContact')->name('property.admin.contact');

Route::group(['prefix'=>'attribute'],function (){
    Route::get('/','AttributeController@index')->name('property.admin.attribute.index');
    Route::get('edit/{id}','AttributeController@edit')->name('property.admin.attribute.edit');
    Route::post('store/{id}','AttributeController@store')->name('property.admin.attribute.store');

    Route::get('terms/{id}','AttributeController@terms')->name('property.admin.attribute.term.index');
    Route::get('term_edit/{id}','AttributeController@term_edit')->name('property.admin.attribute.term.edit');
    Route::get('term_store','AttributeController@term_store')->name('property.admin.attribute.term.store');

    Route::get('getForSelect2','AttributeController@getForSelect2')->name('property.admin.attribute.term.getForSelect2');
});

Route::group(['prefix'=>'dokan'],function (){

    Route::group(['prefix'=>'attribute'],function (){
        Route::get('/','DokanAttributeController@index')->name('property.admin.dokan.attribute.index');
        Route::get('edit/{id}','DokanAttributeController@edit')->name('property.admin.dokan.attribute.edit');
        Route::post('store/{id}','DokanAttributeController@store')->name('property.admin.dokan.attribute.store');
        Route::post('editAttrBulk','DokanAttributeController@editAttrBulk')->name('property.admin.dokan.attribute.editAttrBulk');

        Route::get('terms/{id}','DokanAttributeController@terms')->name('property.admin.dokan.attribute.term.index');
        Route::get('term_edit/{id}','DokanAttributeController@term_edit')->name('property.admin.dokan.attribute.term.edit');
        Route::post('term_store','DokanAttributeController@term_store')->name('property.admin.dokan.attribute.term.store');

        Route::get('getForSelect2','DokanAttributeController@getForSelect2')->name('property.admin.dokan.attribute.term.getForSelect2');
    });
    Route::get('{property_id}/index','DokanController@index')->name('property.admin.dokan.index');
    Route::get('{property_id}/create','DokanController@create')->name('property.admin.dokan.create');
    Route::get('{property_id}/edit/{id}','DokanController@edit')->name('property.admin.dokan.edit');
    Route::post('{property_id}/store/{id}','DokanController@store')->name('property.admin.dokan.store');
    Route::post('/bulkEdit','DokanController@bulkEdit')->name('property.admin.dokan.bulkEdit');
});

Route::group(['prefix'=>'product'],function (){

    Route::group(['prefix'=>'attribute'],function (){
        Route::get('/','ProductAttributeController@index')->name('property.admin.product.attribute.index');
        Route::get('edit/{id}','ProductAttributeController@edit')->name('property.admin.product.attribute.edit');
        Route::post('store/{id}','ProductAttributeController@store')->name('property.admin.product.attribute.store');
        Route::post('editAttrBulk','ProductAttributeController@editAttrBulk')->name('property.admin.product.attribute.editAttrBulk');

        Route::get('terms/{id}','ProductAttributeController@terms')->name('property.admin.product.attribute.term.index');
        Route::get('term_edit/{id}','ProductAttributeController@term_edit')->name('property.admin.product.attribute.term.edit');
        Route::post('term_store','ProductAttributeController@term_store')->name('property.admin.product.attribute.term.store');

        Route::get('getForSelect2','ProductAttributeController@getForSelect2')->name('property.admin.product.attribute.term.getForSelect2');
    });
    Route::get('{dokan_id}/index','ProductController@index')->name('property.admin.product.index');
    Route::get('{dokan_id}/create','ProductController@create')->name('property.admin.product.create');
    Route::get('{dokan_id}/edit/{id}','ProductController@edit')->name('property.admin.product.edit');
    Route::post('{dokan_id}/store/{id}','ProductController@store')->name('property.admin.product.store');
    Route::post('/bulkEdit','ProductController@bulkEdit')->name('property.admin.product.bulkEdit');
});
// Route::group(['prefix'=>'availability'],function(){
//     Route::get('/','AvailabilityController@index')->name('property.admin.availability.index');
//     Route::get('/loadDates','AvailabilityController@loadDates')->name('property.admin.availability.loadDates');
//     Route::match(['post'],'/store','AvailabilityController@store')->name('property.admin.availability.store');
// });
Route::group(['prefix'=>'{property_id}/availability'],function(){
    Route::get('/','AvailabilityController@index')->name('property.admin.dokan.availability.index');
    Route::get('/loadDates','AvailabilityController@loadDates')->name('property.admin.dokan.availability.loadDates');
    Route::post('/store','AvailabilityController@store')->name('property.admin.dokan.availability.store');
});

Route::group(['prefix'=>'{dokan_id}/availability'],function(){
    Route::get('/','AvailabilityController@index')->name('property.admin.product.availability.index');
    Route::get('/loadDates','AvailabilityController@loadDates')->name('property.admin.product.availability.loadDates');
    Route::post('/store','AvailabilityController@store')->name('property.admin.product.availability.store');
});

Route::match(['get'],'/product-category','ProductCategoryController@index')->name('property.admin.product.category.index');
Route::match(['get'],'/product-category/editBulk','ProductCategoryController@editBulk')->name('property.admin.product.category.index');
Route::match(['get'],'/product-category/edit/{id}','ProductCategoryController@edit')->name('property.admin.product.category.edit');
Route::post('/product-category/store/{id}','ProductCategoryController@store')->name('property.admin.product.category.store');

Route::match(['get'],'/category','CategoryController@index')->name('property.admin.category.index');
Route::match(['get'],'/category/edit/{id}','CategoryController@edit')->name('property.admin.category.edit');
Route::post('/category/store/{id}','CategoryController@store')->name('property.admin.category.store');
