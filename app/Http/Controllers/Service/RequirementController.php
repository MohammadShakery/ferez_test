<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequirementUpdateRequest;
use App\Http\Requests\Service\RequirementStoreRequest;
use App\Models\Requirement;
use App\Models\requirementCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RequirementController extends Controller
{
    public function getCategories()
    {
        if(Cache::has('requirement_categories'))
        {
            $data_array = (array)json_decode(Cache::get('requirement_categories'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $categories = requirementCategory::all();
        $data =array(
            'status' => true ,
            'categories' => $categories
        );
        Cache::put('requirement_categories',json_encode($data),now()->addSeconds(60));
        return response([
            'status' => true ,
            'categories' => $categories
        ],200);
    }


    public function getRequirementFromCategory(requirementCategory $requirementCategory,Request $request)
    {
        if(Cache::has('requirement_category_id_'.$requirementCategory->id.'_page_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('requirement_category_id_'.$requirementCategory->id.'_page_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $requirements = Requirement::query()->where('requirement_category_id',$requirementCategory->id)
            ->orderByDesc('created_at')->paginate();
        $data =array(
            'status' => true ,
            'requirements' => $requirements
        );
        Cache::put('requirement_category_id_'.$requirementCategory->id.'_page_'.$request->get('page'),json_encode($data),now()->addSeconds(60));

        return response([
            'status' => true ,
            'requirements' => $requirements
        ],200);
    }

    public function allRequirements(Request $request)
    {
        if(Cache::has('requirements_page_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('requirements_page_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $requirements = Requirement::query()->orderByDesc('created_at')->paginate();
        $data =array(
            'status' => true ,
            'requirements' => $requirements
        );
        Cache::put('requirements_page_'.$request->get('page'),json_encode($data),now()->addSeconds(60));

        return response([
            'status' => true ,
            'requirements' => $requirements
        ],200);
    }

    public function show(Requirement $requirement)
    {
        if(Cache::has('requirement_id_'.$requirement->id))
        {
            $data_array = (array)json_decode(Cache::get('requirement_id_'.$requirement->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $requirement_with_category = $requirement->load('category');
        $data =array(
            'status' => true ,
            'requirement' => $requirement_with_category
        );
        Cache::put('requirement_id_'.$requirement->id,json_encode($data),now()->addSeconds(60));

        return response([
            'status' => true ,
            'requirement' => $requirement_with_category
        ],200);
    }

    public function store(RequirementStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/requirement', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $requirement = Requirement::query()->create($request->all());
        $requirement->image = $path;
        $requirement->status = true;
        $requirement->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
        $requirement->save();
        return response([
            'status' => true ,
            'requirement' => $requirement ,
            'message' => 'نیازمندی مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    public function update(Requirement $requirement,RequirementUpdateRequest $request)
    {
        $user = $this->getUser($request);
        if($requirement->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این آگهی ندارید'
            ],200);
        }
        $requirement->update($request->all());
        $requirement->user_id = $user->id;
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
            $requirement->cdn_image = (new \App\S3\ArvanS3)->sendFile($fileUrl);
            $requirement->save();
        }
        $requirement->user_id = $user->id;
        $requirement->save();
    }


    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function delete(Requirement $requirement,Request $request)
    {
        $user = $this->getUser($request);
        if($requirement->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این آگهی ندارید'
            ],200);
        }
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

    public function getUserRequirements(Request $request)
    {
        $user = $this->getUser($request);
        return response([
            'status' => true ,
            'requirements'=> Requirement::query()->where('user_id',$user->id)->orderByDesc('created_at')->get()
        ],200);
    }
}
