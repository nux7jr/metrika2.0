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
        Schema::create('metrika_goals_call_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_full')->nullable();
            $table->float('calls_count')->default(0.0);
            $table->string('counter')->nullable();
            $table->string('goal')->nullable();
            $table->date('date')->default(now()->format('Y-m-d'));
            $table->timestamps();
            $table->index(['date','site_full']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrika_goals_call_sites');
    }
};
