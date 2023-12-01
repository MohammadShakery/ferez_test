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
            ['name' => 'یورو' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_eur->p]);
        Price::query()->updateOrCreate(
            ['name' => 'درهم امارات' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_aed->p]);
        Price::query()->updateOrCreate(
            ['name' => 'روبل' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_rub->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پوند استرلینگ' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_gbp->p]);
        Price::query()->updateOrCreate(
            ['name' => 'دلار' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_dollar_rl->p]);
        Price::query()->updateOrCreate(
            ['name' => 'دلار استرالیا' ,'category' => 'ارزها' , 'unit' => 'عدد','type' => 'ریال'],
            ['price' => $data->current->price_aud->p]);

        Price::query()->updateOrCreate(
            ['name' => 'آلومینیم' ,'category' => 'فلزات' , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->aluminium->p]);
        Price::query()->updateOrCreate(
            ['name' => 'کبالت' ,'category' => 'فلزات' , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->cobalt->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پالادیوم' ,'category' => 'فلزات' , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->palladium->p]);
        Price::query()->updateOrCreate(
            ['name' => 'پلاتینیم' ,'category' => 'فلزات' , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->platinum->p]);
        Price::query()->updateOrCreate(
            ['name' => 'روی' ,'category' => 'فلزات' , 'unit' => 'تن','type' => 'دلار'],
            ['price' => $data->current->base_global_zinc->p]);
    }
}
