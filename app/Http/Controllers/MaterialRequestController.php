<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Model\Job;
use App\Model\MaterialRequest;
use App\Model\MaterialRequestDetail;
use Auth;
use Validator;

class MaterialRequestController extends Controller
{
    
       public function materialRequestData(Request $request){
        $userData = User::where('status', '1')->get();
        $jobData = Job::where('status', '1')->get();
        $mrId = MaterialRequest::orderBy('id', 'DESC')->first();
        return response()->json([
            'status' => '200',
            'data' => [
                'userData' => $userData,
                'jobData' => $jobData,
                'id' => $mrId->id+1,
            ]
        ], 200);
   }

   public function getBomData(Request $request){
       $jobData = Job::where('id', $request->jobId)->with('bomRecord')->with('fepRecord.itemRecord')->get(); 
       return response()->json([
          'status' => 200,
          'data' => $jobData
       ], 200);
   }

   public function submitmaterialRequest(Request $request){
    $user =  Auth::user()->id;
      $validator = Validator::make($request->all(), [ 
            'address' => 'required', 
            'bomlist' => 'required', 
            'requestto' => 'required', 
            'job' => 'required', 
            'requestedby' => 'required',
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

      $requestMaterial = new MaterialRequest;
      $requestMaterial->mrDate = $request->mrdate;
      $requestMaterial->mrRequestBy = $request->requestedby;
      $requestMaterial->mrRemark = $request->remark;
      $requestMaterial->wareHouse = $request->warehouse;
      $requestMaterial->mrType = $request->typemr;
      $requestMaterial->jobId = $request->job;
      $requestMaterial->qty = $request->quantity;
      $requestMaterial->bomId = $request->bomlist;
      $requestMaterial->status = '1';
      $requestMaterial->createdBy = $user;
      if($requestMaterial->save()){
        foreach ($request->fep_rows as $key => $value) { 
         $materialDetail = new MaterialRequestDetail;
         $materialDetail->mrId = $requestMaterial->id;
         $materialDetail->productId = $value['ids'];
         $materialDetail->qty = $requestMaterial->quantity;
         $materialDetail->createdBy = $user;
         $materialDetail->status = '1';
         if($materialDetail->save()){
          return response()->json([
            'status' => 200,
            'data' => 'success'
          ], 200);
         }else{
          return response()->json([
            'status' => 202,
            'data' => 'somerthing error'
          ], 200);
         }
        }
        
       }else{
        return response()->json([
            'status' => 203,
            'data' => 'somerthing error'
          ], 200);
       }

     }

  //--Get Mr Data by passing id
  public function mrapproveData(Request $request){
    $validator = Validator::make($request->all(), [ 
            'searchMr' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
   //--Get Material request data from material-request table   
   $mrObj = MaterialRequest::where('id', $request->searchMr)->with('materialDetail.materialRequestItem')->first(); 
   if($mrObj){    
   return response()->json([
    'status' => 200,
    'data' => [$mrObj]
   ], 200);
   }else{
        return response()->json([
        'status' => 201,
        'data' => 'Data not found'
       ], 200);
   }

  } 

//--- Submit MR Approve Form Data 
public function submitMrApprove(Request $request){
  $validator = Validator::make($request->all(), [ 
            'searchMr' => 'required', 
            'bomList' => 'required',
            'fep_row' => 'required',
            'mrDate' => 'required'  
        ]);
    if ($validator->fails()) { 
      return response()->json(['error'=>$validator->errors()], 401);
    }
  $materialrequest = MaterialRequest::where('id', $request->searchMr)->where('bomId', $request->bomList)->where('mrDate', $request->mrDate)->first();  
  
  if($materialrequest){
    foreach ($request->fep_row as $rowindex => $rows) {
      if($rows['ids']){
        $user =  Auth::user()->id;
    $materialrequest->approveStatus = '1';
    $materialrequest->approveBy = $user;
    $materialrequest->approveDate = date('Y-m-d H:i:s');
    $materialrequest->save();
    return response()->json([
    'status' => 200,
    'data' => 'success'
  ], 200);
      }
    }
    
  }else{
    return response()->json([
    'status' => 201,
    'data' => 'something wrong!'
  ], 200);
  }
  

}  


   //---end of class  
      }

