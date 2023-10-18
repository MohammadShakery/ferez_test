<?php

use App\Models\Category;
use App\Services\SMS\SmsService;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;
use Aws\S3\Exception\S3Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/test',function (){
        if(Cache::has('categories'))
        {
            $data_array = (array)json_decode(Cache::get('categories'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status'         => true ,
            'categories' => \App\Models\Category::all());
        Cache::put('categories',json_encode($data),now()->addSeconds(300));
       return response([
           'status' => true ,
           'categories' => $data
       ],200);
    });



