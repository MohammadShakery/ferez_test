<?php

namespace App\Console\Commands;

use App\Models\Network;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AddDefaultNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:default';

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
        $users = User::all();
        foreach ($users as $user)
        {
            Network::query()->create([
                'name' => "همکاران" ,
                'user_id' => $user->id ,
                'icon' => 'star.png'
            ]);
            Network::query()->create([
                'name' => "تامین کنندگان" ,
                'user_id' => $user->id ,
                'icon' => 'truck.png'
            ]);
            Network::query()->create([
                'name' => "خریداران" ,
                'user_id' => $user->id ,
                'icon' => 'box.png'
            ]);
        }
        Log::info('شبکه های پیش فرض به کاربران اضافه شدند');

    }
}
