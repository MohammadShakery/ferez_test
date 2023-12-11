<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BrandView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        $brands = Brand::all();
        foreach ($brands as $brand)
        {
            if(Cache::has('view_'.$brand->id))
            {
                $view = (Cache::get('view_'.$brand->id));
                if($view > 0)
                {
                    DB::table('brands')
                        ->where('id', '=', $brand->id)
                        ->update(['view' => $brand->view + $view]);
                }
                Cache::put('view_'.$brand->id,0);
            }
        }
        DB::commit();

    }
}
