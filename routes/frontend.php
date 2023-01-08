



<?php


// frontend section

//authentication
Route::get('user/auth',[\App\Http\Controllers\frontend\IndexController::class,'userAuth'])->name('user.auth');

Route::post('user/login',[\App\Http\Controllers\frontend\IndexController::class,'loginSubmit'])->name('login.submit');
Route::post('user/register',[\App\Http\Controllers\frontend\IndexController::class,'registerSubmit'])->name('register.submit');
Route::get('user/logout',[\App\Http\Controllers\frontend\IndexController::class,'userLogout'])->name('user.logout');


Route::get('/',[\App\Http\Controllers\frontend\IndexController::class,'home'])->name('home');


// about us
Route::get('/about-us',[\App\Http\Controllers\frontend\IndexController::class,'aboutUs'])->name('about_us');
// contact us
Route::get('/contact-us',[\App\Http\Controllers\frontend\IndexController::class,'contactUs'])->name('contact_us');
Route::post('/contact-submit',[\App\Http\Controllers\frontend\IndexController::class,'contactSubmit'])->name('contact.submit');

//product category
Route::get('product-category/{slug}/',[\App\Http\Controllers\frontend\IndexController::class,'productCategory'])->name('product.category');
// Route::post('category-filter',[\App\Http\Controllers\frontend\IndexController::class,'productCategoryFilter'])->name('productCategory.filter');

//end product category
//product detail 
Route::get('product-detail/{slug}/',[\App\Http\Controllers\frontend\IndexController::class,'productDetail'])->name('product.detail');
//end product detail
//product review 
Route::post('product-review/{slug}',[\App\Http\Controllers\ProductReviewController::class,'productReview'])->name('product.review');

//cart section 
Route::get('cart',[\App\Http\Controllers\frontend\CartController::class,'cart'])->name('cart');
Route::post('cart/store',[\App\Http\Controllers\frontend\CartController::class,'cartStore'])->name('cart.store');
Route::post('cart/delete',[\App\Http\Controllers\frontend\CartController::class,'cartDelete'])->name('cart.delete');
Route::post('cart/update',[\App\Http\Controllers\frontend\CartController::class,'cartUpdate'])->name('cart.update');

//coupon section
Route::post('coupon/add',[\App\Http\Controllers\frontend\CartController::class,'couponAdd'])->name('coupon.add');

//  WISHLIST SECTION
Route::get('wishlist',[\App\Http\Controllers\frontend\WishlistController::class,'wishlist'])->name('wishlist');
Route::post('wishlist/store',[\App\Http\Controllers\frontend\WishlistController::class,'wishlistStore'])->name('wishlist.store');
Route::post('wishlist/cart',[\App\Http\Controllers\frontend\WishlistController::class,'moveToCart'])->name('move.cart');
Route::post('wishlist/delete',[\App\Http\Controllers\frontend\WishlistController::class,'wishlistDelete'])->name('wishlist.delete');

//  COMPARE SECTION
Route::get('compare',[\App\Http\Controllers\frontend\CompareControler::class,'compare'])->name('compare');
Route::post('compare/store',[\App\Http\Controllers\frontend\CompareControler::class,'compareStore'])->name('compare.store');
Route::post('compare/move-to-cart',[\App\Http\Controllers\frontend\CompareControler::class,'moveToCart'])->name('compare.move.cart');
Route::post('compare/move-to-wishlist',[\App\Http\Controllers\frontend\CompareControler::class,'moveToWishlist'])->name('compare.move.wishlist');

Route::post('compare/delete',[\App\Http\Controllers\frontend\CompareControler::class,'compareDelete'])->name('compare.delete');
///// checkout section 
Route::get('checkout1',[\App\Http\Controllers\frontend\CheckoutController::class,'checkout1'])->name('checkout1');
Route::post('checkout-first',[\App\Http\Controllers\frontend\CheckoutController::class,'checkout1Store'])->name('checkout1.store');
Route::post('checkout-two',[\App\Http\Controllers\frontend\CheckoutController::class,'checkout2Store'])->name('checkout2.store');
Route::post('checkout-three',[\App\Http\Controllers\frontend\CheckoutController::class,'checkout3Store'])->name('checkout3.store');
Route::get('checkout-store',[\App\Http\Controllers\frontend\CheckoutController::class,'checkoutStore'])->name('checkout.store');
Route::get('complete/{order}',[\App\Http\Controllers\frontend\CheckoutController::class,'complete'])->name('complete');


////section shop
Route::get('shop',[\App\Http\Controllers\frontend\IndexController::class,'shop'])->name('shop');
Route::post('shop-filter',[\App\Http\Controllers\frontend\IndexController::class,'shopFilter'])->name('shop.filter');
// search product and autosearch product
Route::get('autosearch',[\App\Http\Controllers\frontend\IndexController::class,'autoSearch'])->name('autosearch');
Route::get('search',[\App\Http\Controllers\frontend\IndexController::class,'search'])->name('search');

//end frontend section
Auth::routes();




//user dashbord
Route::group(['prefix'=>'seller','middleware'=>['auth','seller']],function(){
    Route::get('/',[\App\Http\Controllers\AdminController::class,'admin'])->name('seller');
});


Route::group(['prefix'=>'user'],function(){
    Route::get('/account',[\App\Http\Controllers\frontend\IndexController::class,'userAccount'])->name('user.account');
    Route::get('/address',[\App\Http\Controllers\frontend\IndexController::class,'userAddress'])->name('user.address');
    Route::get('/order',[\App\Http\Controllers\frontend\IndexController::class,'userOrder'])->name('user.order');
    Route::get('/dashboard',[\App\Http\Controllers\frontend\IndexController::class,'userDashboard'])->name('user.dashboard');



    Route::get('/billing/address/{id}',[\App\Http\Controllers\frontend\IndexController::class,'billingAddress'])->name('billing.address');
    Route::get('/shipping/address/{id}',[\App\Http\Controllers\frontend\IndexController::class,'shippingAddress'])->name('shipping.address');

    Route::post('/update/account/{id}',[\App\Http\Controllers\frontend\IndexController::class,'updateAccount'])->name('update.account');

});