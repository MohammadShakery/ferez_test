<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $user;
    protected $brand;
    public function __construct(Request $request)
    {
        $this->user = $this->getUser($request);
        $this->brand = Brand::query()->where('user_id',$this->user->id)->first();
    }
    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function index()
    {
        return response([
            'status' => true ,
            'comments' => Comment::query()->where('brand_id',$this->brand->id)->orderBy('status')->get()
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
        if($comment->brand_id != $this->brand->id)
        {
            return  response([
                'status' => false ,
                'message' => 'شما دسترسی به این کامنت ندارید'
            ],200);
        }
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
        if($comment->brand_id != $this->brand->id)
        {
            return  response([
                'status' => false ,
                'message' => 'شما دسترسی به این کامنت ندارید'
            ],200);
        }
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
        if($comment->brand_id != $this->brand->id)
        {
            return  response([
                'status' => false ,
                'message' => 'شما دسترسی به این کامنت ندارید'
            ],200);
        }
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
}
