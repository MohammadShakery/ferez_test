<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlertStoreRequest;
use App\Http\Requests\Admin\AlertUpdateRequest;
use App\Models\Alert;
use App\Models\Brand;
use App\S3\ArvanS3;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlertController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'alerts' => Alert::query()->orderByDesc('created_at')->get()
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
    public function store(AlertStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/alerts', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $alert = Alert::query()->create($request->all());
        $alert->image = $path;
        $alert->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
        $alert->save();
        return response([
            'status' => true ,
            'alert' => $alert
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alert $alert)
    {
        return response([
            'status' => true ,
            'alert' => $alert
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlertUpdateRequest $request, Alert $alert)
    {

        $alert->update($request->all());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/alerts', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($alert->image) {
                Storage::delete(parse_url($alert->image, PHP_URL_PATH));
            }
            (new \App\S3\ArvanS3)->deleteFile($alert->cdn_image);
            $alert->image = $fileUrl;
            $alert->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
            $alert->save();
        }
        return response([
            'status' => true ,
            'alert' => $alert
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        try {
                $alert_clone = $alert;
                $alert->delete();
                if ($alert_clone->image) {
                    Storage::delete(parse_url($alert_clone->image, PHP_URL_PATH));
                }
                if($alert_clone->cdn_image != null)
                {
                    (new \App\S3\ArvanS3)->deleteFile($alert_clone->cdn_image);
                }
            return response([
                'status' => true ,
                'message' => 'اعلان مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف اعلان مورد نظر شما وجود ندارد'
            ],200);
        }
    }
}
