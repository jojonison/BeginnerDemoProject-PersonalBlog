<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Article extends Model
{
    use SoftDeletes, HasFactory, HashableId;

    protected $fillable = [
        'user_id',
        'title',
        'post',
        'category_id',
        'image'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class)->withTrashed();
    }
}
