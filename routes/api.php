<?php

Route::group([

    'middleware' => 'api',

], function () {

    // Authentication apis

    Route::post('login',                'AuthController@login');
    Route::post('register',             'AuthController@register');
    Route::post('logout',               'AuthController@logout');
    Route::post('refresh',              'AuthController@refresh');
    Route::post('me',                   'AuthController@me');
    Route::post('forgetPassword',       'ResetPasswordController@sendEmail');
    Route::post('resetPassword',        'ChangePasswordController@process');
    
    // Library management apis

    Route::post('collections',          'CollectionController@list');
    Route::post('collection',           'CollectionController@get');
    Route::post('collection_add',       'CollectionController@add');
    Route::post('collection_edit',      'CollectionController@edit');
    Route::post('collection_remove',    'CollectionController@remove');

    Route::post('books',                'BookController@list');
    Route::post('book',                 'BookController@get');
    Route::post('book_add',             'BookController@add');
    Route::post('book_edit',            'BookController@edit');
    Route::post('book_remove',          'BookController@remove');
});