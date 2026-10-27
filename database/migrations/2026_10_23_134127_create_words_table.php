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
    Schema::create('words', function (Blueprint $table) {
        $table->id();
        $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
        $table->string('word');
        $table->string('meaning');
        $table->string('phonetic')->nullable();
        $table->string('part_of_speech')->nullable();
        
        // Thêm 3 trường mới vào đây
        $table->string('image')->nullable();
        $table->text('example')->nullable();
        $table->string('family')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
