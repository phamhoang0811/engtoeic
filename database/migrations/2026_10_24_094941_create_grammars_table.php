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
    Schema::create('grammars', function (Blueprint $table) {
        $table->id();
        $table->integer('target_score'); // Mốc điểm: 450, 650, 990
        $table->string('title');         // Tên chuyên đề ngữ pháp (Ví dụ: Thì hiện tại đơn)
        $table->text('description')->nullable(); // Mô tả ngắn
        $table->longText('content');     // Nội dung chi tiết bài học
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammars');
    }
};
