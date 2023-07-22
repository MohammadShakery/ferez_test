<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ModuleStoreRequest;
use App\Http\Requests\Admin\ModuleUpdateRequest;
use App\Http\Requests\Admin\ModuleUserStoreRequest;
use App\Models\Brand;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'modules' => Module::all()
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
    public function store(ModuleStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('icon')->guessExtension();
        $file = $request->file('icon')->storeAs('public/images/modules', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $module = Module::query()->create($request->all());
        $module->icon = $path;
        $module->save();
        return response([
            'status' => true ,
            'module' => $module ,
            'message' => 'ماژول مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return response([
            'status' => true ,
            'module' => $module
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleUpdateRequest $request, Module $module)
    {
        $module->update($request->all());
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/image/modules', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($module->icon) {
                Storage::delete(parse_url($module->icon, PHP_URL_PATH));
            }

            $module->icon = $fileUrl;
            $module->save();
        }

        return response([
            'status' => true ,
            'module' => $module
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        try {
            $module_clone = $module;
            $module->delete();
            if ($module_clone->icon) {
                Storage::delete(parse_url($module_clone->icon, PHP_URL_PATH));
            }
            return response([
                'status' => true ,
                'message' => 'ماژول مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف ماژول مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function attachModuleToUser(ModuleUserStoreRequest $request)
    {
        $user = User::query()->where('id',$request->get('user'))->firstOrFail();
        $user->modules()->attach($request->get('modules'));
        return response([
            'status' => true ,
            'message' => 'دسترسی به ماژول های مورد برای این کاربر ایجاد شد'
        ],200);
    }


}
