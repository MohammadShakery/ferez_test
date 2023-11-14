<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostStoreRequest;
use App\Http\Requests\Admin\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'posts' => Post::query()->orderByDesc('created_at')->get()
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('file')->guessExtension();
        $file = $request->file('file')->storeAs('public/images/posts', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        Post::query()->create([
            'title' => $request->get('title') ,
            'file' => $path ,
            'file_cdn' => (new \App\S3\ArvanS3)->sendFile($path) ,
            'description' => $request->get('description') ,
            'type' => $this->getType($request->file('file')->guessExtension())
        ]);

        return response([
            'status' => true ,
            'message' => 'پست شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response([
            'status' => true ,
            'post' => $post
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $post->update($request->all());
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/posts', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($post->file) {
                Storage::delete(parse_url($post->file, PHP_URL_PATH));
            }
            if($post->file_cdn != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($post->file_cdn);
            }
            $post->file = $fileUrl;
            $post->file_cdn = (new \App\S3\ArvanS3)->sendFile($fileUrl);
            $post->type = $this->getType($extension);
            $post->save();
        }

        return response([
            'status' => true ,
            'message' => 'پست مورد نظر شما با موفقیت بروزرسانی گردید'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post_clone = $post;
            $post->delete();
            if ($post_clone->file) {
                Storage::delete(parse_url($post_clone->file, PHP_URL_PATH));
            }
            if($post_clone->file_cdn != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($post_clone->file_cdn);
            }
            return response([
                'status' => true ,
                'message' => 'پست مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف پست مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function getType($extension)
    {
        switch ($extension)
        {
            case 'png': return "image";break;
            case 'jpg': return "image";break;
            case 'jpeg': return "image";break;
            case 'mp4': return "video";break;
            case 'mkv': return "video";break;
        }
    }
}
