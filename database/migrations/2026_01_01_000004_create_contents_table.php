<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contents')) {
            Schema::table('contents', function (Blueprint $table) {
                if (! Schema::hasColumn('contents', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });

            return;
        }

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['dokumen', 'halaman', 'pengumuman', 'slider', 'sumber']);
            $table->string('judul', 200);
            $table->string('slug', 220)->nullable();
            $table->mediumText('isi')->nullable();
            $table->string('tautan')->nullable();
            $table->string('berkas')->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();

            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
