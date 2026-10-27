<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grammar;

class GrammarSeeder extends Seeder
{
    public function run(): void
    {
        Grammar::create([
            'target_score' => 450,
            'title' => 'Chuyên đề 1: Thì hiện tại đơn (Present Simple)',
            'description' => 'Cách dùng, dấu hiệu nhận biết và cấu trúc câu khẳng định, phủ định, nghi vấn.',
            'content' => 'Nội dung chi tiết về thì hiện tại đơn...'
        ]);

        Grammar::create([
            'target_score' => 450,
            'title' => 'Chuyên đề 2: Thể bị động cơ bản (Passive Voice)',
            'description' => 'Công thức chuyển đổi từ câu chủ động sang bị động trong các thì cơ bản.',
            'content' => 'Nội dung chi tiết về thể bị động...'
        ]);
    }
}