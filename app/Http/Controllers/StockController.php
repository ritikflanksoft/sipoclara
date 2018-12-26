<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Stock;
use Validator;

class StockController extends Controller
{
    public function getDataForStock(Request $request){
    	$userObj = User::where('status', '1')->get();
    	return response()->json([
    		'status' => 200,
    		'data' => $userObj
    	], 200);
    }

    //--create booking stock
    public function createBookingStock(Request $request){
    	$validator = Validator::make($request->all(), [ 
            'referencename' => 'required', 
            'refdate' => 'required', 
            'bomList' => 'required', 
            'quantity' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);  
        }
        $getRefDate = $request->refdate['year'].'-'.$request->refdate['month'].'-'.$request->refdate['day'];
        $stockObj = new Stock;
        $stockObj->reserveDate = $getRefDate;
        $stockObj->referenceId = $request->referencename;
        $stockObj->bomId = $request->bomList;
        $stockObj->quantityReserve = $request->quantity;
        $stockObj->createdBy = 1;
        if($stockObj->save()){
        	return response()->json([
        		'status' => 200,
        		'data' => 'success'
        	], 200);
        }else{
        	return response()->json([
        		'status' => 401,
        		'data' => 'Some thing wrong!'
        	], 200);
        }
    }
}
