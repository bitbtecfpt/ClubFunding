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
        Schema::create('requiredfees', function (Blueprint $table) {
            $table->id();  // Tạo cột id (tự tăng)
            $table->decimal('amount', 15, 2);  // Tạo cột amount (số thập phân)
            $table->string('purpose_code');  // Tạo cột purpose_code (chuỗi)
            $table->timestamps();  // Tạo cột created_at và updated_at

            // Thiết lập khóa ngoại cho cột purpose_code, tham chiếu đến cột 'code' trong bảng 'purposes'
            $table->foreign('purpose_code')->references('code')->on('purposes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requiredfees');
    }
};
