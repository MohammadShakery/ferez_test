<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::query()->create([
            "name" => 'برندها' ,
            "start_color" => "#we34rt" ,
            "end_color" => "#we34rt" ,
            "route" => "/brands"
        ]);
        Module::query()->create([
            "name" => 'اطلاعیه ها' ,
            "start_color" => "#we34rt" ,
            "end_color" => "#we34rt" ,
            "route" => "/alerts"
        ]);
        Module::query()->create([
            "name" => 'شبکه من' ,
            "start_color" => "#we34rt" ,
            "end_color" => "#we34rt" ,
            "route" => "/network"
        ]);
        Module::query()->create([
            "name" => 'آخرین قیمت ها' ,
            "start_color" => "#we34rt" ,
            "end_color" => "#we34rt" ,
            "route" => "/price"
        ]);

    }
}
