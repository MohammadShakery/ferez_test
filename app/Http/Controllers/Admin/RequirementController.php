<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequirementStoreRequest;
use App\Http\Requests\Admin\RequirementUpdateRequest;
use App\Models\Category;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'requirements' => Requirement::query()->orderByDesc('created_at')->get()
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
    public function store(RequirementStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/requirement', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $requirement = Requirement::query()->create($request->all());
        $requirement->image = $path;
//        $requirement->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
        $requirement->save();
        return response([
            'status' => true ,
            'requirement' => $requirement ,
            'message' => 'نیازمندی مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Requirement $requirement)
    {
        return response([
            'status' => true ,
            'requirement' => $requirement
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequirementUpdateRequest $request, Requirement $requirement)
    {
        $requirement->update($request->all());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/requirement', $name . '.' . $extension);
            $fileUrl = Storage::url($path);
            // Delete previous image if it exists
            if ($requirement->image) {
                Storage::delete(parse_url($requirement->image, PHP_URL_PATH));
            }
            if($requirement->cdn_image != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($requirement->cdn_image);
            }
            $requirement->image = $fileUrl;
//            $requirement->cdn_image = (new \App\S3\ArvanS3)->sendFile($fileUrl);
            $requirement->save();
        }

        return response([
            'status' => true,
            'requirement' => $requirement,
            'message' => 'نیازمندی مورد نظر شما با موفقیت ویرایش گردید'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requirement $requirement)
    {
        try {
            $requirement_clone = $requirement;
            $requirement->delete();
            if ($requirement_clone->image) {
                Storage::delete(parse_url($requirement_clone->image, PHP_URL_PATH));
            }
            if($requirement_clone->cdn_image != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($requirement_clone->cdn_image);
            }
            return response([
                'status' => true ,
                'message' => 'نیازمندی مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف نیازمندی مورد نظر شما وجود ندارد'
            ],200);
        }
    }
}
