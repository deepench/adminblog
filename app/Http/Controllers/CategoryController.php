<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return Category::latest()->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        if ($request->iconImage) {

            $name = time() . '.' . explode('/', explode(':', substr($request->iconImage, 0, strpos($request->iconImage, ';')))[1])[1];

            \Image::make($request->iconImage)->save(public_path('img/category/') . $name);
            $request->merge(['iconImage' => $name]);
        }
        $category = new Category;
        $category->categoryName = $request->categoryName;
        $category->iconImage = $name;
        $category->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Category registered successfully'
            ]
        );
    }
    public function update(Request $request, $id)
    {
        $category = Category::findorFail($id);
        $this->validate($request, [
            'categoryName' => 'required',
        ]);
        $currentPhoto = $category->iconImage;
        if ($request->iconImage != $currentPhoto) {
            $name = time() . '.' . explode('/', explode(':', substr($request->iconImage, 0, strpos($request->iconImage, ';')))[1])[1];

            \Image::make($request->iconImage)->save(public_path('img/category/') . $name);
            $request->merge(['iconImage' => $name]);

            $categoryPhoto = public_path('img/category/') . $currentPhoto;
            if (file_exists($categoryPhoto)) {
                @unlink($categoryPhoto);
            }
        }

        $category->update($request->all());
        return ['message' => 'update the category info'];
    }
    public function destroy($id)
    {
        $category = Category::findorFail($id);

        //delete the category
        $category->delete();
    }
}
