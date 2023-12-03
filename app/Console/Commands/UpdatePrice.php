<?php

namespace App\Console\Commands;

use App\Models\Price;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:price';

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
        Log::info('Price is called');
        $client = new Client();
        $headers = [];
        $request = new Request('GET', "https://call3.tgju.org/ajax.json", $headers);
        $res = $client->send($request);
        $data =  json_decode($res->getBody());
        Price::query()->updateOrCreate(
            ['name' => 'یورو' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_eur->p]);
        Price::query()->updateOrCreate(
            ['name' => 'درهم امارات' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_aed->p]);
        Price::query()->updateOrCreate(
            ['name' => 'روبل' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_rub->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پوند استرلینگ' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_gbp->p]);
        Price::query()->updateOrCreate(
            ['name' => 'دلار' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_dollar_rl->p]);
        Price::query()->updateOrCreate(
            ['name' => 'دلار استرالیا' ,'category_price_id' => 1 , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_aud->p]);

        Price::query()->updateOrCreate(
            ['name' => 'آلومینیم' ,'category_price_id' => 2 , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->aluminium->p]);
        Price::query()->updateOrCreate(
            ['name' => 'کبالت' ,'category_price_id' => 2 , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->cobalt->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پالادیوم' ,'category_price_id' => 2 , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->palladium->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پلاتینیم' ,'category_price_id' => 2 , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->platinum->p]);
        Price::query()->updateOrCreate(
            ['name' => 'روی' ,'category_price_id' => 2 , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->base_global_zinc->p]);
    }
}
