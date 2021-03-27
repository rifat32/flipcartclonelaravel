<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class categoriesController extends Controller
{
    public function create(Request $request)
    {

        $categoriesArray = [
            "name" => $request->name,
            "slug" => SlugService::createSlug(Category::class, 'slug', $request->name)
        ];
        if ($request->parentId) {
            $categoriesArray = array_merge($categoriesArray, ["parentId" => $request->parentId]);
        }

        $success = Category::create($categoriesArray);
        if (!$success) {
            return response()->json([
                "status" => 400,
                "err" => "Something went wrong"
            ]);
        } else {
            return response()->json([
                "status" => 201,
                "data" => $success
            ]);
        }
    }
    public function get()
    {
        $categories = DB::table('categories')->get();
        if ($categories) {
            return response()->json([
                'status' => 200,
                'data' => $categories
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'err' => 'Something went wrong',
            ]);
        }
    }
}
