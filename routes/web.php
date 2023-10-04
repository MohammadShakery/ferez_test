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

Route::get('/test',function (){
//    $client = new Client();
//    $headers = [
//    ];
//    $request = new Request('GET', 'https://sanatabzar128.ir/api/test', $headers);
//    $res = $client->send($request);
//    $data =  json_decode($res->getBody());
//    $products = $data->products;
//    foreach ($products as $product)
//    {
//        if($product->is_delete == "0" and $product->id_category != null) {
//                $path = $product->img;
//                $name = explode('/', $product->img);
//                Storage::put('public/products/' . $name[4], file_get_contents($path));
//                if(\App\Models\brandCategory::query()->where('id',$product->id_category)->exists())
//                {
//                    $product_db = \App\Models\Product::query()->create([
//                        'name' => $product->title ,
//                        'description' => $product->content ,
//                        'brand_category_id' => $product->id_category ,
//                        'price' => $product->price
//                    ]);
//                    \App\Models\Image::query()->create([
//                        'product_id' => $product_db->id ,
//                        'src' => 'storage/products/' . $name[4],
//                    ]);
//                }
//
//
//
//        }
//    }

    $access_key = "bde69fd1-9530-4b1d-b16d-482bffd2e615";
    $secret_key = "1a743675e6d1fe4fe405dd4f566f0b5b2083aa8290d0060d9f15844de569933f";
    $client = new \Aws\S3\S3Client([
        'region' => 'region',
        'version' => '2006-03-01',
        'endpoint' => "https://s3.ir-thr-at1.arvanstorage.ir",
        'credentials' => [
            'key' => $access_key,
            'secret' => $secret_key
        ],
        'use_path_style_endpoint' => true
    ]);


    $brands = \App\Models\Brand::all();
    foreach ($brands as $brand)
    {
        echo "1";
        $image = str_replace("storage/app",'public',$brand->image);
        dd(Storage::exists($image));
        if(Storage::exists($brand->image)) {
            echo "2";
            try {
                $url = Storage::path($brand->image);
                $name = explode("/",$brand->image);
                $result = $client->putObject([
                    'Bucket' => 'gh23d',
                    'Key' => 'brands/'.$name[2],
                    'SourceFile' => $url,
                    'ACL' => 'public-read'
                ]);
                $brand->update([
                    'cdn_image' => $result->get("ObjectURL")
                ]);
            } catch (S3Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }



});
