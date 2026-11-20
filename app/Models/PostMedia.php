<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    public $timestamps = false;

    protected $table = 'post_media';

    protected $fillable = [
        'post_id',
        'media_type',
        'file_path',
        'mime_type',
        'file_size',
        'sort_order',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'media_type' => MediaType::class,
            'file_size' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
