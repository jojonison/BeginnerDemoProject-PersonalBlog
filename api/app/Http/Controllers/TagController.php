<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function listTags() {
        $tags = Tag::all();
        return response()->json(TagResource::collection($tags));
    }

    public function createTag(CreateTagRequest $request) {
        $tag = Tag::create(['name' => $request->name]);
        return response()->json(TagResource::make($tag));
    }

    public function destroyTag($id) {
        $tag = Tag::byHashOrFail($id);
        $tag->delete();
        return response()->json(TagResource::make($tag));
    }
}
