<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Plan,
    User,
};

class PlanController extends Controller
{
    // ================ API function ====================
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

    // ================ Admin Functions =================
    public function showEdit(Request $request){
        $id = $request->id;
        $plan = Plan::where('id', $id)->first();
        return response()->json(["plan"=>$plan]);
    }

    public function updatePlan(Request $request){
        try{
            $id = $request->plan_id;
            $plan = Plan::find($id);
            $plan->plan_name = $request->plan_name;
            $plan->price = $request->price;
            $plan->description = $request->description;
            if($plan->save()){
                return redirect()->back()->with(['success'=>"Plan Updated Successfully"]);
            }
        }catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    
}
