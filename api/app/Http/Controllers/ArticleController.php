<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\EditArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function listLatestArticles() {
        $articles = Article::with('tags', 'category')->latest()->simplePaginate(10);
        return response()->json(ArticleResource::collection($articles));
    }

    public function listOwnArticles(Request $request) {
        $articles = Article::with('tags', 'category')
            ->where('user_id', '=', $request->user()->id)
            ->latest()->simplePaginate(10);
        return response()->json(ArticleResource::collection($articles));
    }

    public function showArticle(Request $request, $id) {
        $article = Article::byHashOrFail($id);
        $article->load(['tags', 'category', 'user']);
        return response()->json(ArticleResource::make($article));
    }

    public function createArticle(CreateArticleRequest $request) {
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create([
            'title' => $request->title,
            'post' => $request->post,
            'category_id' => Category::hashToId($request->category_id),
            'image' => $path,
            'user_id' => $request->user()->id
        ]);

        $tags = collect($request->tags)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id;
        });
        $article->tags()->sync($tags);

        // 🔑 make sure tags & category are loaded
        $article->load(['tags', 'category', 'user']);

        return response()->json(ArticleResource::make($article));
    }

    public function editArticle(EditArticleRequest $request, $id)
    {
        $article = Article::findOrFail(Article::hashToId($id));
        $path = $article->image;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
        }
        $article->update([
            'title' => $request->title,
            'post' => $request->post,
            'category_id' => Category::hashToId($request->category_id),
            'image' => $path,
        ]);
        if ($request->filled('tags')) {
            $tags = collect($request->tags)->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => $tagName])->id;
            });
            $article->tags()->sync($tags);
        }
        $article->load(['tags', 'category', 'user']);
        return response()->json(ArticleResource::make($article));
    }

    public function destroyArticle(Request $request, $id) {
        $article = Article::byHashOrFail($id);
        $article->delete();
        return response()->json(ArticleResource::make($article));
    }
    public function restoreArticle(Request $request, $id) {
        $article = Article::byHashOrFail($id);
        $article->restore();
        return response()->json(ArticleResource::make($article));
    }

    // Used to soft delete the seeded articles
//    public static function destroy() {
//        $articles = Article::where('user_id', '!=', 1);
//        $articles->delete();
//    }
}
