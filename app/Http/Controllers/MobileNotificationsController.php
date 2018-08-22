<?php

namespace App\Http\Controllers;

use App\MobileNotification;
use App\User;
use App\Role;
use App\Organization;
use App\Project;
use App\Datasource;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Http\Request;

class MobileNotificationsController extends Controller
{
/**
* Get all mobile notifications
* @param 
* return users
*/
public function index(){
    return MobileNotification::withTrashed()->get();
}

/**
    * @SWG\Get(
    *      path="/mobile/notifications",
    *      operationId="getMobileNotifications",
    *      tags={"Mobile Notifications"},
    *      summary="Get list of Mobile Notifications",
    *      description="Returns list of users",
    *      @SWG\Response(
    *          response=200,
    *          description="Mobile Notification"
    *       ),
    *       @SWG\Response(response=400, description="Bad request"),
    *       security={
    *           {"passport": {}}
    *       }
    *     )
    *
    * Returns list of dashboards
    */
public function getMobileNotifications(){
    //$users =  User::withTrashed()->get();
    $mobilenotifications =  MobileNotification::all();
    return $mobilenotifications;

}

/**
* Get User from User Id
* @param userId
* return User
*/
public function getMobileNotificationsByProjectId($project_id){
    //$users =  User::withTrashed()->get();
    $mobilenotifications =  MobileNotification::where('project_id', $project_id)->get();
    return $mobilenotifications;

}

/**
* Create mobile notification
* @param Request request
* return mobile notification
*/
public function createMobileNotification(Request $request)
{
    $mn = new MobileNotification($request->all());
    $mn->project_id = $this->GetProjectIdByTopic($request->topic);
    if (!$mn->save()) {
        abort(500, 'Could not save Mobile Notification.');
    }
    return $mn;

	// $datasource = Datasource::where('options', 'like', '%'.$request->topic.'%')->get();
 //    return $datasource[0]->project_id;

    }

public function GetProjectIdByTopic($topic){
	$datasource = Datasource::where('options', 'like', '%'.$topic.'%')->get();
    return $datasource[0]->project_id;
}


}
