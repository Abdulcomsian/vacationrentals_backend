<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Plan,
    User,
};

class PlanController extends Controller
{
    public function showPlans(){
        try{
            $plans = Plan::get();
            if(!empty($plans)){
                return response()->json(["success"=>true, "plans"=>$plans], 200);
            }
        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something went wrong", "error"=>$e->getMessage()], 400);
        }
    }

    
}
