<?php

use App\Services\SMS\SmsService;
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

//Route::get('test',function (){
//    $string = "https://panel.sanatabzar128.ir/uploads/1660983551.png";
//    $client = new Client();
//    $headers = [
//    ];
//    $request = new Request('GET', 'https://sanatabzar128.ir/mbm', $headers);
//    $res = $client->send($request);
//    $data =  json_decode($res->getBody());
//    $brands = $data->brands;
//    foreach ($brands as $brand)
//    {
//        if($brand->is_delete == "0")
//        {
//                $path = $brand->img;
//                $name = explode('/',$brand->img);
//                Storage::put('public/brands/'.$name[4],file_get_contents($path));
//                $brand2 = \App\Models\Brand::query()->create([
//                    'name' => $brand->name ,
//                    'description' => $brand->body,
//                    'tell' => $brand->phone ,
//                    'image' => 'storage/brands/'.$name[4] ,
//                    'address' => $brand->insta
//                ]);
//
//
//
//
//        }
//    }
//
//});
