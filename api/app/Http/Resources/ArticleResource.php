<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->hash,
            'title' => $this->title,
            'post' => $this->post,
            'category' => $this->whenLoaded('category', function () {
                return $this->category
                    ? [
                        'id' => $this->category->id,
                        'name' => $this->category->name,
                    ]
                    : null;
            }),
            'user' => UserResource::make($this->user),
            'slug' => Str::slug($this->title),
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]);
            }),
            'image' => $this->image ? asset('storage/' . $this->image) : null,
        ];
    }
}
