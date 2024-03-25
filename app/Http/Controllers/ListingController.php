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
    Category,
    ListingCategory,
    Deal,
    Email
};
use Yajra\DataTables\Contracts\DataTable;
use DataTables;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ListingSubmission;
use App\Notifications\ListingApprovalNotification;
class ListingController extends Controller
{
    // =============== API Functions ======================
    // this function show a listing details against specific listing Id
    public function showListingDetail(Request $request){
        try{
            $slug = $request->slug;
            $listingData = Listing::with(['deals', 'getCategories'])->where('slug', $slug)->first();

            $categoryIds = [];
            foreach ($listingData['getCategories'] as $category) {
                $categoryIds[] = $category['category_id'];
            }

            $structuredListingData = [
                'id' => $listingData['id'],
                'user_id' => $listingData['user_id'],
                'company_name' => $listingData['company_name'],
                'company_link' => $listingData['company_link'],
                'company_tagline' => $listingData['company_tagline'],
                'short_description' => $listingData['short_description'],
                'company_logo' => $listingData['company_logo'],
                'status' => $listingData['status'],
                'deleted_at' => $listingData['deleted_at'],
                'created_at' => $listingData['created_at'],
                'updated_at' => $listingData['updated_at'],
                'plan_id' => $listingData['plan_id'],
                'deals' => $listingData['deals'],
                'slug' => $listingData['slug'],
                'screenshot_image' => $listingData['screenshot_image'],
                'category_ids' => $categoryIds,
            ];
            if(!empty($listingData)){
                return response()->json(["success"=>true, "listingData"=>$structuredListingData, "status" => 200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"No listing found against this . $listing_id", "status" => 400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong", "error"=>$e->getMessage()], 400);
        }
    }

    // for adding listing and updating the record
    public function addListing(Request $request){
        $validator = Validator::make($request->all(),[
            'company_name' => 'required|string',
            'company_categories' => 'required',
            'company_tagline' => 'required',
            'short_description' => 'required|string',
            // 'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validations = ['company_name', 'company_categories', 'company_tagline', 'short_description'];
        $errors = [];
        foreach($validations as $val){
            foreach($validator->errors()->get($val) as $error){
                $errors[] = $error;
            }
        }
        if(!empty($errors)){
            return response()->json(["success"=>false, "error"=>$errors, "status"=>400], 400);
        }

        try{
            // if ($request->file('company_logo')) {
            //     $file = $request->file('company_logo');
            //     $fileName = time() .'_' .  rand() . '.' . $file->getClientOriginalExtension();
            //     $destinationPath = public_path('assets/listing_images'); // Use public_path() to get the physical file system path
            //     $file->move($destinationPath, $fileName);
            // }
            
            // Saving Short Description of Editor
            $designDocument  = $request->short_description;
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml($designDocument, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors(false);
            $images = $dom->getElementsByTagName('img');

            foreach($images as $item => $image){
                $data = $image->getAttribute("src");
                $styles = $image->getAttribute("style");
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $imgeData = base64_decode($data);
                $image_name= time().$item.'.png';
                $path = public_path().'/assets/listing_description_image/' . $image_name;
                file_put_contents($path, $imgeData);
                $image->removeAttribute('src');
                $image->setAttribute('src', asset('assets/listing_description_image/' . $image_name));
                $image->setAttribute('width' , $styles);
                $image->setAttribute('class', 'ck-image');
                $image->removeAttribute("style");
            }
            $content = $dom->saveHTML();
            
            // Creating Slug
            $companyName = $request->company_name;
            $slug = strtolower($companyName);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
            // Checking if slug is already in the database
            $originalSlug = $slug;
            for($i = 1; Listing::where('slug', $slug)->exists(); $i++){
                $slug = $originalSlug . '-' . $i;
            }

            $user_id = Auth::user()->id;
            $listingId = $request->id;
            $listing = Listing::where('id', $listingId)->first(); // 0 means draft    

            //Generating Screenshot image of a given website link
            $method = 'GET';
            $url = 'https://api.screenshotone.com/take?access_key='.env('SCREENSHOT_ACCESS').'&url='.$listing->company_link.'&viewport_width=1920&viewport_height=1280&device_scale_factor=1&image_quality=80&format=jpg&block_ads=true&block_cookie_banners=true&full_page=false&block_trackers=true&block_banners_by_heuristics=false&delay=10&timeout=60';
            $options = [
                'http' => [
                    'method' => $method,
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if($result === FALSE){
                return response()->json(["success"=>false, "msg"=>"Screenshot for given company website is not allowed", "status"=>400], 400);
            }else{
                $imageName = rand() . '_' . 'image' .  '.jpg';
                $imagePath = public_path('assets/screenshot_images/' . $imageName);
                file_put_contents($imagePath, $result);
            }
            if(!empty($listing) || isset($listing)){
                $listing->update([
                    'company_name' => $companyName,
                    'short_description' => $content,
                    'company_tagline' => $request->company_tagline,
                    // 'company_logo' => $fileName,
                    'status' => '2', // 2 means approved by default 
                    'slug' => $slug,
                    'screenshot_image' => asset('assets/screenshot_images/' . $imageName)
                ]);
                // add categories to the user
                $categories = json_decode($request->company_categories);
                ListingCategory::where('listing_id', $listingId)->delete();
                foreach($categories as $category){
                    $categoryInsert = new ListingCategory();
                    $categoryInsert->listing_id = $listingId;
                    $categoryInsert->category_id = $category;
                    $categoryInsert->save();
                }
                // adding deals
                $deals = json_decode($request->deals);
                if(isset($deals)){
                    foreach($deals as $deal){
                        $insertDeal = new Deal();
                        $insertDeal->listing_id = $listingId;
                        $insertDeal->deal_name = $deal->deal_name;
                        $insertDeal->currency = $deal->currency;
                        $insertDeal->discount_price = $deal->discount_price;
                        $insertDeal->actual_price = $deal->actual_price;
                        $insertDeal->billing_interval = $deal->billing_interval;
                        $insertDeal->type = $deal->type;
                        $insertDeal->coupon_code = $deal->coupon_code;
                        $insertDeal->link = $deal->link;
                        $insertDeal->save();
                    }
                }

                // Getting the data from the database against Listing for email
                $emailData = Email::where('type', 'listing_submission')->first();
                $emailSubject = $emailData->subject;
                $emailContent = $emailData->message;
                // Sending email
                Notification::route("mail", Auth::user()->email)->notify(new ListingSubmission($emailSubject, $emailContent));
                return response()->json(["success"=>true, "msg"=>"Listing updated successfully", "status"=>200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"Listing has already been created for the selected tool", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage(), "line"=>$e->getLine(), "status" => 400], 400);
        }
    }


    public function updateListingUser(Request $request){
        $validator = Validator::make($request->all(),[
            'company_name' => 'required|string',
            'company_categories' => 'required',
            'company_tagline' => 'required',
            'short_description' => 'required|string',
            // 'company_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validations = ['company_name', 'company_categories', 'company_tagline', 'short_description'];
        $errors = [];
        foreach($validations as $val){
            foreach($validator->errors()->get($val) as $error){
                $errors[] = $error;
            }
        }
        if(!empty($errors)){
            return response()->json(["success"=>false, "error"=>$errors, "status"=>400], 400);
        }

        try{
            // if ($request->file('company_logo')) {
            //     $file = $request->file('company_logo');
            //     $fileName = time() .'_' .  rand() . '.' . $file->getClientOriginalExtension();
            //     $destinationPath = public_path('assets/listing_images'); // Use public_path() to get the physical file system path
            //     $file->move($destinationPath, $fileName);
            // }
            
            // Saving Short Description of Editor
            $designDocument  = $request->short_description;
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml($designDocument, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors(false);
            $images = $dom->getElementsByTagName('img');

            foreach($images as $item => $image){
                $data = $image->getAttribute("src");
                // $imageData = $image->getAttribute("class");
                $styles = $image->getAttribute("style");
                $image_explode = explode(';', $data);
                if(count($image_explode) == 2){
                    list($type, $data) = [$image_explode[0] , $image_explode[1]];
                    list(, $data)      = explode(',', $data);
                }else{
                    continue;
                }
                // list($type, $data) = explode(';', $data);
                // list(, $data)      = explode(',', $data);
                $imgeData = base64_decode($data);
                $image_name= time().$item.'.png';
                $path = public_path().'/assets/listing_description_image/' . $image_name;
                file_put_contents($path, $imgeData);
                $image->removeAttribute('src');
                $image->setAttribute('src', asset('assets/listing_description_image/' . $image_name));
                $image->setAttribute('width' , $styles);
                $image->setAttribute('class', 'ck-image');
                $image->removeAttribute("style");
            }
            $content = $dom->saveHTML();
            
            // Creating Slug
            $companyName = $request->company_name;
            $slug = strtolower($companyName);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
            // Checking if slug is already in the database
            $originalSlug = $slug;
            for($i = 1; Listing::where('slug', $slug)->exists(); $i++){
                $slug = $originalSlug . '-' . $i;
            }

            $user_id = Auth::user()->id;
            $listingId = $request->id;
            $listing = Listing::where('id', $listingId)->first(); // 0 means draft    
            if(!empty($listing) || isset($listing)){
                $listing->update([
                    'company_name' => $companyName,
                    'short_description' => $content,
                    'company_tagline' => $request->company_tagline,
                    // 'company_logo' => $fileName,
                    'status' => '2', // 2 means approved by default
                    'slug' => $slug,
                ]);
                // add categories to the user
                $categories = json_decode($request->company_categories);
                if(isset($categories) && !empty($categories) && count($categories) > 0){
                    ListingCategory::where('listing_id', $listingId)->delete();
                    foreach($categories as $category){
                        $categoryInsert = new ListingCategory();
                        $categoryInsert->listing_id = $listingId;
                        $categoryInsert->category_id = $category;
                        $categoryInsert->save();
                    }
                }
                

                Deal::where('listing_id', $listingId)->delete();
                // adding deals
                $deals = json_decode($request->deals);
                if(isset($deals)){
                    foreach($deals as $deal){
                        $insertDeal = new Deal();
                        $insertDeal->listing_id = $listingId;
                        $insertDeal->deal_name = $deal->deal_name;
                        $insertDeal->currency = $deal->currency;
                        $insertDeal->discount_price = $deal->discount_price;
                        $insertDeal->actual_price = $deal->actual_price;
                        $insertDeal->billing_interval = $deal->billing_interval;
                        $insertDeal->type = $deal->type;
                        $insertDeal->coupon_code = $deal->coupon_code;
                        $insertDeal->link = $deal->link;
                        $insertDeal->save();
                    }
                }
                return response()->json(["success"=>true, "msg"=>"Listing updated successfully", "status"=>200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"Listing has already been created for the selected tool", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "error"=>$e->getMessage(), "line"=>$e->getLine(), "status" => 400], 400);
        }
    }

    // for deleting Listing 
    public function deleteListingUser(Request $request){
        try{
            $id = $request->listing_id;
            $deleteListing = Listing::where('id', $id)->delete(); // softdelete
            $deleteListingCategory = ListingCategory::where('listing_id', $id)->delete();
            $deleteDeals = Deal::where('listing_id', $id)->delete(); // softdelete
            if($deleteListing !== false && $deleteListingCategory !== false && $deleteDeals !== false){
                return response()->json(['success'=>true, "msg"=>"Listing Deleted Successfully", "status"=>200], 200);
            }else{
                return response()->json(['success'=>false, "msg"=>"Listing Deletion Failure", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something Went Wrong", "status"=>400], 400);
        }
    }

    public function showAllListing(){
        try{
            $userId = Auth::user()->id;
            $listings = Listing::with(['plan', 'deals', 'subscriptions'])
                ->where('user_id', $userId)
                ->where('deleted_at', '=', null)
                ->get()
                ->map(function ($listing) {
                    $listingStatus = '';
                    if($listing->status == "0"){
                        $listingStatus = "Draft";
                    }elseif($listing->status == "1"){
                        $listingStatus = "Pending";
                    }elseif($listing->status == "2"){
                        $listingStatus = "Approved";
                    }elseif($listing->status == "3"){
                        $listingStatus = "Rejected";
                    }
                    return [
                        'id' => $listing->id,
                        'company_name' => $listing->company_name,
                        'company_link' => $listing->company_link,
                        'plan' => [
                            'id' => $listing->plan->id,
                            'plan_type' => $listing->plan->plan_type,
                            'plan_name' => $listing->plan->plan_name,
                            'discounted_price' => $listing->plan->discounted_price,
                            'recurring_price' => $listing->plan->recurring_price,
                        ],
                        'status' => $listingStatus,
                        'slug' => $listing->slug,
                        'screenshot_image' => $listing->screenshot_image,
                        'subscription_id' => $listing->subscriptions->stripe_subscription_id ?? NULL,
                        'has_deals' => $listing->deals->count() > 0,
                    ];
                });

                // dd($listings->subscriptions);

            if(count($listings) > 0){
                return response()->json(["success"=>true, "listings"=>$listings, "status"=>200], 200);
            }else{
                return response()->json(["success"=>false, "msg"=>"No listings found", "status"=>400], 400);
            }
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong", "error" => $e->getMessage(), "status"=>400], 400);
        }
        
    }


    public function showCategoryListing(Request $request){
        try{
            if(isset($request->slug)){
                // $listingsData = Category::with('categoryList')
                // ->where('slug' , $slug)
                // ->first();
                $slug = $request->slug;
                $categoryId = Category::where('slug', $slug)->value("id");
                $categoryListing = ListingCategory::where('category_id', $categoryId)->get();
                $featuredListings = [];
                $otherMonthListings = [];
                foreach ($categoryListing as $listing) {
                    $listingModel = Listing::where('id', $listing->listing_id)->with(['plan','deals'])->where('status', '2')->first();           
                    if ($listingModel) {
                        $planType = $listingModel->plan->plan_type;                
                        if ($planType === 'Featured') {
                            $hasMultipleDeals = false;
                            if(count($listingModel->deals) > 0){
                                $hasMultipleDeals = true;
                            }
                            $listingModel->has_deals = $hasMultipleDeals;
                            array_unshift($featuredListings, $listingModel);
                        } elseif ($planType === 'Monthly' || $planType === 'Yearly' || $planType == 'Admin Plan') {
                            $hasMultipleDeals = false;
                            if(count($listingModel->deals) > 0){
                                $hasMultipleDeals = true;
                            }
                            $listingModel->has_deals = $hasMultipleDeals;
                            $otherMonthListings[] = $listingModel;
                        }
                    }
                }

                $listings = array_merge($featuredListings, $otherMonthListings);
                $responseListings = [];
                foreach($listings as $listing){
                    $filteredListings = collect($listing)->forget('deals')->toArray();
                    $responseListings[] = $filteredListings;
                }

                return response()->json(["success"=>true, "listings"=>$responseListings, "status"=>200], 200);
            }else{
                $listings = Listing::where('status', '2')
                ->leftJoin('plans', 'listings.plan_id', '=', 'plans.id')
                ->orderByRaw('plans.plan_type = "Featured" DESC')
                ->select("listings.*", "plans.plan_type")
                ->get();
                return response()->json(["success"=>true, "listings"=>$listings, "status"=>200], 200);
            }
            
        }catch(\Exception $e){
            return response()->json(["success"=>false, "msg"=>"Something went wrong","error" => $e->getMessage(), "status"=>400], 400);
        }
    }







    // =============== Admin Functions ======================
    public function add_listing(){
        $categories = Category::where('status', 'activate')->get();
        return view('listings/add-listings', compact('categories'));
    }

    public function storeListing(Request $request){
        $request->validate([
            // "companyImage" => "required|mimes:jpeg,png,jpg,gif|max:2048",
            "companyName" => "required",
            "companyTagLine" => "required",
            "websiteLink" => "required",
            "short_description" => "required",
            "category" => "required",
        ],[
            // "companyImage.required" => "Company image is required.",
            // "companyImage.image" => "The file must be an image.",
            // "companyImage.mimes" => "The image must be a file of type: jpeg, png, jpg, gif.",
            // "companyImage.max" => "The image may not be greater than 2048 kilobytes in size.",
            "companyName.required" => "Company name is required.",
            "companyTagLine.required" => "Company tagline is required.",
            "category.required" => "Please select at least one category",
        ]);
        try{
            // Creating Slug
            $companyName = $request->companyName;
            $slug = strtolower($companyName);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
            // Checking if slug is already in the database
            $originalSlug = $slug;
            for($i = 1; Listing::where('slug', $slug)->exists(); $i++){
                $slug = $originalSlug . '-' . $i;
            }

            $user_id = Auth::user()->id;
            $storeListing = new Listing();
            $storeListing->user_id = $user_id;
            // if($request->file('companyImage')){
            //     $file = $request->file('companyImage');
            //     $fileName = time() .'_' .  rand() . '.' . $file->getClientOriginalExtension();
            //     $destinationPath = public_path('assets/listing_images'); // Use public_path() to get the physical file system path
            //     $file->move($destinationPath, $fileName);
            //     $storeListing->company_logo = $fileName;
            // }
            $storeListing->company_name = $companyName;
            $storeListing->company_tagline = $request->companyTagLine;

            // saving summernote content
            $designDocument = $request->short_description;
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml($designDocument, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors(false);
            $images = $dom->getElementsByTagName('img');

            foreach($images as $item => $image){
                $data = $image->getAttribute("src");
                $styles = $image->getAttribute("style");
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $imgeData = base64_decode($data);
                $image_name= time().$item.'.png';
                $path = public_path().'/assets/listing_description_image/' . $image_name;
                file_put_contents($path, $imgeData);
                $image->removeAttribute('src');
                $image->setAttribute('src', asset('assets/listing_description_image/' . $image_name));
                $image->setAttribute('width' , $styles);
                $image->setAttribute('class', 'ck-image');
                $image->removeAttribute("style");
            }
            $content = $dom->saveHTML();
            $storeListing->short_description = $content;
            $storeListing->plan_id = 4;
            $status = $request->status;
            $saveStatus = "0";
            if($status == "approve"){
                $saveStatus = "2";
            }elseif($status == "pending"){
                $saveStatus = "1";
            }elseif($status == "reject"){
                $saveStatus = "3";
            }
            $storeListing->status = $saveStatus;
            $storeListing->company_link = $request->websiteLink;
            $storeListing->slug = $slug;

            //Generating Screenshot image of a given website link
            $method = 'GET';
            $url = 'https://api.screenshotone.com/take?access_key='.env('SCREENSHOT_ACCESS').'&url='.$request->websiteLink.'&viewport_width=1920&viewport_height=1280&device_scale_factor=1&image_quality=80&format=jpg&block_ads=true&block_cookie_banners=true&full_page=false&block_trackers=true&block_banners_by_heuristics=false&delay=10&timeout=60';
            $options = [
                'http' => [
                    'method' => $method,
                ],
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if($result === FALSE){
                return response()->json(["success"=>false, "msg"=>"Screenshot for given company website is not allowed", "status"=>400], 400);
            }else{
                $imageName = rand() . '_' . 'image' .  '.jpg';
                $imagePath = public_path('assets/screenshot_images/' . $imageName);
                file_put_contents($imagePath, $result);
            }

            $storeListing->screenshot_image = asset('assets/screenshot_images/' . $imageName);
            if($storeListing->save()){
                foreach($request->category as $category_id){
                    $listingCategory = new ListingCategory();
                    $listingCategory->category_id = $category_id;
                    $listingCategory->listing_id = $storeListing->id;
                    $listingCategory->save();
                }
                return redirect()->route('listings')->with(['success'=>"Listing Added Successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong.... Please try again later"]);
        }
    }

    public function editListing($id){
        $categories = Category::where('status', 'activate')->get();
        $listingData = Listing::with('plan')->where('id', $id)->first();
        $listingCategory = ListingCategory::where('listing_id', $id)->get();
        $categoryId = [];
        foreach($listingCategory as $listCat){
            $categoryId[] = $listCat->category_id;
        }
        return view('listings/edit-listings', compact('listingData', 'categories', 'categoryId'));
    }

    public function updateListing(Request $request){
        // dd($request->all());
        $request->validate([
            // "companyImage" => "required|mimes:jpeg,png,jpg,gif|max:2048",
            "companyName" => "required",
            "companyTagLine" => "required",
            "websiteLink" => "required",
            "short_description" => "required",
            "category" => "required",
        ],[
            // "companyImage.required" => "Company image is required.",
            // "companyImage.image" => "The file must be an image.",
            // "companyImage.mimes" => "The image must be a file of type: jpeg, png, jpg, gif.",
            // "companyImage.max" => "The image may not be greater than 2048 kilobytes in size.",
            "companyName.required" => "Company name is required.",
            "companyTagLine.required" => "Company tagline is required.",
            "category.required" => "Please select at least one category",
        ]);
        try{
            // Creating Slug
            $companyName = $request->companyName;
            $slug = strtolower($companyName);
            $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
            $slug = trim($slug, '-');
            // Checking if slug is already in the database
            $originalSlug = $slug;
            for($i = 1; Listing::where('slug', $slug)->exists(); $i++){
                $slug = $originalSlug . '-' . $i;
            }

            $id = $request->listing_id;
            $storeListing = Listing::find($id);
            // if($request->file('companyImage')){
            //     $file = $request->file('companyImage');
            //     $fileName = time() .'_' .  rand() . '.' . $file->getClientOriginalExtension();
            //     $destinationPath = public_path('assets/listing_images'); // Use public_path() to get the physical file system path
            //     $file->move($destinationPath, $fileName);
            //     $storeListing->company_logo = $fileName;
            // }
            $storeListing->company_name = $companyName;
            $storeListing->company_tagline = $request->companyTagLine;
            
            // saving summernote content
            $designDocument = $request->short_description;
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHtml($designDocument, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            libxml_use_internal_errors(false);
            $images = $dom->getElementsByTagName('img');

            foreach($images as $item => $image){
                $data = $image->getAttribute("src");
                // $imageData = $image->getAttribute("class");                
                $styles = $image->getAttribute("style");
                $image_explode = explode(';', $data);
                if(count($image_explode) == 2){
                    list($type, $data) = [$image_explode[0] , $image_explode[1]];
                    list(, $data) = explode(',', $data);
                }else{
                    continue;
                }
                // list($type, $data) = explode(';', $data);
                // list(, $data)      = explode(',', $data);
                $imgeData = base64_decode($data);
                $image_name= time().$item.'.png';
                $path = public_path().'/assets/listing_description_image/' . $image_name;
                file_put_contents($path, $imgeData);
                $image->removeAttribute('src');
                $image->setAttribute('src', asset('assets/listing_description_image/' . $image_name));
                $image->setAttribute('width' , $styles);
                $image->setAttribute('class', 'ck-image');
                $image->removeAttribute("style");
            }
            $content = $dom->saveHTML();
            $storeListing->short_description = $content;
            $status = $request->status;
            $saveStatus = "0";
            if($status == "approve"){
                $saveStatus = "2";
            }elseif($status == "pending"){
                $saveStatus = "1";
            }elseif($status == "reject"){
                $saveStatus = "3";
            }
            $storeListing->status = $saveStatus;
            $storeListing->plan_id = $request->plan_id;
            $storeListing->company_link = $request->websiteLink;
            $storeListing->slug = $slug;
            if($storeListing->save()){
                $deleteCategory = ListingCategory::where('listing_id', $id)->delete();
                foreach($request->category as $category_id){
                    $listingCategory = new ListingCategory();
                    $listingCategory->listing_id = $id;
                    $listingCategory->category_id = $category_id;
                    $listingCategory->save();
                }

                // $status = $storeListing->status;
                // if($status == "2" || $status == "3"){
                    // Getting User Email
                    // $user_id = $storeListing->user_id;
                    // $userEmail = User::where('id', $user_id)->value("email");
                    // Getting Status
                    // $approvalStatus = "";
                    // if($status == "2"){
                    //     $approvalStatus = "Approved";
                    // }elseif($status == "3"){
                    //     $approvalStatus = "Rejected";
                    // }
                    // Getting the data from database against listing
                    // $emailData = Email::where('type', 'listing_approval')->first();
                    // $subjectEm = $emailData->subject;
                    // $emailSubject = str_replace("[LISTING_APPROVAL]", $approvalStatus, $subjectEm);
                    // $emailMessage = $emailData->message;
                    // $emailContent = str_replace("[LISTING_APPROVAL]", $approvalStatus, $emailMessage);
                    // // Sending email
                    // Notification::route("mail", $userEmail)->notify(new ListingApprovalNotification($emailSubject, $emailContent));
                // }               
                return redirect()->route('listings')->with(['success'=>"Listing Updated Successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong. Please try again later"]);
        }
    }

    public function deleteListing(Request $request){
        try{
            $id = $request->listing_id;
            $deleteListing = Listing::where('id', $id)->delete();
            $deleteListingCategory = ListingCategory::where('listing_id', $id)->delete();
            return redirect()->back()->with(['success'=>"Listing deleted Successfully"]);
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => "Something Went Wrong.... Please try again later"]);
        }
    }

    public function listingDataTable(Request $request){
        if(isset($request->userId)){
            $userId = $request->userId;
            $listings = Listing::where('user_id', $userId)
                           ->with(['getCategories', 'plan'])
                           ->where(function($query) {
                               $query->where('status', '1')
                                     ->orWhere('status', '2')
                                     ->orWhere('status', '3');
                           })
                           ->orderBy('id', 'DESC')
                           ->get();
        }else{
            $listings = Listing::with(['getCategories', 'plan'])
            ->where(function($query){
                $query->where('status', '1')
                      ->orWhere('status', '2')
                      ->orWhere('status', '3');
            })
            ->orderBy('id', 'DESC')
            ->get();
        }
        return Datatables::of($listings)
                    ->addIndexColumn()
                    ->addColumn('company_name', function($listing){
                        $companName = '<span style="white-space: pre-wrap;">'.$listing->company_name.'</span>';
                        return $companName;
                    })
                    ->addColumn('listing_link', function($listing){
                        $listingLink = '<a href="'.$listing->company_link.'" target="_blank">
                        <i class="fa fa-eye"></i>
                    </a>';
                        return $listingLink;
                    })
                    ->addColumn('categories', function($listing){
                        $categories = [];
                        foreach ($listing->getCategories as $category_id) {
                            $categoryId = $category_id->category_id;
                            $categoryName = \App\Models\Category::select('category_name')->where('id', $categoryId)->value("category_name");
                            $categories[] =  '<span style="white-space: pre-wrap;">'.$categoryName.'</span>'; 
                        }
                        return implode(', ', $categories);
                    })
                    ->addColumn('package', function($listing){
                        return $listing->plan->plan_type;
                    })
                    ->addColumn('status', function($listing){
                        $status = "";
                        if($listing->status == 1){
                           $status = "Pending";
                        }
                        elseif($listing->status == 2){
                            $status = "Approved";
                        }
                        elseif($listing->status == 3){
                            $status = "Rejected";
                        }
                        return $status;
                    })
                    ->addColumn('screenshot_image', function($listing){
                        if(isset($listing->screenshot_image)){
                            $imageLink = '
                            <a href="'.$listing->screenshot_image.'" target="_blank">
                                    <i class="fa fa-eye fs-15"></i>
                                </a>
                            ';
                        }else{
                            $imageLink = '';
                        }
                        return $imageLink;
                    })
                    ->addColumn('action', function($listing) {
                        $btns = '
                            <a href="' . url('edit-listing', ["id" => $listing->id]) . '" class="edit-cat text-success">
                                <i class="las la-pencil-alt fs-20"></i>
                            </a>
                            <a href="#" class="del-cat text-danger mx-2" data-id="' . $listing->id . '">
                                <i class="lar la-trash-alt fs-20"></i>
                            </a>
                        ';
                        return $btns;
                    })
                    
                    ->rawColumns(['company_name', 'listing_link', 'categories', 'package', 'status', 'screenshot_image', 'action'])
                    ->make(true);
    }
}
