<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;


class UserController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success, "status" => 200], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised', "status" => 401], 200); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
return response()->json(['success'=>$success, "status" => 200], $this-> successStatus); 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    //sending mail for forgot password
    public function forgetpassword(Request $request){

        $emailObj = User::where('email', $request->email)->first();

        if($emailObj){
        $name = 'Ritik';
        $email = 'hietavinash@gmail.com';
        Mail::to($email)->send(new SendMailable($name));
        return response()->json([
            'status' => 200,
            'data' => 'success',
        ], 200);
       }else{
            return response()->json([
            'status' => 401,
            'data' => 'Email not found.Please try again!',
             ], 200);
        }
      }

 

 // end class here    
}
