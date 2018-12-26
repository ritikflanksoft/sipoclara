<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use App\Model\BomList;
use App\Model\Job;

class JobController extends Controller
{
    
    public function createJobRequest(Request $Request){
    	$userData = User::where('status', '1')->get();
    	$bomList = BomList::where('status', '1')->with('projectList')->get();
    	
    	return response()->json([
    		'status' => '200',
    		'data' => [
    			'userData' => $userData,
    			'bomList' => $bomList,
    		]
    	], 200);
    }

   public function jobFormSubmit(Request $request){  
     $validator = Validator::make($request->all(), [ 
            'assignTO' => 'required', 
            'bomList' => 'required', 
            'customer' => 'required', 
            'deadLine' => 'required', 
            'jobDate' => 'required', 
            'jobName' => 'required', 
            'jobTargetDate' => 'required', 
            'jobType' => 'required',
            'po' => 'required', 
            'priority' => 'required', 
            'qty' => 'required', 
            'referencedBy' => 'required', 
            'requestedBy' => 'required',
            'um' => 'required',
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $jobObj = new Job;
        $jobObj->jobName = $request->jobName;
        $jobObj->jobDate = $request->jobDate;
        $jobObj->customerId = $request->customer;
        $jobObj->poStatus = $request->po;
        $jobObj->assignTo = $request->assignTO;
        $jobObj->bomId = $request->bomList;
        $jobObj->quantiyProduction = $request->qty;
        $jobObj->jobTarget = $request->jobTargetDate;
        $jobObj->jobType = $request->jobType;
        $jobObj->requestedBy = $request->requestedBy;
        $jobObj->fep = $request->fepName;
        $jobObj->priority = $request->priority;
        $jobObj->reffBy = $request->referencedBy;
        $jobObj->status = "1";
        $jobObj->createdBy = Auth::user()->id;
        $jobObj->save();
        return response()->json([
            'status' => 200,
            'data' => 'success'
        ], 200);
   } 

   public function jobApproval(Request $request){
        $userData = User::where('status', '1')->get();
        $jobData = Job::where('status', '1')->get();

        return response()->json([
            'status' => '200',
            'data' => [
                'userData' => $userData,
                'jobData' => $jobData,
            ]
        ], 200);
   }

   //-- submit job approval form data
   public function jobApprovalForm(Request $request){
         $validator = Validator::make($request->all(), [ 
            'jobName' => 'required', 
            'approveDate' => 'required', 
            'approvedBy' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $jobObj =  Job::where('id', $request->jobName)->first();
        if($jobObj){
            $jobObj->approvedBy = $request->approvedBy;
            $jobObj->approveDate = $request->approveDate;
            $jobObj->approveStatus = '1';
            if($jobObj->save()){
                return response()->json([
                'status' => 200,
                'data' => 'success'
                 ], 200); 
            }
        }else{
            return response()->json([
                'status' => 401,
                'data' => 'Job Not Found.'
            ], 200);
        }
       
   }

   //---end class here 
}
