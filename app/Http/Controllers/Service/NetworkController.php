<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\Network\UpdateRequest;
use App\Models\Brand;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetworkController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'networks' => Network::query()->where('user_id',($this->getUser($request))->id)->orderByDesc('created_at')->get()
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function getBrands(Request $request)
    {
        $network = Network::query()->where('user_id',($this->getUser($request))->id)
            ->where('id',$request->get('id'))->with(['brands' => function($query){
                $query->with('category');
            }])->first();
        return response([
            'status' => true ,
            'network' => $network
        ],200);
    }

    public function store(UpdateRequest $request)
    {
        $user = $this->getUser($request);
        if(Network::query()->where('user_id',$user->id)->where('name',$request->get('name'))->exists())
        {
            return response([
                'status' => false ,
                'message' => 'شما درحال حاضر یک شبکه با این نام دارید'
            ],200);
        }
        Network::query()->create([
            'name' => $request->get('name') ,
            'user_id' => $user->id ,
            'icon' => $request->get('icon')
        ]);

        return response([
            'status' => true ,
            'message' => 'شبکه مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    public function update(UpdateRequest $request,Network $network)
    {
        $user = $this->getUser($request);
        if($network->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این شبکه ندارید'
            ],403);
        }
        if(Network::query()->where('user_id',$user->id)->whereNot('id',$network->id)->where('name',$request->get('name'))->exists())
        {
            return response([
                'status' => false ,
                'message' => 'شما درحال حاضر یک شبکه با این نام دارید'
            ],200);
        }
        $network->update([
            'name' => $request->get('name') ,
            'icon' => $request->get('icon')
        ]);
        return response([
            'status' => true ,
            'message' => 'شبکه مورد نظر شما با موفقیت بروزرسانی گردید'
        ],200);
    }

    public function attachBrand(Request $request,Network $network,Brand $brand)
    {
        $user = $this->getUser($request);
        if($network->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این شبکه ندارید'
            ],403);
        }
        if($network->brands->where('id',$brand->id)->isNotEmpty())
        {
            return response([
                'status' => false ,
                'message' => 'شما این برند را قبلا در این شبکه ثبت نموده اید'
            ],200);
        }
        $network->brands()->attach($brand);
        return  response([
            'status' => true ,
            'message' => 'برند مورد نظر شما به این شبکه اضافه گردید'
        ],200);
    }

    public function detachBrand(Request $request,Network $network,Brand $brand)
    {
        $user = $this->getUser($request);
        if($network->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این شبکه ندارید'
            ],403);
        }
        if($network->brands->where('id',$brand->id)->isEmpty())
        {
            return response([
                'status' => false ,
                'message' => 'این برند در شبکه شما موجود نمی باشد'
            ],200);
        }
        $network->brands()->detach($brand);
        return  response([
            'status' => true ,
            'message' => 'برند مورد نظر شما از این شبکه حذف گردید'
        ],200);
    }

    public function delete(Request $request,Network $network,)
    {
        $user = $this->getUser($request);
        if($network->user_id != $user->id)
        {
            return response([
                'status' => false ,
                'message' => 'شما دسترسی به این شبکه ندارید'
            ],403);
        }
        try {
            DB::transaction(function () use ($network) {
                $network->brands()->detach();
                $network->delete();
            });
            return  response([
                'status' => true ,
                'message' => 'شبکه مورد نظر شما حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception){
            return  response([
                'status' => false ,
                'message' => 'امکان حذف این شبکه وجود ندارد'
            ],200);
        }

    }
}
