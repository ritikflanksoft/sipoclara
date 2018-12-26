<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use App\Model\MpsDetail;
use App\Model\MpsDetailData;
use App\Model\Job;

class MpsController extends Controller
{
    
    //---submit data for MPS detail
    public function createMpsData(Request $request){
    	$user = Auth::user();
    	//--validation for server side
    	 $validator = Validator::make($request->all(), [ 
            'jmlhari' => 'required', 
            'manpower' => 'required', 
            'shifttime' => 'required', 
            'processgroup' => 'required', 
            'jobname' => 'required', 
            'startdate' => 'required', 
        ]);

    if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 201);            
        }
        
     $mpsObj = new MpsDetail;  
     //$mpsObj->nn = $request->jmlhari;
     $mpsObj->manPower = $request->manpower;
     $mpsObj->shift = $request->shifttime;
     $mpsObj->productionDate = $request->startdate;
     $mpsObj->status = '1';
     $mpsObj->createdBy = $user->id;
     $mpsObj->jobId = $request->jobname;
     $mpsObj->mpsDate = $request->startdate;
     if($mpsObj->save()){
        foreach ($request->tbl_data as $key => $value) {
            if($value['ids']){
     	$mpsDetailObj = new MpsDetailData;
     	$mpsDetailObj->mpsId = $mpsObj->id;
     	$mpsDetailObj->manpower = $request->manpower;
     	$mpsDetailObj->shift = $request->shifttime;
     	//$mpsDetailObj->processId = $request->processgroup;
     	$mpsDetailObj->productionDate = $request->startdate;
     	$mpsDetailObj->status = '1';
     	$mpsDetailObj->createdBy = $user->id;
     	$mpsDetailObj->save();
     }
     }
     	return response()->json([
    		'status' => 200,
    		'data' => 'success'
    	], 200);
     }else{
     	return response()->json([
     		'status' => 401,
    		'data' => 'Something Wrong!'
    	], 200);
     }
    	
    }

    /**
     * Get FEP detail according job production table
      */
    public function getFeptblData(Request $request){
    	$jobObj = Job::where('status', '1')->with('fepRecord')->get();

    	return response()->json([
    		'status' => 200,
    		'data' => $jobObj
    	], 200);
    }

  //-------End class here
}
