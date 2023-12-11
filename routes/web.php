<?php

use App\Models\Alert;
use App\Models\Category;
use App\Models\Price;
use App\Models\Setting;
use App\Services\SMS\SmsService;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
           $brands = \App\Models\Brand::all();
           foreach ($brands as $brand)
           {
               if(Cache::has('view_'.$brand->id))
               {
                   $view = (Cache::get('view_'.$brand->id));
                   Cache::put('view_'.$brand->id,$view+1);
               }
               else
               {
                   Cache::put('view_'.$brand->id,1);
               }
           }


        });

