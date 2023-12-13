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

    Route::prefix('/service/v1/admin')->name('admin.')->group(function (){

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
        Route::get('/brand/{brand}/brand_categories',[\App\Http\Controllers\Admin\BrandCategoryController::class,'getBrandCategoryByBrand']);

        # Admin Product Api
        Route::resource('product',\App\Http\Controllers\Admin\ProductController::class);
        Route::post('/product/{product}/update',[\App\Http\Controllers\Admin\ProductController::class,'update']);
        Route::delete('/product/image/{image}',[\App\Http\Controllers\Admin\ProductController::class,'deleteImage']);
        Route::get('/brand_category/{brand_category}/products',[\App\Http\Controllers\Admin\ProductController::class,'getProductByBrandCategory']);

        # Admin Alert Api
        Route::resource('alert',\App\Http\Controllers\Admin\AlertController::class);
        Route::post('/alert/{alert}/update',[\App\Http\Controllers\Admin\AlertController::class,'update']);

        # Comment Api
        Route::resource('comment',\App\Http\Controllers\Admin\CommentController::class);
        Route::get('brand/{brand}/comment',[\App\Http\Controllers\Admin\CommentController::class,'getCommentFromBrand']);
        Route::get('user/{user}/comment',[\App\Http\Controllers\Admin\CommentController::class,'getCommentFromUser']);


        Route::resource('slider',\App\Http\Controllers\Admin\SliderController::class);
        Route::post('/slider/{slider}/update',[\App\Http\Controllers\Admin\SliderController::class,'update']);
        Route::get('/slider/location/{location}',[\App\Http\Controllers\Admin\SliderController::class,'getWithLocation']);

        Route::resource('category_slider',\App\Http\Controllers\Admin\CategorySliderController::class);
        Route::post('/category_slider/{category_slider}/update',[\App\Http\Controllers\Admin\CategorySliderController::class,'update']);
        Route::get('/category_slider/category/{category}',[\App\Http\Controllers\Admin\CategorySliderController::class,'getWithCategory_id']);

        Route::get('/request/industrials',[\App\Http\Controllers\Admin\IndustrialController::class,'index']);
        Route::post('/request/{request}/industrial/update',[\App\Http\Controllers\Admin\IndustrialController::class,'store']);

        Route::get('special_sale',[\App\Http\Controllers\Admin\SpecialSaleController::class,'index']);
        Route::get('special_sale/{special_sale}/show',[\App\Http\Controllers\Admin\SpecialSaleController::class,'show']);
        Route::post('special_sale',[\App\Http\Controllers\Admin\SpecialSaleController::class,'store']);
        Route::post('special_sale/{special_sale}/update',[\App\Http\Controllers\Admin\SpecialSaleController::class,'update']);
        Route::get('special_sale_image/{special_sale_image}/delete',[\App\Http\Controllers\Admin\SpecialSaleController::class,'deleteImage']);
        Route::get('special_sale/{special_sale}/delete',[\App\Http\Controllers\Admin\SpecialSaleController::class,'delete']);

        Route::resource('requirement_category',\App\Http\Controllers\Admin\RequirementCategoryController::class);
        Route::resource('requirement',\App\Http\Controllers\Admin\RequirementController::class);
        Route::post('/requirement/{requirement}/update',[\App\Http\Controllers\Admin\RequirementController::class,'update']);
        Route::get('requirement/{requirement}/approve',[\App\Http\Controllers\Admin\RequirementController::class,'approveUserRequirement']);

        Route::resource('post',\App\Http\Controllers\Admin\PostController::class);
        Route::post('/post/{post}/update',[\App\Http\Controllers\Admin\PostController::class,'update']);

        Route::get('/user',[\App\Http\Controllers\Admin\UserController::class,'index']);
        Route::get('/user/{user}',[\App\Http\Controllers\Admin\UserController::class,'show']);
        Route::post('/user/{user}',[\App\Http\Controllers\Admin\UserController::class,'update']);

        Route::get('/violations',[\App\Http\Controllers\Admin\ViolationController::class,'index']);
        Route::get('/attribute/{attribute}/delete',[\App\Http\Controllers\Admin\ProductController::class,'deleteAttribute']);

    });

    Route::prefix('/service/v1/client')->name('service.')->group(function (){

        Route::post('/login',[\App\Http\Controllers\Service\AuthController::class,'store']);
        Route::post('/verify',[\App\Http\Controllers\Service\AuthController::class,'VerifyOTP']);

    });

    Route::prefix('/service/v1/client/')->name('service.')->middleware(\App\Http\Middleware\UserAuth::class)->group(function (){
            Route::get('user/profile/',[\App\Http\Controllers\Service\UserController::class,'index']);
            Route::post('user/profile',[\App\Http\Controllers\Service\UserController::class,'store']);

            Route::get('/network',[\App\Http\Controllers\Service\NetworkController::class,'index']);
            Route::post('/network',[\App\Http\Controllers\Service\NetworkController::class,'store']);
            Route::get('/network/getBrands',[\App\Http\Controllers\Service\NetworkController::class,'getBrands']);
            Route::post('/network/{network}/update',[\App\Http\Controllers\Service\NetworkController::class,'update']);
            Route::get('/network/{network}/brand/{brand}/attach',[\App\Http\Controllers\Service\NetworkController::class,'attachBrand']);
            Route::get('/network/{network}/brand/{brand}/detach',[\App\Http\Controllers\Service\NetworkController::class,'detachBrand']);
            Route::post('/network/{network}/delete',[\App\Http\Controllers\Service\NetworkController::class,'delete']);

            Route::get('/post',[\App\Http\Controllers\Service\PostController::class,'index']);
            Route::get('/post/{post}',[\App\Http\Controllers\Service\PostController::class,'show']);
            Route::get('/post/{post}/like',[\App\Http\Controllers\Service\PostController::class,'attachLike']);
            Route::get('/post/{post}/unlike',[\App\Http\Controllers\Service\PostController::class,'detachLike']);

            Route::get('/home',[\App\Http\Controllers\Service\HomeController::class,'home']);

            Route::post('/brand/{brand}/violation',[\App\Http\Controllers\Service\ViolationController::class,'store']);
            Route::get('/brand/{brand}/comments',[\App\Http\Controllers\Service\CommentController::class,'getCommentUser']);

            Route::get('/user/requirement',[\App\Http\Controllers\Service\RequirementController::class,'getUserRequirements']);
            Route::post('/user/requirement',[\App\Http\Controllers\Service\RequirementController::class,'store']);
            Route::post('/user/requirement/{requirement}/update',[\App\Http\Controllers\Service\RequirementController::class,'update']);
            Route::get('/user/requirement/{requirement}/delete',[\App\Http\Controllers\Service\RequirementController::class,'delete']);


    });

    Route::prefix('/service/v1/client/')->name('service.')->middleware(\App\Http\Middleware\CRM::class)->group(function (){
        Route::get('/category',[\App\Http\Controllers\Service\CategoryController::class,'index']);
        Route::get('/category/{category}/show',[\App\Http\Controllers\Service\CategoryController::class,'show']);
        Route::get('/category/{category}/subcategories',[\App\Http\Controllers\Service\CategoryController::class,'getSubCategories']);
        Route::get('/brand',[\App\Http\Controllers\Service\BrandController::class,'index']);
        Route::get('/brand/{brand}/show',[\App\Http\Controllers\Service\BrandController::class,'show']);
        Route::get('/brand/category/{category}/show',[\App\Http\Controllers\Service\BrandController::class,'brandFromCategory']);
        Route::get('/brand/{brand}/brand_categories',[\App\Http\Controllers\Service\BrandCategoryController::class,'index']);

        Route::get('/brand/{slug}/search',[\App\Http\Controllers\Service\BrandController::class,'searchInBrands']);
        Route::get('/product/{slug}/search',[\App\Http\Controllers\Service\BrandController::class,'searchInProducts']);

        Route::get('/slider',[\App\Http\Controllers\Service\SliderController::class,'show']);
        Route::get('/category/{category}/slider',[\App\Http\Controllers\Service\CategorySliderController::class,'show']);

        Route::post('/comment/store',[\App\Http\Controllers\Service\CommentController::class,'store'])->middleware(\App\Http\Middleware\UserAuth::class);

        Route::post('/industrial/store',[\App\Http\Controllers\Service\IndustrialController::class,'CheckIndustrial'])->middleware(\App\Http\Middleware\UserAuth::class);

        Route::get('/requirement_categories',[\App\Http\Controllers\Service\RequirementController::class,'getCategories']);
        Route::get('/requirement_category/{requirement_category}/requirement',[\App\Http\Controllers\Service\RequirementController::class,'getRequirementFromCategory']);
        Route::get('/requirement',[\App\Http\Controllers\Service\RequirementController::class,'allRequirements']);
        Route::get('/requirement/{requirement}',[\App\Http\Controllers\Service\RequirementController::class,'show']);

        Route::get('/special_sale',[\App\Http\Controllers\Service\SpecialSaleController::class,'getSpecialSaleByPage']);
        Route::get('/special_sale/{special_sale}',[\App\Http\Controllers\Service\SpecialSaleController::class,'getSpecialSale']);
        Route::get('/category/{category}/special_sale',[\App\Http\Controllers\Service\SpecialSaleController::class,'getSpecialSaleByCategory']);

        Route::get('/alert',[\App\Http\Controllers\Service\AlertController::class,'index']);
        Route::get('/alert/{alert}',[\App\Http\Controllers\Service\AlertController::class,'show']);

        Route::get('/price',[\App\Http\Controllers\Service\PriceController::class,'price']);
        Route::get('/price/category',[\App\Http\Controllers\Service\PriceController::class,'priceCategories']);
        Route::get('/category_price/{category_price}/prices',[\App\Http\Controllers\Service\PriceController::class,'getPriceByCategory']);
    });


