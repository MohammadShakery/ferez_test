<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BrandStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:status';

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
        $brands = Brand::all();
        foreach ($brands as $brand)
        {
            $brand->update([
                'status' => true
            ]);
        }
        Log::info('تمامی برند ها غیر فعال گردید');
    }
}
