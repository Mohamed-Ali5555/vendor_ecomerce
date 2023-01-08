<?php

/// ADMIN LOGIN 
Route::group(['prefix'=>'admin'],function(){
  Route::get('/login',[\App\Http\Controllers\Auth\Admin\LoginController::class,'showLoginForm'])->name('admin.login.form');
  Route::post('/login',[\App\Http\Controllers\Auth\Admin\LoginController::class,'login'])->name('admin.login');

});

//admin dashbord
Route::group(['prefix'=>'admin','middleware'=>['admin']],function(){
    Route::get('/',[\App\Http\Controllers\AdminController::class,'admin'])->name('admin');

    //banner section
    Route::resource('banner',\App\Http\Controllers\BannerController::class);
    // banner status 
    Route::post('banner_status',[\App\Http\Controllers\BannerController::class,'bannerStatus'])->name('banner.status');

    // About Us
    Route::get('aboutus',[\App\Http\Controllers\AboutusController::class,'index'])->name('aboutus');
    Route::put('aboutus-update',[\App\Http\Controllers\AboutusController::class,'aboutUpdate'])->name('aboutus.update');


    //category section
    Route::resource('category',\App\Http\Controllers\CategoryController::class);
    // category status 
    Route::post('category_status',[\App\Http\Controllers\CategoryController::class,'categoryStatus'])->name('category.status');
    Route::post('category/{id}/child',[\App\Http\Controllers\CategoryController::class,'getChildByParentID']);
    //Brand section
    Route::resource('brand',\App\Http\Controllers\BrandController::class);
    // Brand status 
    Route::post('brand_status',[\App\Http\Controllers\BrandController::class,'brandStatus'])->name('brand.status');

    //product section
    Route::resource('product',\App\Http\Controllers\ProductController::class);
    // Brand status 
    Route::post('product.status',[\App\Http\Controllers\ProductController::class,'productStatus'])->name('product.status');

    //user section
    Route::resource('user',\App\Http\Controllers\UserController::class);
    // Brand status 
    Route::post('user.status',[\App\Http\Controllers\UserController::class,'userStatus'])->name('user.status');
    // product-attribute 
    Route::post('product-attribute/{id}',[\App\Http\Controllers\ProductController::class,'addProductAttribute'])->name('product.attribute');
    Route::post('productAttribute/destroy',[\App\Http\Controllers\frontend\ProductController::class,'attributeDelete'])->name('productAttribute.destroy');


    //coupon section
    Route::resource('coupon',\App\Http\Controllers\CouponController::class);
    // coupon status 
    Route::post('coupon.status',[\App\Http\Controllers\CouponController::class,'couponStatus'])->name('coupon.status');

      //shipping section
      Route::resource('shipping',\App\Http\Controllers\ShippingController::class);
      // shipping status 
      Route::post('shipping.status',[\App\Http\Controllers\ShippingController::class,'shippingStatus'])->name('shipping.status');


      
      //currency section
      Route::resource('currency',\App\Http\Controllers\CurrencieController::class);
      // shipping status 
      Route::post('currency.status',[\App\Http\Controllers\CurrencieController::class,'currencyStatus'])->name('currency.status');

      //order section
      Route::resource('order',\App\Http\Controllers\OrderController::class);
      Route::post('order-status',[\App\Http\Controllers\OrderController::class,'orderStatus'])->name('order.status');


      //order section
      Route::resource('seller',\App\Http\Controllers\SellerController::class);
      Route::post('seller-status',[\App\Http\Controllers\SellerController::class,'sellerStatus'])->name('seller.status');
      Route::post('seller-verified',[\App\Http\Controllers\SellerController::class,'sellerverified'])->name('seller.verified');

      //setting section
      Route::get('settings',[\App\Http\Controllers\SettingController::class,'settings'])->name('settings');
      Route::put('settings',[\App\Http\Controllers\SettingController::class,'settingUpdate'])->name('setting.update');
     
      //SMTP SETTINGS SECTION
      Route::get('smtp',[\App\Http\Controllers\SettingController::class,'smtp'])->name('smtp');
      Route::post('smtp-update',[\App\Http\Controllers\SettingController::class,'smtpUpdate'])->name('smtp.update');
    });


//this here route of file manager route groupe
Route::group(['prefix' => 'filemanager', 'middleware' => ['web']], function () {
  \UniSharp\LaravelFilemanager\Lfm::routes();
});