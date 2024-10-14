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
        Schema::create('Purposes', function (Blueprint $table) {
            $table->id();  // Tạo cột id (tự tăng)
            $table->string('name');  // Tạo cột name
            $table->string('code')->unique();  // Tạo cột code
            $table->text('note')->nullable();  // Tạo cột note
            $table->string('prefix')->nullable();  // Tạo cột prefix
            $table->unsignedBigInteger('bank_id');  // Tạo cột bank_id (khóa ngoại)
            $table->timestamps();  // Tạo cột created_at và updated_at

            // Thiết lập khóa ngoại cho cột bank_id
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Purposes');
    }
};
