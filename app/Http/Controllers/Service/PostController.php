<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if(Cache::has('posts_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('posts_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $posts = Post::query()->orderByDesc('created_at')->paginate(10);
        $posts = $this->checkLikes($posts,$request);
        $data =array(
            'status' => true ,
            'posts' => $posts);
        Cache::put('posts_'.$request->get('page'),json_encode($data),now()->addSeconds(5));
        return response([
            'status' => true ,
            'posts' => $posts
        ],200);
    }

    public function show(Post $post,Request $request)
    {
        if(Cache::has('post_id_'.$post->id))
        {
            $data_array = (array)json_decode(Cache::get('post_id_'.$post->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $post->is_liked = $post->is_liked($request);
        $data =array(
            'status' => true ,
            'post' => $post);
        Cache::put('post_id_'.$post->id,json_encode($data),now()->addSeconds(5));
        return response([
            'status' => true ,
            'posts' => $post
        ],200);
    }

    public function attachLike(Post $post,Request $request)
    {
        if($request->hasHeader("user_id") and
            User::query()->where('id',$request->header("user_id"))->exists() and
            !$post->likes()->where('user_id',$request->header("user_id"))->exists()
        ) {
                $post->likes()->attach($request->header('user_id'));
                return response([
                'status' => true
                ], 200);
        }
        return response([
            'status' => false
        ], 200);
    }

    public function detachLike(Post $post,Request $request)
    {
        if($request->hasHeader("user_id") and
            User::query()->where('id',$request->header("user_id"))->exists() and
            $post->likes()->where('user_id',$request->header("user_id"))->exists()
        ) {
            $post->likes()->detach($request->header('user_id'));
            return response([
                'status' => true
            ], 200);
        }
        return response([
            'status' => false
        ], 200);
    }


    public function checkLikes($posts,$request)
    {
        foreach ($posts as $post)
        {
            $post->is_liked = $post->is_liked($request);
        }
        return $posts;
    }
}
