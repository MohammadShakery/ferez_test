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
        $client = new Client();
        $request = new Request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $res = $client->sendAsync($request)->wait();
        $data = json_decode($res->getBody());
        foreach ($data as $post)
        {
            \App\Models\Post::query()->create([
                'title' => $post->title ,
                'description' => $post->body ,
                'file' => '/storage/dfsfsf.png' ,
                'type' => 'image'
            ]);
        }
    });



