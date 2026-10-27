<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\GrammarSeeder; // Thêm dòng import này vào

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản admin mặc định
       User::factory()->create([
            'name' => 'Admin EngToeic',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'), // Mật khẩu là 123456
            'role' => 'admin',
        ]);

        // Gọi các seeder chạy cùng nhau
        $this->call([
            TopicSeeder::class,
            GrammarSeeder::class,
        ]);
    }
}