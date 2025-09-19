<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Tag extends Model
{
    use HasFactory, HashableId, softDeletes;

    protected $fillable = ['name'];

    public function articles(): BelongsToMany {
        return $this->belongsToMany(Article::class);
    }
}
