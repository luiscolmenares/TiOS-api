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
use Validator;
use Illuminate\Routing\UrlGenerator;
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
    $mobilenotifications_list = array();
    foreach ($mobilenotifications as $mobilenotification) {
        // $datasource = Datasource::all();
        $datasource = $this->GetDatasourceByTopic($mobilenotification->topic);
        $datasourcetype = $this->GetDatasourceTypeByTypeName($datasource[0]['type']);
        // $datasource = $this->GetDatasourceByTopic('tios000001/s00002/sw001');
        $mn = array(
        'id'=> $mobilenotification->id ,
        'name'=> $mobilenotification->name,
        'space'=> $mobilenotification->space,
        'topic'=> $mobilenotification->topic,
        'value'=> $mobilenotification->value,
        'project_id'=> $mobilenotification->project_id,
        'timestamp'=> $mobilenotification->timestamp,
        'created_at'=> $mobilenotification->created_at,
        'updated_at'=> $mobilenotification->updated_at,
        'datasource' => $datasource,
        'datasourcetype' => $datasourcetype
    );
         array_push($mobilenotifications_list, $mn);


    }
    // $datasources = Datasource::where('space_id', '=', $space_id)->get();
    return $mobilenotifications_list;

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

public function GetDatasourceByTopic($topic){
    $datasource = Datasource::where('options', 'like', '%'.$topic.'%')->get();
    return $datasource;
}

public function GetDatasourceTypeByTypeName($type){
    $url = url('/');
    $datasourcetype = \DB::table('datasource_type')
                        ->where('name', '=', $type)
                        ->select('id', 'name', 'codename', 'icon_image')->get();
    $dstype = array(
        'id' => $datasourcetype[0]->id,
        'name' => $datasourcetype[0]->name,
        'codename' => $datasourcetype[0]->codename,
        'icon_image' => $datasourcetype[0]->icon_image,
        'icon_image_on_url' => $url.'/datasources/icons/'.$datasourcetype[0]->icon_image.'_ON.png', 
        'icon_image_off_url' => $url.'/datasources/icons/'.$datasourcetype[0]->icon_image.'_OFF.png'
    );
    // $datasourcetype = \DB::table('datasource_type')->select('id', 'name', 'codename', 'icon_image')->get();
    return $dstype; 
}


}
