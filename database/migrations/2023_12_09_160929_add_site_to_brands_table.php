<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('site',255)->nullable();
            $table->string('instagram',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('whatsapp',255)->nullable();
            $table->string('linkedin',255)->nullable();
            $table->string('telegram',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            //
        });
    }
};
