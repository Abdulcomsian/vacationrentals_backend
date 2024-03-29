<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category,

};

class CategoryController extends Controller
{
    // ================== Admin Functions ======================
    public function storeCategory(Request $request){
        // Adding Validation
        $request->validate([
            'categoryName' => 'required',
            'categoryImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'categoryName.required' => 'The category name field is required.',
            'categoryImage.required' => 'The category image field is required.',
            'categoryImage.image' => 'The category image must be an image file.',
            'categoryImage.mimes' => 'The category image must be a file of type: jpeg, png, jpg, gif.',
            'categoryImage.max' => 'The category image may not be greater than 2048 kilobytes in size.'
        ]);

        try{            
            if($request->file('categoryImage')){
                $file = $request->file('categoryImage');
                $fileDestination = public_path('assets/category_images');
                $fileName = uniqid() . '_' . time() . '.' . $file->extension();
                $file->move($fileDestination, $fileName);
            }

            // Converting String for Slug
            $categorySlug = $request->categoryName;
            $categorySlug = strtolower($categorySlug);
            $categorySlug = str_replace(' ', '_', $categorySlug);

            $categoryData = new Category();
            $categoryData->category_name = $request->categoryName;
            $categoryData->slug = $categorySlug;
            $categoryData->category_image = "assets/category_images". $fileName;
            if($categoryData->save()){
                return redirect()->back()->with(['success' => "Category Added Succesfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong.... Please try again later"]);
        }
    }

    public function deleteCategory(Request $request){
        try{
            $id = $request->category_id;
            $deleteCategory = Category::where('id', $id)->update(["status" => "deactivate"]);
            return redirect()->back()->with(['success' => "Category deactivated Succesfully"]);
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong.... Please try again later"]);
        }
    }

    public function showEditCategory(Request $request){
        $id = $request->id;
        $categoryData = Category::where('id', $id)->first();
        return response()->json(["categoryData" => $categoryData]);
    }

    public function updateCategory(Request $request){
        try{
            $id = $request->category_id;

            // Converting String for Slug
            $categorySlug = $request->categoryName;
            $categorySlug = strtolower($categorySlug);
            $categorySlug = str_replace(' ', '_', $categorySlug);

            $categoryData = Category::find($id);
            $categoryData->category_name = $request->categoryName;
            $categoryData->slug = $categorySlug;
            if($request->file('categoryImage')){
                $file = $request->file('categoryImage');
                $fileDestination = public_path('assets/category_images');
                $fileName = uniqid() . '_' . time() . '.' . $file->extension();
                $file->move($fileDestination, $fileName);
                $categoryData->category_image = "assets/category_images" . $fileName;
            }
            if($categoryData->save()){
                return redirect()->back()->with(['success' => "Category Updated Succesfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['success' => "Category Updated Succesfully"]);
        }
    }


    // ==================== API Functions =======================
    public function showCategory(){
        try{
            $categories = Category::select('id', 'slug', 'category_name', 'category_image')->where('status', 'activate')->get();
            if(count($categories) > 0){
                return response()->json(["success"=>true, "data"=>$categories, "status"=>200], 200);
            }else{
                return response()->josn(["success"=>false, "msg"=>"No Category Found", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong ... ", "status"=>400], 400);
        }
    }
}
