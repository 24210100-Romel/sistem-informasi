<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                if (! Schema::hasColumn('articles', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
            });

            return;
        }

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
