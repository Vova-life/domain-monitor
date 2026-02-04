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
        Schema::create('check_logs', function (Blueprint $table) {
            $table->id();
            // Прив'язуємо лог до конкретного домену
            $table->foreignId('domain_id')->constrained('domains')->cascadeOnDelete();

            $table->integer('status_code')->nullable();
            $table->float('response_time')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }
};
