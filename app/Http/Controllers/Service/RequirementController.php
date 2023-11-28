<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\requirementCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
}
