<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class productsController extends Controller
{
    public function create(Request $request)
    {
        $images = $request->file('productPicture');

        $profilePicture = [];
        $counter = 1;
        foreach ($images as $image) {

            $imageName = (time() + $counter) . '.' . $image->extension();
            $counter += rand(0, 100);
            $image->move(public_path('images'), $imageName);
            array_push($profilePicture, $imageName);
        }
        $counter = 0;

        $productsArray = [
            "name" => $request->name,
            "slug" => SlugService::createSlug(Product::class, 'slug', $request->name),
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'productPicture' => implode("||", $profilePicture),
            'category' => $request->category,
            'createdBy' => Auth::user()->id,
        ];
        $success = Product::create($productsArray);
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
}
