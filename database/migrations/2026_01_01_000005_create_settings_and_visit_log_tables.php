<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->string('k', 60)->primary();
                $table->text('v')->nullable();
            });
        }

        if (! Schema::hasTable('visit_log')) {
            Schema::create('visit_log', function (Blueprint $table) {
                $table->date('tgl');
                $table->char('vid', 32);
                $table->unsignedInteger('views')->default(1);

                $table->primary(['tgl', 'vid']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_log');
        Schema::dropIfExists('settings');
    }
};
