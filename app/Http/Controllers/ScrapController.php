<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Scrap;
use App\Model\Job;
use App\User;
use Validator;
use Auth;

class ScrapController extends Controller
{
    
    public function getScrapRelatedData(Request $request){

    	$jobObj = Job::where('status', '1')->get();
    	$userObj = User::where('status', '1')->get();

    	return response()->json([
    		'status' => 200,
    		'data' => ['userData' => $userObj,
    		'jobData' => $jobObj,
    	]
    	], 200);
    }

    public function entryScrapMaterial(Request $request){
    	$user = Auth::user();
    	$validator = Validator::make($request->all(), [ 
            'jobName' => 'required', 
            'approveDate' => 'required', 
            'process' => 'required', 
            'weightedBy' => 'required', 
            'scrapmaterial' => 'required', 
            'scrapweight' => 'required',
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);  
        }

        $scrap = new Scrap;
        $scrap->jobId = $request->jobName;
        $scrap->processId = $request->process;
        $scrap->weightedBy = $request->weightedBy;
        $scrap->scrapWeight = $request->scrapweight;
        $scrap->scrapDate = $request->approveDate;
        $scrap->materialType = $request->scrapmaterial;
        $scrap->status = '1';
        $scrap->createdBy = $user->id;
        if($scrap->save()){
        	return response()->json([
        		'status' => 200,
        		'data' => 'success'
        	], 200);
        }


    }

    public function getScrapData(Request $request){
        $validator = Validator::make($request->all(), [ 
            'jobName' => 'required', 
            'approveDate' => 'required', 
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);  
        }

     $scrapObj = Scrap::where('jobId', $request->jobName)->where('scrapDate', $request->approveDate)->where('processId', $request->process)->where('status', '1')->first();
     if($scrapObj){
        return response()->json([
            'status' => 200,
            'data' => $scrapObj
        ], 200);
     }else{
        return response()->json([
            'status' => 401,
            'data' => 'Something Wrong!'
        ], 200);
     }

    }

   public function approveScrapMaterial(Request $request){
     $user = Auth::user();
        $validator = Validator::make($request->all(), [ 
            'jobName' => 'required', 
            'approveDate' => 'required', 
            'process' => 'required', 
            'weightedBy' => 'required', 
            'scrapmaterial' => 'required', 
            'scrapweight' => 'required',
            'hiddenId' => 'required',
        ]);
    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);  
        }

        $scrapObj = Scrap::where('status', '1')->where('id', $request->hiddenId)->first();
        if($scrapObj){
            $scrapObj->approvedBy = $user->id;
            $scrapObj->approvedDate = date('Y-m-d');
            if($scrapObj->save()){
                return response()->json([
                    'status' => 200,
                    'data' => 'success'
                ], 200);
            }
        }

   } 

  //--- End class here   
}
