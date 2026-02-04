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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            // Прив'язуємо домен до користувача
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('url'); // Адреса сайту
            $table->integer('check_interval')->default(60);
            $table->integer('timeout')->default(5);
            $table->string('method')->default('GET');

            // Коли була остання перевірка (щоб знати, коли робити наступну)
            $table->timestamp('last_checked_at')->nullable();

            $table->timestamps();
        });
    }
};
