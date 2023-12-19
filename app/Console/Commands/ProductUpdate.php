<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ProductUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update';

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
        $products = Product::all();

        foreach ($products as $product)
        {
            $product->update([
                'price' => 0 ,
                'description' => html_entity_decode(strip_tags($product->description))
            ]);
            $product->update([
                'description' => str_replace(' ','',$product->description)
            ]);
        }
    }
}
