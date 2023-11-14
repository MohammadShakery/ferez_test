<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequirementCategoryStoreRequest;
use App\Models\requirementCategory;
use Illuminate\Http\Request;

class RequirementCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'categories' => requirementCategory::all()
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
    public function store(RequirementCategoryStoreRequest $request)
    {
        requirementCategory::query()->create([
            'title' => $request->get('title')
        ]);

        return response([
            'status' => true ,
            'message' => 'دسته بندی شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(requirementCategory $requirementCategory)
    {
        return response([
            'status' => true ,
            'category' => $requirementCategory
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
    public function update(RequirementCategoryStoreRequest $request, requirementCategory $requirementCategory)
    {
        $requirementCategory->update($request->all());
        return response([
            'status' => true ,
            'message' => 'دست هبندی مورد نظر شما با موفقیت بروزرسانی گردید'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(requirementCategory $requirementCategory)
    {
        try {
            $requirementCategory->delete();
            return response([
                'status' => true ,
                'message' => 'دسته بندی مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف این دسته بندی وجود ندارد'
            ],200);
        }
    }
}
