<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function listCategories() {
        $categories = Category::all();
        return response()->json(CategoryResource::collection($categories));
    }

    public function createCategory(Request $request) {
        $category = Category::create(['name' => $request->name]);
        return response()->json(CategoryResource::make($category));
    }

    public function destroyCategory($id) {
        $category = Category::byHashOrFail($id);
        $category->delete();
        return response()->json(CategoryResource::make($category));
    }
}
