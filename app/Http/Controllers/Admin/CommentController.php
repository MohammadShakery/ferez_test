<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'comments' => Comment::query()->orderBy('status')->get()
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
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return response([
            'status' => true ,
            'comment' => $comment
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        if($request->has('approve'))
        {
            if($request->get('approve') == 1)
            {
                $comment->update([
                    'status' => true
                ]);
            }
        }
        return response([
            'status' => true ,
            'comment' => $comment
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response([
                'status' => true ,
                'message' => 'کامنت مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف کامنت مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function getCommentFromUser(User $user)
    {
        return response([
            'status' => true ,
            'comments' => Comment::query()->where('user_id',$user->id)->orderBy('status')->get()
        ],200);
    }

    public function getCommentFromBrand(Brand $brand)
    {
        return response([
            'status' => true ,
            'comments' => Comment::query()->where('brand_id',$brand->id)->orderBy('status')->get()
        ],200);
    }

}
