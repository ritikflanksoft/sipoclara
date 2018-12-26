<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('forgetpassword', 'UserController@forgetpassword');

Route::group(['middleware' => 'auth:api'], function(){
Route::get('request-detail', 'BomRequestController@getRequestDetail');
Route::post('bom-request', 'BomRequestController@createRequest');
Route::post('submit-bom-request', 'BomRequestController@submitBomRequest');
Route::get('job-request', 'JobController@createJobRequest');
Route::post('submit-form-request', 'JobController@jobFormSubmit');
Route::get('job-approval', 'JobController@jobApproval');
Route::post('job-approvel-form', 'JobController@jobApprovalForm');
Route::get('items', 'ItemController@getItems');
Route::post('submit-purchase-request', 'ItemController@submitPurchaseRequest');
Route::get('getDataForStock', 'StockController@getDataForStock');
Route::post('submit-booking-stock', 'StockController@createBookingStock');
Route::post('create-mps-data', 'MpsController@createMpsData');
Route::post('job-fep-data', 'MpsController@getFeptblData');

/*
|------scrap related url
*/
Route::get('scrap-related-data', 'ScrapController@getScrapRelatedData');
Route::post('entry-scrap-material', 'ScrapController@entryScrapMaterial');
Route::post('get-scrap-data', 'ScrapController@getScrapData');
Route::post('approve-scrap-material', 'ScrapController@approveScrapMaterial');
/*
|------material request 
*/
Route::get('material-request-data', 'MaterialRequestController@materialRequestData');
Route::get('material-id-data', 'MaterialRequestController@materialIdData');
Route::post('get-bom-data', 'MaterialRequestController@getBomData');
Route::post('submit-material-request', 'MaterialRequestController@submitmaterialRequest');
Route::post('mr-approve-data', 'MaterialRequestController@mrapproveData');
Route::post('submit-mr-approve-data', 'MaterialRequestController@submitMrApprove');

});