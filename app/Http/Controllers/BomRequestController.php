<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Project;
use App\User;
use Validator;
use Auth;
use App\Model\BomList;

class BomRequestController extends Controller
{
    //-getting detail related bom request
	public function getRequestDetail(Request $request){
		$projectData = Project::where('status', '1')->get();
		$userData = User::where('status', '1')->get();

		return response()->json([
			'status' => 200,
			'data' => [
				'projectData' => $projectData,
				'userData' => $userData,
			]
		], 200);
	}

	//- creating bom request
    public function submitBomRequest(Request $request){
    	 $user = Auth::user();
    	 $v = Validator::make($request->all(), [
        'project' => 'required',
        'deadline' => 'required',
        'requestFromId' => 'required',
        'assignToId' => 'required',
		]);
    	 //--condition for validation fields
    	  if ($v->fails())
		    {
		        return response()->json([
		        	'status' => 401,
		        	'data' => $v->errors(),
		        ], 200);
		    }

		$bomListObj = new BomList;
		$bomListObj->projectId = $request->project; 
		$bomListObj->deadline = $request->deadline['year'].'-'.$request->deadline['month'].'-'.$request->deadline['day'];
		$bomListObj->requestFrom = $request->requestFromId;
		$bomListObj->assignTo = $request->assignToId;
		$bomListObj->priority = $request->priority;
		$bomListObj->status = '1';
		$bomListObj->createdDate = date('Y-m-d H:i:s');
		$bomListObj->createdBy = $user->id;
		if($bomListObj->save()){
			return response()->json([
				'status' => 200,
				'data' => 'success',
			], 200);
		}  
    }


    //---End class here
}
