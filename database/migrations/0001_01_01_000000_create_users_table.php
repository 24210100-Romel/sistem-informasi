<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            // Database lama (sistem PHP native): lengkapi kolom yang dibutuhkan Laravel.
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'remember_token')) {
                    $table->rememberToken();
                }
                if (! Schema::hasColumn('users', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100);
                $table->string('username', 50)->unique();
                $table->string('password');
                $table->enum('role', ['admin', 'operator'])->default('operator');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
