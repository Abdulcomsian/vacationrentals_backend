<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\{
    User,
    Plan,
    Subscription,
    Listing,
    Category
};
class ListingController extends Controller
{
    // =============== API Functions ======================
    // public function showListingDetail(Request $request){
    //     try{
    //         $listing_id = $request->listing_id;
    //         $listingData = Listing::where('id', $listing_id)->first();
    //         if(!empty($listingData)){
    //             return response()->json(["success"=>true, "listingData"=>$listingData], 200);
    //         }else{
    //             return response()->json(["success"=>false, "msg"=>"Sorry, Data not found related to your id . $listing_id"]);
    //         }
    //     }catch(\Exception $e){
    //         return response()->json(["success"=>false, "msg"=>"Something went wrong", "error"=>$e->getMessage()], 400);
    //     }
    // }

    // public function addListing(Request $request){
    //     try{
    //         $validator = Validator::make($request->all(),[
    //             'tool_name' => 'required|string',
    //             'short_description' => 'required|string',
    //             'tool_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         if($validator->fails()){
    //             return response()->json(["success" => false, "msg"=>"Validation Error", "error"=>$validator->getMessageBag()]);
    //         }

    //         if ($request->file('tool_image')) {
    //             $file = $request->file('tool_image');
    //             $fileName = time() .'_' .  rand() . '.' . $file->getClientOriginalExtension();
    //             $destinationPath = public_path('assets/listing_images'); // Use public_path() to get the physical file system path
    //             $file->move($destinationPath, $fileName);
    //         }
            
    //         $user_id = Auth::user()->id;
    //         $listing = Listing::where('user_id', $user_id)->update([
    //             'tool_name' => $request->tool_name,
    //             'short_description' => $request->short_description,
    //             'long_description' => $request->long_description,
    //             'tool_image' => $fileName,
    //         ]);

    //         return response()->json(["success"=>true, "msg"=>"Listing Added Successfully"], 200);

    //     }catch(\Exception $e){
    //         return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage()], 400);
    //     }
    // }


    // =============== Admin Functions ======================
    public function add_listing(){
        $categories = Category::where('status', 'activate')->get();
        return view('listings/add-listings', compact('categories'));
    }

    public function storeListing(Request $request){
        dd($request->all());
    }
}
