<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        if(Cache::has('alert_page_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('alert_page_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'alerts' => Alert::query()->orderByDesc('created_at')->paginate());
        Cache::put('alert_page_'.$request->get('page'),json_encode($data),now()->addSeconds(100));
        return response($data,200);
    }

    public function show(Alert $alert)
    {
        if(Cache::has('alert_id_'.$alert->id))
        {
            $data_array = (array)json_decode(Cache::get('alert_id_'.$alert->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'alert' => $alert);
        Cache::put('alert_id_'.$alert->id,json_encode($data),now()->addSeconds(500));
        return response($data,200);
    }
}
