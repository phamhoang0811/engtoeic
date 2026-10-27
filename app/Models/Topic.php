<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    // Thêm dòng này để cho phép lưu dữ liệu vào các cột
    protected $fillable = [
        'name',
        'slug',
        'target_score',
        'image', // Thêm dòng này
        'description',
    ];

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
