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
        $schema = Schema::connection('pgsql');
        $schema->create('deals', function (Blueprint $table) {
            $table->bigInteger('id')->unique()->primary();
            $table->boolean('is_adv')->default(false);
            $table->string('utm_source')->nullable();
            $table->text('utm_medium')->nullable();
            $table->text('utm_campaign')->nullable();
            $table->text('utm_content')->nullable();
            $table->text('utm_term')->nullable();
            $table->text('url')->default('без url');
            $table->string('stage_now')->default('new');
            $table->json('stage_changes')->default(json_encode([]));
            $table->float('income')->nullable();
            $table->string('currency')->default('RUB');
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->index(['is_adv', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schema = Schema::connection('pgsql');
        $schema->dropIfExists('deals');
    }
};
