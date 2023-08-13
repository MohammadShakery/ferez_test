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
    $string = "https://panel.sanatabzar128.ir/uploads/1660983551.png";
    $client = new Client();
    $headers = [
    ];
    $request = new Request('GET', 'https://sanatabzar128.ir/mbm', $headers);
    $res = $client->send($request);
    $data =  json_decode($res->getBody());
    $brands = $data->brands;
    foreach ($brands as $brand)
    {
        if($brand->is_delete == "0")
        {
            try {

                $path = str_replace('panel.','',$brand->img);
//                $client2 = new Client();
//                $request2 = new Request('GET', $path, $headers);
//                $res2 = $client2->send($request2);
                Storage::put('public/brands/'.$path,file_get_contents($path));
                dd(1);

                \App\Models\Brand::query()->create([
                    'name' => $brand->name ,
                    'description' => $brand->body,
                    'tell' => $brand->phone ,
                    'image' => 'public/brands/'.$path ,
                    'address' => $brand->insta
                ]);
                dd(1);
            }catch (Exception $e)
            {

            }


        }
    }

});
