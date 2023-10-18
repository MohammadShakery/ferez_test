<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::prefix('/service/v1/admin')->name('service.')->group(function (){

        # Admin Category Api
        Route::get('/category',[\App\Http\Controllers\Admin\CategoryController::class,'index']);
        Route::post('/category/store',[\App\Http\Controllers\Admin\CategoryController::class,'store']);
        Route::get('/category/{category}/edit',[\App\Http\Controllers\Admin\CategoryController::class,'edit']);
        Route::post('/category/{category}/update',[\App\Http\Controllers\Admin\CategoryController::class,'update']);
        Route::delete('/category/{category}/delete',[\App\Http\Controllers\Admin\CategoryController::class,'destroy']);
        Route::get('/category/{category}/subcategories',[\App\Http\Controllers\Admin\CategoryController::class,'getSubCategories']);


        # Admin Brand Api
        Route::resource('brand',\App\Http\Controllers\Admin\BrandController::class);
        Route::get('/category/{category}/brand',[\App\Http\Controllers\Admin\BrandController::class,'getBrandFromCategory']);
        Route::post('/brand/{brand}/update',[\App\Http\Controllers\Admin\BrandController::class,'update']);

        # Admin Brand Category Api
        Route::resource('brand_category',\App\Http\Controllers\Admin\BrandCategoryController::class);

        # Admin Product Api
        Route::resource('product',\App\Http\Controllers\Admin\ProductController::class);
        Route::post('/product/{product}/update',[\App\Http\Controllers\Admin\ProductController::class,'update']);
        Route::delete('/product/image/{image}',[\App\Http\Controllers\Admin\ProductController::class,'deleteImage']);

        # Admin Alert Api
        Route::resource('alert',\App\Http\Controllers\Admin\AlertController::class);
        Route::post('/alert/{alert}/update',[\App\Http\Controllers\Admin\AlertController::class,'update']);

        # Comment Api
        Route::resource('comment',\App\Http\Controllers\Admin\CommentController::class);
        Route::get('brand/{brand}/comment',[\App\Http\Controllers\Admin\CommentController::class,'getCommentFromBrand']);
        Route::get('user/{user}/comment',[\App\Http\Controllers\Admin\CommentController::class,'getCommentFromUser']);

        Route::post('/setting/default_module',[\App\Http\Controllers\Admin\SettingController::class,'DefaultModule']);

        Route::resource('slider',\App\Http\Controllers\Admin\SliderController::class);
        Route::post('/slider/{slider}/update',[\App\Http\Controllers\Admin\SliderController::class,'update']);
        Route::get('/slider/location/{location}',[\App\Http\Controllers\Admin\SliderController::class,'getWithLocation']);

        Route::resource('category_slider',\App\Http\Controllers\Admin\CategorySliderController::class);
        Route::post('/category_slider/{category_slider}/update',[\App\Http\Controllers\Admin\CategorySliderController::class,'update']);
        Route::get('/category_slider/category/{category}',[\App\Http\Controllers\Admin\CategorySliderController::class,'getWithCategory_id']);

        Route::get('/request/industrials',[\App\Http\Controllers\Admin\IndustrialController::class,'index']);
        Route::post('/request/{request}/industrial/update',[\App\Http\Controllers\Admin\IndustrialController::class,'store']);

    });

    Route::prefix('/service/v1/client')->name('service.')->group(function (){

        Route::post('/login',[\App\Http\Controllers\Service\AuthController::class,'store']);
        Route::post('/verify',[\App\Http\Controllers\Service\AuthController::class,'VerifyOTP']);

    });

    Route::prefix('/service/v1/client/')->name('service.')->middleware(\App\Http\Middleware\UserAuth::class)->group(function (){
            Route::get('user/profile/',[\App\Http\Controllers\Service\UserController::class,'index']);
            Route::post('user/profile',[\App\Http\Controllers\Service\UserController::class,'store']);
    });

    Route::prefix('/service/v1/client/')->name('service.')->group(function (){
        Route::get('/category',[\App\Http\Controllers\Service\CategoryController::class,'index']);
        Route::get('/category/{category}/show',[\App\Http\Controllers\Service\CategoryController::class,'show']);
        Route::get('/category/{category}/subcategories',[\App\Http\Controllers\Service\CategoryController::class,'getSubCategories']);
        Route::get('/brand',[\App\Http\Controllers\Service\BrandController::class,'index']);
        Route::get('/brand/{brand}/show',[\App\Http\Controllers\Service\BrandController::class,'show']);
        Route::get('/brand/category/{category}/show',[\App\Http\Controllers\Service\BrandController::class,'brandFromCategory']);
        Route::get('/brand/{brand}/brand_categories',[\App\Http\Controllers\Service\BrandCategoryController::class,'index']);

        Route::get('/slider',[\App\Http\Controllers\Service\SliderController::class,'show']);
        Route::get('/category/{category}/slider',[\App\Http\Controllers\Service\CategorySliderController::class,'show']);

        Route::post('/comment/store',[\App\Http\Controllers\Service\CommentController::class,'store']);

        Route::post('/industrial/store',[\App\Http\Controllers\Service\IndustrialController::class,'CheckIndustrial']);
    });


    Route::get('/test',function (){
        dd(\Illuminate\Support\Facades\Cache::get('test'));
        if(\Illuminate\Support\Facades\Cache::has('test'))
        {
            return response([
                'products' => \Illuminate\Support\Facades\Cache::get('test')
            ],200);
        }
        $products = \App\Models\Product::all();
        \Illuminate\Support\Facades\Cache::put('test',$products,60);
        return response([
            'products' => $products
        ],200);
    });

