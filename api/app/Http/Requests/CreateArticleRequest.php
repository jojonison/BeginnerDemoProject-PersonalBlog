<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class CreateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create articles', Article::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'post' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'string'],
            'tags' => ['required'],
        ];
    }
}
