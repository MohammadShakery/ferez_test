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


    $slides = \App\Models\Slider::all();
    foreach ($slides as $slide)
    {
            $image = str_replace("app/storage",'app/public',Storage::path($slide->image));
            try {
                $name = explode("/",$slide->image);
                $result = $client->putObject([
                    'Bucket' => 'gh23d',
                    'Key' => 'slider/'.$name[sizeof($name)-1],
                    'SourceFile' => $image,
                    'ACL' => 'public-read'
                ]);
                $slide->update([
                    'cdn_image' => $result->get("ObjectURL")
                ]);
                echo "2";
            } catch (S3Exception $e) {
                echo $e->getMessage() . "\n";
            }
    }



});
