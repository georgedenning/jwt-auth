<?php
/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here you may register all of the authorisation routes which use the "auth" prefix
| and the namespace of "App\Http\Controllers\Auth".
|
*/

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'error' => 'Endpoint not found.'
    ], 404);
});

Route::post('register', 'RegisterController@register');
Route::post('verify', 'VerifyController@verify');
Route::post('login', 'LoginController@login');
Route::post('recover', 'RecoverController@recover');
Route::post('reset', 'ResetController@reset');

Route::middleware('jwt.auth')->get('user', function () {
    return auth('api')->user();
});

Route::middleware('jwt.auth')->group(function () {
    Route::get('logout', 'LogoutController@logout');
});
