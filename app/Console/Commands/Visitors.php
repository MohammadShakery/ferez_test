<?php

namespace App\Console\Commands;

use App\Models\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Visitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:visitors';

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
        $urls = Redis::command('keys',['*url*']);
        foreach ($urls as $url)
        {
            $mix_url = explode(".",$url);
            $visits = Cache::get('url.'.$mix_url[1]);
            if(Visit::query()->where('url',$mix_url[1])->where('date',date("Y-m-d"))->exists())
            {
                $visit = Visit::query()->where('url',$mix_url[1])->where('date',date("Y-m-d"))->first();
                $visit->update([
                    'visit' => $visit->visit += $visits
                ]);
                Cache::put('url.'.$mix_url[1],0);
            }
            else
            {
                Visit::query()->create([
                    'url' => $mix_url[1] ,
                    'date' => date("Y-m-d") ,
                    'visit' => $visits
                ]);
                Cache::put('url.'.$mix_url[1],0);
            }
        }
    }
}
