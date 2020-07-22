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

Route::get('/', 'ProductController@index');

/* Produits */
Route::get('/boutique', 'ProductController@index')->name('products.index');
Route::get('/boutique/{slug}', 'ProductController@show')->name('products.show');
Route::get('/search', 'ProductController@search')->name('products.search');

/* Panier */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/panier', 'CartController@index')->name('cart.index');
    Route::post('/panier/ajouter', 'CartController@store')->name('cart.store');
    Route::patch('/panier/{rowId}', 'CartController@update')->name('cart.update');
    Route::delete('/panier/{rowId}', 'CartController@destroy')->name('cart.destroy');
    Route::get('/remise' , 'CartController@storeremise')->name('cart.store.remise');
    Route::delete('/remise' , 'CartController@destroyremise')->name('cart.destroy.remise');

});

/* Checkout  */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/paiement', 'CheckoutController@index')->name('checkout.index');
    Route::get('/paiementLivr', 'CheckoutController@indexLivraison')->name('checkout.indexLivraison');
    Route::post('/paiement', 'CheckoutController@store')->name('checkout.store');
    Route::post('/paiementLivr', 'CheckoutController@storeLivr')->name('checkout.storeLivr');
    Route::get('/merci', 'CheckoutController@thankYou')->name('checkout.thankYou');
});


Route::group(['prefix' => '/admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/homeView', function () {
   return view('homeView');
});