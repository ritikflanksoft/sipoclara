<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Item;
use App\Model\PurchaseRequest;
use App\Model\PurchaseRequestDetail;
use Validator;

class ItemController extends Controller
{
    
    //---- get all items from item model
    public function getItems(Request $Request){
    	$itemObj = Item::where('status', '1')->get();
    	return response()->json([
    		'status' => '200',
    		'data' => $itemObj,
    	], 200);
    }

    public function submitPurchaseRequest(Request $request){
    	 $validator = Validator::make($request->all(), [ 
            'pr' => 'required', 
            'prDate' => 'required', 
            'defineTerms' => 'required', 
            'requestfrom' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

    $purchaseRequestObj = new PurchaseRequest;   
    $purchaseRequestObj->prDate = $request->prDate; 
    $purchaseRequestObj->PrRemark = $request->pr; 
    $purchaseRequestObj->requestFrom = $request->requestfrom; 
    $purchaseRequestObj->requestType = $request->requestmodel; 
    $purchaseRequestObj->purposeId = $request->requestmodel;
    $purchaseRequestObj->purposeRemark = $request->requestmodel;
    $purchaseRequestObj->requestType = $request->requestmodel;
    if($purchaseRequestObj->save()){
    	$purchaseDetailObj = new PurchaseRequestDetail; 
    	$purchaseDetailObj->prId = $purchaseRequestObj->id;
    	$purchaseDetailObj->productId = $request->itemsData;
    	$purchaseDetailObj->targetDate = $request->targetDate;
    	$purchaseDetailObj->qty = $request->quantity;
    	$purchaseDetailObj->createdBy = '1';
    	if($purchaseDetailObj->save()){
    		return response()->json([
    			'status' => 200,
    			'data' => 'success'
    		], 200);
    	}
    }

    }


}
