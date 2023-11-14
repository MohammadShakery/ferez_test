<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\requirementCategory;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function getCategories()
    {
        return response([
            'status' => true ,
            'categories' => requirementCategory::all()
        ],200);
    }


    public function getRequirementFromCategory(requirementCategory $requirementCategory)
    {
        $requirements = Requirement::query()->where('requirement_category_id',$requirementCategory->id)
            ->orderByDesc('created_at')->get();
        return response([
            'status' => true ,
            'requirements' => $requirements
        ],200);
    }

    public function allRequirements()
    {
        return response([
            'status' => true ,
            'requirements' => Requirement::query()->orderByDesc('created_at')->get()
        ],200);
    }

    public function show(Requirement $requirement)
    {
        return response([
            'status' => true ,
            'requirement' => $requirement
        ],200);
    }
}
