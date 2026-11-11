<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                if (! Schema::hasColumn('menus', 'ikon')) {
                    $table->string('ikon', 40)->nullable();
                }
            });

            return;
        }

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['utama', 'footer', 'layanan'])->default('utama');
            $table->string('ikon', 40)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->string('title', 120);
            $table->string('url')->default('#');
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
