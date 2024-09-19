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
        Schema::create('purpose', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('name');
            $table->string('note')->nullable();
            $table->string('prefix');
            $table->foreignId('bank_id')->constrained('banks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purpose');
    }
};
