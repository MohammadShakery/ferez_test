<?php

use App\Services\SMS\SmsService;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;
use Aws\S3\Exception\S3Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
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

Route::get('/service/amp',[\App\Http\Controllers\TestController::class,'test']);


    Route::get('/t',function (){
        $name = "fgf/dfs/gsdff/kgj.jpg";
        $name2 = explode("/",$name);
        dd($name2[sizeof($name2)-1]);

    });

Route::get('/test',function (){
        $images = \App\Models\Image::all();
        foreach ($images as $image)
        {
            $image->update([
                'src' => ("/".$image->src)
            ]);
        }
});
