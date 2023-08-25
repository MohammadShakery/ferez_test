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

Route::get('test',function (){
    $client = new Client();
    $headers = [
    ];
    $request = new Request('GET', 'https://sanatabzar128.ir/api/test', $headers);
    $res = $client->send($request);
    $data =  json_decode($res->getBody());
    $categories = $data->categories;
    foreach ($categories as $category)
    {
        if($category->brand->is_delete == "0" and $category->is_delete == "0") {
                $path = $category->img;
                $name = explode('/', $category->img);
                Storage::put('public/categories/' . $name[4], file_get_contents($path));
                $brand = \App\Models\Brand::query()->where('name',$category->brand->name)->first();
                $category = \App\Models\brandCategory::query()->create([
                    'id' => $category->id ,
                    'name' => $category->name,
                    'image' => 'storage/categories/' . $name[4],
                    'brand_id' => $brand->id
                ]);


        }
    }

});
