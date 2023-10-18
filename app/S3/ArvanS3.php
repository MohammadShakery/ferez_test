<?php

namespace App\S3;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArvanS3
{
    private  $bucket_name = "gh23d";
    private  $access_key = "bde69fd1-9530-4b1d-b16d-482bffd2e615";
    private  $secret_key = "1a743675e6d1fe4fe405dd4f566f0b5b2083aa8290d0060d9f15844de569933f";


    private function getClient()
    {
        return new S3Client([
            'region' => 'region',
            'version' => '2006-03-01',
            'endpoint' => "https://s3.ir-thr-at1.arvanstorage.ir",
            'credentials' => [
                'key' => $this->access_key,
                'secret' => $this->secret_key
            ],
            'use_path_style_endpoint' => true
        ]);
    }

    public function sendFile($file)
    {
        // use path file on server as example :
            // /storage/brands/123.jpg
        if(PHP_OS == "WINNT") {$address = str_replace("app\storage",'app\public',Storage::path($file));}
        else {$address = str_replace("app/storage",'app/public',Storage::path($file));}
        $file_name = str_replace("/storage/","",$file);
        try {
                $client = $this->getClient();
                $result = $client->putObject([
                    'Bucket' => $this->bucket_name,
                    'Key' => $file_name,
                    'SourceFile' => $address,
                    'ACL' => 'public-read'
                ]);
                return $result->get("ObjectURL");
            } catch (S3Exception $e) {
                Log::error("s3 upload error: ".$e->getMessage());
            }
    }

    public function deleteFile($name)
    {
        // use real path for delete file as example:
            // url : https://s3.ir-thr-at1.arvanstorage.ir/gh23d/test/123.png
        $address = explode($this->bucket_name.'/',$name);
        try {
            $client = $this->getClient();
            $result = $client->deleteObject([
                'Bucket' => $this->bucket_name,
                'Key' => $address[1]]);
            return $result;
        } catch (S3Exception $e) {
            Log::error("s3 upload error: ".$e->getMessage());
        }
    }
}
