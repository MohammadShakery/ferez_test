<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\CommentStoreRequest;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request)
    {
        $user = $this->getUser($request);
        $comment = Comment::query()->create([
            'user_id' => $user->id ,
            'brand_id' => $request->get('brand_id') ,
            'rate' => $request->get('rate') ,
            'text' => $request->get('text')
        ]);
        return response([
            'status' => true ,
            'message' => 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد' ,
            'comment' => $comment
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function getCommentUser(Brand $brand, Request $request)
    {
        $user = $this->getUser($request);
        return response([
            'status' => true ,
            'comments' => Comment::query()->where('brand_id',$brand->id)
                ->where('user_id',$user->id)
                ->where('status',false)
                ->orderByDesc('created_at')->get()
        ],200);
    }

}
