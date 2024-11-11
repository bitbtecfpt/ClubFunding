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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();  // Tạo cột id (tự tăng)
            $table->string('bank_account');  // Tạo cột bank_account
            $table->string('bank_name');  // Tạo cột bank_name 
            $table->string('bank_fullname');  // Tạo cột bank_fullname
            $table->string('bank_username');  // Tạo cột bank_username 
            $table->timestamps();  // Tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Banks');
    }
};
