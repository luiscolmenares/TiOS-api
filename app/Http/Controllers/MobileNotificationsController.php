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
* @SWG\Get(
*      path="/project/{project_id}/mobile/notifications",
*      operationId="getMobileNotificationsByProjectId",
*      tags={"Mobile Notifications"},
*      summary="Get Mobile Notifications by Project",
*      description="Returns Mobile Notifications",
*      @SWG\Parameter(
*          name="project_id",
*          description="project id",
*          required=true,
*          type="integer",
*          in="path"
*      ),
*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*      @SWG\Response(response=400, description="Bad request"),
*      @SWG\Response(response=404, description="Resource Not Found"),
*      security={
*           {"passport": {}}
*       },
* )
*
*/ 
public function getMobileNotificationsByProjectId($project_id){
    $mobilenotifications =  MobileNotification::where('project_id', $project_id)->get();
    return $mobilenotifications;

}

/**
*
* @SWG\Post(
*      path="/mobile/notification/create",
*      tags={"Mobile Notifications"},
*      operationId="createMobileNotification",
*      summary="Create Mobile Notification",
*      @SWG\Parameter(
*          name="body",
*          in="body",
*          description="JSON Payload",
*          required=true,
*          type="json",
*          format="application/json",
*          @SWG\Schema(
*              type="object",
*              @SWG\Property(property="name", description="name", type="string", example="Lights"),
*              @SWG\Property(property="space", description="space", type="string", example="Bedroom"),
*              @SWG\Property(property="topic", description="topic", type="string", example="tios000001/s00002/sw001"),
*              @SWG\Property(property="value", type="string", example="ON"),
*              @SWG\Property(property="project_id", type="integer", example="1"),
*              @SWG\Property(property="timestamp", type="string", example="1534913055338"),
*       
*          )
*
*      ),

*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @SWG\Response(response=400, description="Bad request"),
*       security={
*           {"passport": {}}
*       }
*  )
*
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
