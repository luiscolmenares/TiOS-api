<?php
namespace App\Http\Controllers;
use App\Trigger;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TriggerController extends Controller
{
/**
* @SWG\Get(
*      path="/triggers",
*      operationId="getTriggers",
*      tags={"Triggers"},
*      summary="Get all triggers",
*      description="Returns triggers",
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
public function getTriggers(){
	$triggers = Trigger::orderBy('id', 'desc')->paginate(10);
    $triggers_list = array();
    $url = url('/');
    $pagination = array(
        'count' => $triggers->count(),
        'currentPage' => $triggers->currentPage(),
        'firstItem' => $triggers->firstItem(),
        'hasMorePages' => $triggers->hasMorePages(),
        'lastItem' => $triggers->lastItem(),
        'lastPage' => $triggers->lastPage(), //(Not available when using simplePaginate)
        'nextPageUrl' => $triggers->nextPageUrl(),
        'onFirstPage' => $triggers->onFirstPage(),
        'perPage' => $triggers->perPage(),
        'previousPageUrl' => $triggers->previousPageUrl(),
        'total' => $triggers->total(), //(Not available when using simplePaginate)
        // 'url' => $triggers->url($page)

    );
    $pagination = array('pagination' => $pagination);
    array_push($triggers_list, $pagination);
    foreach ($triggers as $trigger) {

        $datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->datasource_id);
        $project = app('App\Http\Controllers\ProjectController')->getProject($trigger->project_id);
        if ($trigger->act_datasource_id != null){
            $act_datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->act_datasource_id);
        } else {
            $act_datasource = array();
        }
        
         // $space = app('App\Http\Controllers\SpaceController')->getSpace($trigger->space_id);
        
        $complete_trigger = array(
            'id' => $trigger->id,
            'name' => $trigger->name,
            'operator' => $trigger->operator,
            'value' => $trigger->value,
            'trigger_action_type_id' => $trigger->trigger_action_type_id,
            'project_id' => $trigger->project_id,
            'datasource_id' => $trigger->datasource_id,
            'datapoint_id' => $trigger->datapoint_id,
            'active' => $trigger->active,
            'custommessage' => $trigger->custommessage,
            'created_at' => $trigger->created_at,
            'updated_at' => $trigger->updated_at,
            'created_at_timestamp' => strtotime($trigger->created_at),
            'recipients' => $trigger->recipients,
            'notes' => $trigger->notes,
            'act_datasource_id' => $trigger->act_datasource_id,
            'act_datapoint_id' => $trigger->act_datapoint_id,
            'act_new_value' => $trigger->act_new_value,
            'datasource' => $datasource,
            'act_datasource' => $act_datasource,
            'project' => $project,
            
            );

        array_push($triggers_list, $complete_trigger);
    }
    $triggers = array('triggers' => $triggers_list);
    return $triggers;
}

/**
* @SWG\Get(
*      path="/nopagination/triggers",
*      operationId="getTriggersNoPagination",
*      tags={"Triggers"},
*      summary="Get all triggers",
*      description="Returns triggers",
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
public function getTriggersNoPagination(){
    $triggers = Trigger::all();
    $triggers_list = array();
    $url = url('/');
    
    foreach ($triggers as $trigger) {

        $datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->datasource_id);
        $project = app('App\Http\Controllers\ProjectController')->getProject($trigger->project_id);
        $organization = app('App\Http\Controllers\ProjectController')->getOrganizationByProjectId($trigger->project_id);
        if ($trigger->act_datasource_id != null){
            $act_datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->act_datasource_id);
        } else {
            $act_datasource = array();
        }
        $description = $this->getTriggerTypeById($trigger->trigger_action_type_id);
         // $space = app('App\Http\Controllers\SpaceController')->getSpace($trigger->space_id);
        
        $complete_trigger = array(
            'id' => $trigger->id,
            'name' => $trigger->name,
            'operator' => $trigger->operator,
            'value' => $trigger->value,
            'trigger_action_type_id' => $trigger->trigger_action_type_id,
            'project_id' => $trigger->project_id,
            'datasource_id' => $trigger->datasource_id,
            'datapoint_id' => $trigger->datapoint_id,
            'active' => $trigger->active,
            'custommessage' => $trigger->custommessage,
            'created_at' => $trigger->created_at,
            'updated_at' => $trigger->updated_at,
            'created_at_timestamp' => strtotime($trigger->created_at),
            'recipients' => $trigger->recipients,
            'notes' => $trigger->notes,
            'act_datasource_id' => $trigger->act_datasource_id,
            'act_datapoint_id' => $trigger->act_datapoint_id,
            'act_new_value' => $trigger->act_new_value,
            'datasource' => $datasource,
            'act_datasource' => $act_datasource,
            'project' => $project,
            'organization_name' => $organization['organization']->name,
            'project_name' => $project['project']->name,
            'description' => $description,
            
            );

        array_push($triggers_list, $complete_trigger);
    }
    $triggers = array('triggers' => $triggers_list);
    return $triggers;
}


/**
* @SWG\Get(
*      path="/trigger/{id}",
*      operationId="getTriggerById",
*      tags={"Triggers"},
*      summary="Get trigger information by ID",
*      description="Returns trigger data",
*      @SWG\Parameter(
*          name="id",
*          description="Trigger id",
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
public function getTriggerById($triggerId){
	//return Trigger::find($triggerId);
    $trigger = \DB::table('triggers')
            ->where('triggers.id', '=', $triggerId)
            ->join('trigger_action_types', 'triggers.trigger_action_type_id', '=', 'trigger_action_types.id')
            ->join('datasources', 'triggers.datasource_id', '=', 'datasources.id')
            ->join('datapoints', 'triggers.datapoint_id', '=', 'datapoints.id')
            ->join('operators', 'triggers.operator', '=', 'operators.value')
            ->select('triggers.*', 'trigger_action_types.description', 'trigger_action_types.name as action_name', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name', 'operators.name as operator_name')
            ->get();
    $trigger = array('trigger' => $trigger);
    return $trigger;
}

/**
* Get Trigger Type by Id
* @param 
* return triggertype
*/
public function getTriggerTypeById($triggertypeId){
    $triggertype = \DB::table('trigger_action_types')->where('id', '=', $triggertypeId)->first();
    return $triggertype->description;
}

 /**
* @SWG\Get(
*      path="/project/{project_id}/trigger/count",
*      operationId="getProjectTriggersCount",
*      tags={"Triggers"},
*      summary="Get trigger count by project",
*      description="Returns trigger count data by project",
*      @SWG\Parameter(
*          name="project_id",
*          description="Project id",
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
    public function getProjectTriggersCount($projectId) {
        $triggerscount = \DB::table('triggers')
                    ->where('deleted_at', '=', null)
                    ->where('project_id', '=', $projectId)
                    ->count();
        return $triggerscount;
    }


/**
*
* @SWG\Post(
*      path="/trigger/create",
*      tags={"Triggers"},
*      operationId="createTrigger",
*      summary="Create new trigger entry",
*      @SWG\Parameter(
*          name="body",
*          in="body",
*          description="JSON Payload",
*          required=true,
*          type="json",
*          format="application/json",
*          @SWG\Schema(
*              type="object",
*              @SWG\Property(property="name", type="string", example="Turn ON AC when room temp reach 30C"),
*              @SWG\Property(property="operator", type="string", example=">"),
*              @SWG\Property(property="value", type="string", example="30"),
*              @SWG\Property(property="trigger_action_type_id", type="integer", example="5"),
*              @SWG\Property(property="project_id", type="integer", example="1"),
*              @SWG\Property(property="datasource_id", type="integer", example="1"),
*              @SWG\Property(property="datapoint_id", type="integer", example="1"),
*              @SWG\Property(property="active", type="integer", example="1"),
*              @SWG\Property(property="custommessage", type="string", example="custom message"),
*              @SWG\Property(property="act_datasource_id", type="integer", example="23"),
*              @SWG\Property(property="act_new_value", type="string", example="0"),
*          )
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
public function createTrigger(Request $request)
{
    
	$trigger = new Trigger($request->all());
    if($trigger->act_datasource_id == null){
        $trigger->act_datasource_id = $request->datasource_id;
    }

	if (!$trigger->save()) {
		abort(500, 'Could not save trigger.');
	}
	return $trigger;
}

/**
* @SWG\Get(
*      path="/project/{project_id}/triggers",
*      operationId="getTriggersByProjectId",
*      tags={"Triggers"},
*      summary="Get triggers by project",
*      description="Returns triggers data by project",
*      @SWG\Parameter(
*          name="project_id",
*          description="Project id",
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
public function getTriggersByProjectId($projectId){
	// $triggers = \DB::table('triggers')
 //                    ->where('project_id', '=', $projectId)
 //                    ->join('trigger_action_types', 'triggers.trigger_action_type_id', '=', 'trigger_action_types.id')
 //                    ->select('triggers.*', 'trigger_action_types.description')
 //                    ->get()->toArray();
 //    $triggers = array('triggers' => $triggers);
 //    return $triggers;
    $triggers = Trigger::where('project_id', $projectId)->orderBy('id', 'desc')->paginate(10);
    // return $triggers;
    $triggers_list = array();
    $url = url('/');
    $pagination = array(
        'count' => $triggers->count(),
        'currentPage' => $triggers->currentPage(),
        'firstItem' => $triggers->firstItem(),
        'hasMorePages' => $triggers->hasMorePages(),
        'lastItem' => $triggers->lastItem(),
        'lastPage' => $triggers->lastPage(), //(Not available when using simplePaginate)
        'nextPageUrl' => $triggers->nextPageUrl(),
        'onFirstPage' => $triggers->onFirstPage(),
        'perPage' => $triggers->perPage(),
        'previousPageUrl' => $triggers->previousPageUrl(),
        'total' => $triggers->total(), //(Not available when using simplePaginate)
        // 'url' => $triggers->url($page)

    );
    $pagination = array('pagination' => $pagination);
    array_push($triggers_list, $pagination);
    foreach ($triggers as $trigger) {

        $datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->datasource_id);
        $project = app('App\Http\Controllers\ProjectController')->getProject($trigger->project_id);
        if($trigger->act_datasource_id){
            $act_datasource = app('App\Http\Controllers\DatasourceController')->getDatasource($trigger->act_datasource_id);
        } else {
            $act_datasource = null;
        }
        


         // $space = app('App\Http\Controllers\SpaceController')->getSpace($trigger->space_id);
        
        $complete_trigger = array(
            'id' => $trigger->id,
            'name' => $trigger->name,
            'operator' => $trigger->operator,
            'value' => $trigger->value,
            'trigger_action_type_id' => $trigger->trigger_action_type_id,
            'project_id' => $trigger->project_id,
            'datasource_id' => $trigger->datasource_id,
            'datapoint_id' => $trigger->datapoint_id,
            'active' => $trigger->active,
            'created_at' => $trigger->created_at,
            'updated_at' => $trigger->updated_at,
            'created_at_timestamp' => strtotime($trigger->created_at),
            'custommessage' => $trigger->custommessage,
            'recipients' => $trigger->recipients,
            'notes' => $trigger->notes,
            'act_datasource_id' => $trigger->act_datasource_id,
            'act_datapoint_id' => $trigger->act_datapoint_id,
            'act_new_value' => $trigger->act_new_value,
            'datasource' => $datasource,
            'act_datasource' => $act_datasource,
            'project' => $project,
            
            );

        array_push($triggers_list, $complete_trigger);
}
    $triggers = array('triggers' => $triggers_list);
    return $triggers;
}

/**
* Get Triggers by DatapointId
* @param datapointId
* return triggers
*/
public function getTriggersByDatapointId($datapointId){
    $triggers = \DB::table('triggers')
                    ->where('triggers.deleted_at', '=', null)
                    ->where('datapoint_id', '=', $datapointId)
                    ->join('trigger_action_types', 'triggers.trigger_action_type_id', '=', 'trigger_action_types.id')
                    ->join('projects', 'triggers.project_id', '=', 'projects.id')
                    ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
                    ->select('triggers.*', 'trigger_action_types.description', 'projects.name as project_name', 'organizations.name as organization_name', 'organizations.id as organization_id')
                    ->get()->toArray();
    $triggers = array('triggers' => $triggers);
    return $triggers;
}

/**
* Get Triggers by DatapointId
* @param datapointId
* return triggers
*/
// public function getTriggersByDatapointId($datapointId){
// 	$triggers = \DB::table('triggers')->where('datapoint_id', '=', $datapointId)->get();
// 	return $triggers;
// }

/**
* Get Triggers by DatasourceId
* @param datasourceId
* return triggers
*/
public function getTriggersByDatasourceId($datasourceId){
	$triggers = \DB::table('triggers')->where('datasource_id', '=', $datasourceId)->get();
	return $triggers;
}

/**
* Delete Trigger from trigger Id
* @param triggerId
* return boolean
*/
public function deleteTrigger($triggerId){

    //$trigger = Trigger::find($triggerId);

    //$trigger->delete();

    $trigger = Trigger::destroy($triggerId);

}

/**
* Delete Trigger Notification from trigger notification Id
* @param triggerId
* return boolean
*/
public function deleteTriggerNotification($triggernotificationId){

    \DB::table('triggers_notifications')
        ->where('id', '=', $triggernotificationId)
        ->delete();

}

/**
* Updates a trigger
* @param Request $request, triggerId
* return project
*/
public function updateTrigger(Request $request, $triggerId){
    $trigger = Trigger::find($triggerId);
    if($request->name){$trigger->name = $request->name;}
    if($request->operator){$trigger->operator = $request->operator;}
    if($request->value){$trigger->value = $request->value;}
    if($request->trigger_action_type_id){$trigger->trigger_action_type_id = $request->trigger_action_type_id;}
    if($request->project_id){$trigger->project_id = $request->project_id;}
    if($request->datasource_id){$trigger->datasource_id = $request->datasource_id;}
    if($request->datapoint_id){$trigger->datapoint_id = $request->datapoint_id;}
    if($request->notes){$trigger->notes = $request->notes;}
    if($request->active == 0){$trigger->active = 0;}
    if($request->active == 1){$trigger->active = 1;}

    if (!$trigger->save()) {
        abort(500, 'Could not update trigger.');
    }
    return $trigger;
}

/**
* @SWG\Get(
*      path="/trigger/types",
*      operationId="getTriggerTypes",
*      tags={"Triggers"},
*      summary="Get triggers action types",
*      description="Returns triggers action types",
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
public function getTriggerTypes(){
        $types = \DB::table('trigger_action_types')->select('id', 'name', 'description')->get();
        $trigger_action_types = array('trigger_action_types' => $types);
        //return $trigger_action_types;
        return $trigger_action_types;
    } 

    /**
* Get Operators
* @param Request $request, triggerId
* return project
*/   
public function getTriggerOperators(){
        $operators = \DB::table('operators')->select('id', 'name', 'value')->get();
        $operators = array('operators' => $operators);
        return $operators;
    } 

/**
* Get trigger total count
* @param 
* return count int
*/
public function getTotalTriggersCount(){
    return Trigger::all()->count();
}

//Notifications
/**
* Get trigger total count
* @param 
* return count int
*/
public function getTriggersNotifications(){
    $notifications = \DB::table('triggers_notifications')
            ->join('trigger_action_types', 'triggers_notifications.trigger_action_type_id', '=', 'trigger_action_types.id')
            ->join('projects', 'triggers_notifications.project_id', '=', 'projects.id')
            ->join('organizations', 'triggers_notifications.organization_id', '=', 'organizations.id')
            ->join('datasources', 'triggers_notifications.datasource_id', '=', 'datasources.id')
            ->join('datapoints', 'triggers_notifications.datapoint_id', '=', 'datapoints.id')
            ->select('triggers_notifications.id', 'triggers_notifications.trigger_action_type_id', 'trigger_action_types.name as trigger_action_types_name', 'triggers_notifications.organization_id', 'organizations.name as organization_name', 'triggers_notifications.project_id', 'projects.name as project_name', 'triggers_notifications.datasource_id', 'triggers_notifications.datapoint_id', 'triggers_notifications.message', 'triggers_notifications.recipients', 'triggers_notifications.viewed', 'triggers_notifications.created_at', 'triggers_notifications.updated_at', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name')
            ->get();
        $notifications = array('notifications' => $notifications);
        foreach ($notifications as $notification) {
            # code...
            foreach ($notification as $notif) {
                # code...
                $recipients = $notif->recipients;
                $recipients = ltrim($recipients, '[');
                $recipients = ltrim($recipients, ']');
                $recipients_array = explode(',', $recipients);
                foreach ($recipients_array as $recipient_id) {
                //$user = User::find($recipient_id);
                $notif->users = array();
                array_push($notif->users, $recipient_id);
            }
            }
        }
        return $notifications;
}

/**
* Get trigger Notificagtion by org Id
* @param 
* return notifications
*/
public function getTriggersNotificationsByOrgId($organizationId){
    $notifications = \DB::table('triggers_notifications')
            ->where('triggers_notifications.organization_id', '=', $organizationId)
            ->join('trigger_action_types', 'triggers_notifications.trigger_action_type_id', '=', 'trigger_action_types.id')
            ->join('projects', 'triggers_notifications.project_id', '=', 'projects.id')
            ->join('organizations', 'triggers_notifications.organization_id', '=', 'organizations.id')
            ->join('datasources', 'triggers_notifications.datasource_id', '=', 'datasources.id')
            ->join('datapoints', 'triggers_notifications.datapoint_id', '=', 'datapoints.id')
            ->select('triggers_notifications.id', 'triggers_notifications.trigger_action_type_id', 'trigger_action_types.name as trigger_action_types_name', 'triggers_notifications.organization_id', 'organizations.name as organization_name', 'triggers_notifications.project_id', 'projects.name as project_name', 'triggers_notifications.datasource_id', 'triggers_notifications.datapoint_id', 'triggers_notifications.message', 'triggers_notifications.recipients', 'triggers_notifications.viewed', 'triggers_notifications.created_at', 'triggers_notifications.updated_at', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name')
            ->get();
        $notifications = array('notifications' => $notifications);
        foreach ($notifications as $notification) {
            # code...
            foreach ($notification as $notif) {
                # code...
                $recipients = $notif->recipients;
                $recipients = ltrim($recipients, '[');
                $recipients = ltrim($recipients, ']');
                $recipients_array = explode(',', $recipients);
                foreach ($recipients_array as $recipient_id) {
                //$user = User::find($recipient_id);
                $notif->users = array();
                array_push($notif->users, $recipient_id);
            }
            }
        }
        return $notifications;
}

/**
* Get trigger Notification by datapoint Id
* @param 
* return notifications
*/
public function getTriggersNotificationsByDatapointId($datapointId, $fromdate, $todate){
    $notifications = \DB::table('triggers_notifications')
            ->where('triggers_notifications.datapoint_id', '=', $datapointId)
            ->join('trigger_action_types', 'triggers_notifications.trigger_action_type_id', '=', 'trigger_action_types.id')
            ->join('projects', 'triggers_notifications.project_id', '=', 'projects.id')
            ->join('organizations', 'triggers_notifications.organization_id', '=', 'organizations.id')
            ->join('datasources', 'triggers_notifications.datasource_id', '=', 'datasources.id')
            ->join('datapoints', 'triggers_notifications.datapoint_id', '=', 'datapoints.id')
            ->select('triggers_notifications.id', 'triggers_notifications.trigger_action_type_id', 'trigger_action_types.name as trigger_action_types_name', 'triggers_notifications.organization_id', 'organizations.name as organization_name', 'triggers_notifications.project_id', 'projects.name as project_name', 'triggers_notifications.datasource_id', 'triggers_notifications.datapoint_id', 'triggers_notifications.message', 'triggers_notifications.recipients', 'triggers_notifications.viewed', 'triggers_notifications.created_at', 'triggers_notifications.updated_at', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name')
            ->get();

    //total counts
            $total_count = 0;
            $email_count = 0;
            $sms_count = 0;
            $push_count = 0;
            $off_count = 0;
            $on_count = 0;
    //convert to timesta created_at values
            foreach ($notifications as $notification) {
                $total_count = $total_count + 1;
                if ($notification->trigger_action_types_name == 'send-email'){
                    $email_count = $email_count + 1;
                 }
                 if ($notification->trigger_action_types_name == 'send-sms-message'){
                   $sms_count = $sms_count + 1;
                    }
                if ($notification->trigger_action_types_name == 'system-notification'){
                   $push_count = $push_count + 1;
                    }
                 if ($notification->trigger_action_types_name == 'turn-off'){
                   $off_count = $off_count + 1;
                    }
                if ($notification->trigger_action_types_name == 'turn-on'){
                   $on_count = $on_count + 1;
                    }
                 $notification->date_created = strtotime($notification->created_at);
            }
    //If daterange is provided
            if(($fromdate > 0) && ($todate >0)){
                $notificationslist = [];
                $filtered_count = 0;
                $filtered_email_count = 0;
                $filtered_sms_count = 0;
                $filtered_push_count = 0;
                $filtered_on_count = 0;
                $filtered_off_count = 0;
                    foreach ($notifications as $notification) {
                        if(($notification->date_created > $fromdate) && ($notification->date_created < $todate)){
                        array_push($notificationslist, $notification);
                        $filtered_count = $filtered_count + 1;
                         if ($notification->trigger_action_types_name == 'send-email'){
                            $filtered_email_count = $filtered_email_count + 1;
                         }
                         if ($notification->trigger_action_types_name == 'send-sms-message'){
                           $filtered_sms_count = $filtered_sms_count + 1;
                            }
                        if ($notification->trigger_action_types_name == 'system-notification'){
                           $filtered_push_count = $filtered_push_count + 1;
                            }
                         if ($notification->trigger_action_types_name == 'turn-off'){
                           $filtered_off_count = $filtered_off_count + 1;
                            }
                        if ($notification->trigger_action_types_name == 'turn-on'){
                           $filtered_on_count = $filtered_on_count + 1;
                            }
                        //Create returned array
                        }
                        
                    }

                    $notificationslist = array(
                            'count' => $filtered_count,
                            'email_count' => $filtered_email_count,
                            'sms_count' => $filtered_sms_count,
                            'push_count' => $filtered_push_count,
                            'on_count' => $filtered_on_count,
                            'off_count' => $filtered_off_count,
                            'notifications' => $notificationslist,
                        ); 
                    return $notificationslist;
                    
            } else {
                $notifications = array(
                    'count' => $total_count,
                    'email_count' => $email_count,
                    'sms_count' => $sms_count,
                    'push_count' => $push_count,
                    'on_count' => $on_count,
                    'off_count' => $off_count,
                    'notifications' => $notifications,
                );

                return $notifications;
               
            }

        return $notifications;
}

/**
* Get trigger Notification by datasource Id
* @param 
* return notifications
*/
public function getTriggersNotificationsByDatasourceId($datasourceId, $fromdate, $todate){
    $notifications = \DB::table('triggers_notifications')
            ->where('triggers_notifications.datasource_id', '=', $datasourceId)
            ->join('trigger_action_types', 'triggers_notifications.trigger_action_type_id', '=', 'trigger_action_types.id')
            ->join('projects', 'triggers_notifications.project_id', '=', 'projects.id')
            // ->join('organizations', 'triggers_notifications.organization_id', '=', 'organizations.id')
            ->join('datasources', 'triggers_notifications.datasource_id', '=', 'datasources.id')
            ->join('datapoints', 'triggers_notifications.datapoint_id', '=', 'datapoints.id')
            // ->select('triggers_notifications.id', 'triggers_notifications.trigger_action_type_id', 'trigger_action_types.name as trigger_action_types_name', 'triggers_notifications.organization_id', 'organizations.name as organization_name', 'triggers_notifications.project_id', 'projects.name as project_name', 'triggers_notifications.datasource_id', 'triggers_notifications.datapoint_id', 'triggers_notifications.message', 'triggers_notifications.recipients', 'triggers_notifications.viewed', 'triggers_notifications.created_at', 'triggers_notifications.updated_at', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name')
            ->select('triggers_notifications.id', 'triggers_notifications.trigger_action_type_id', 'trigger_action_types.name as trigger_action_types_name', 'triggers_notifications.project_id', 'projects.name as project_name', 'triggers_notifications.datasource_id', 'triggers_notifications.datapoint_id', 'triggers_notifications.message', 'triggers_notifications.recipients', 'triggers_notifications.viewed', 'triggers_notifications.created_at', 'triggers_notifications.updated_at', 'datasources.name as datasource_name', 'datapoints.name as datapoint_name')
            ->get();

    //total counts
            $total_count = 0;
            $email_count = 0;
            $sms_count = 0;
            $push_count = 0;
            $off_count = 0;
            $on_count = 0;
    //convert to timesta created_at values
            foreach ($notifications as $notification) {
                $total_count = $total_count + 1;
                if ($notification->trigger_action_types_name == 'send-email'){
                    $email_count = $email_count + 1;
                 }
                 if ($notification->trigger_action_types_name == 'send-sms-message'){
                   $sms_count = $sms_count + 1;
                    }
                if ($notification->trigger_action_types_name == 'system-notification'){
                   $push_count = $push_count + 1;
                    }
                 if ($notification->trigger_action_types_name == 'turn-off'){
                   $off_count = $off_count + 1;
                    }
                if ($notification->trigger_action_types_name == 'turn-on'){
                   $on_count = $on_count + 1;
                    }
                 $notification->date_created = strtotime($notification->created_at);
            }
    //If daterange is provided
            if(($fromdate > 0) && ($todate >0)){
                $notificationslist = [];
                $filtered_count = 0;
                $filtered_email_count = 0;
                $filtered_sms_count = 0;
                $filtered_push_count = 0;
                $filtered_on_count = 0;
                $filtered_off_count = 0;
                    foreach ($notifications as $notification) {
                        if(($notification->date_created > $fromdate) && ($notification->date_created < $todate)){
                        array_push($notificationslist, $notification);
                        $filtered_count = $filtered_count + 1;
                         if ($notification->trigger_action_types_name == 'send-email'){
                            $filtered_email_count = $filtered_email_count + 1;
                         }
                         if ($notification->trigger_action_types_name == 'send-sms-message'){
                           $filtered_sms_count = $filtered_sms_count + 1;
                            }
                        if ($notification->trigger_action_types_name == 'system-notification'){
                           $filtered_push_count = $filtered_push_count + 1;
                            }
                         if ($notification->trigger_action_types_name == 'turn-off'){
                           $filtered_off_count = $filtered_off_count + 1;
                            }
                        if ($notification->trigger_action_types_name == 'turn-on'){
                           $filtered_on_count = $filtered_on_count + 1;
                            }
                        //Create returned array
                        }
                        
                    }

                    $notificationslist = array(
                            'count' => $filtered_count,
                            'email_count' => $filtered_email_count,
                            'sms_count' => $filtered_sms_count,
                            'push_count' => $filtered_push_count,
                            'on_count' => $filtered_on_count,
                            'off_count' => $filtered_off_count,
                            'notifications' => $notificationslist,
                        ); 
                    return $notificationslist;
                    
            } else {
                $notifications = array(
                    'count' => $total_count,
                    'email_count' => $email_count,
                    'sms_count' => $sms_count,
                    'push_count' => $push_count,
                    'on_count' => $on_count,
                    'off_count' => $off_count,
                    'notifications' => $notifications,
                );

                return $notifications;
               
            }

        return $notifications;
}

/**
* Get trigger Notification by datapoint Id
* @param 
* return notifications
*/
public function getTopTriggersNotificationsByDatapointId($datapointId, $fromdate, $todate){
    
    $topnotifications = \DB::table('triggers_notifications')
            //->select(\DB::raw('triggers_notifications.id AS notifications_count, triggers_notifications.message, triggers_notifications.created_at'))
            ->where('triggers_notifications.datapoint_id', '=', $datapointId)
            //if(($fromdate > 0) && ($todate > 0)){
            //    $fdate = date("YYYY-10-22 H:i:s", $fromdate);
            //    $tdate = date("YYYY-10-22 H:i:s", $todate);
            //    ->where('triggers_notifications.created_at', '<', $todate)
            //    ->where('triggers_notifications.created_at', '>', $fromdate)
            //}
            //->groupBy('triggers_notifications.message')
            ->get();

    //convert to timestap created_at values
            foreach ($topnotifications as $notification) {
                // $total_count = $total_count + 1;
                // if ($notification->trigger_action_types_name == 'send-email'){
                //     $email_count = $email_count + 1;
                //  }
                //  if ($notification->trigger_action_types_name == 'send-sms-message'){
                //    $sms_count = $sms_count + 1;
                //     }
                // if ($notification->trigger_action_types_name == 'system-notification'){
                //    $push_count = $push_count + 1;
                //     }
                //  if ($notification->trigger_action_types_name == 'turn-off'){
                //    $off_count = $off_count + 1;
                //     }
                // if ($notification->trigger_action_types_name == 'turn-on'){
                //    $on_count = $on_count + 1;
                //     }
                 $notification->date_created = strtotime($notification->created_at);
            }
    //If daterange is provided
            if(($fromdate > 0) && ($todate >0)){
                 $notificationslist = [];
                 $nlist = [];
                // $filtered_count = 0;
                // $filtered_email_count = 0;
                // $filtered_sms_count = 0;
                // $filtered_push_count = 0;
                // $filtered_on_count = 0;
                // $filtered_off_count = 0;
                    foreach ($topnotifications as $notification) {
                        if(($notification->date_created > $fromdate) && ($notification->date_created < $todate)){
                        // $n = array(
                        //     "message" => $notification->message,
                        // );
                        array_push($notificationslist, $notification->message."~".$notification->trigger_action_type_id);
                        //$filtered_count = $filtered_count + 1;
                        //  if ($notification->trigger_action_types_name == 'send-email'){
                        //     $filtered_email_count = $filtered_email_count + 1;
                        //  }
                        //  if ($notification->trigger_action_types_name == 'send-sms-message'){
                        //    $filtered_sms_count = $filtered_sms_count + 1;
                        //     }
                        // if ($notification->trigger_action_types_name == 'system-notification'){
                        //    $filtered_push_count = $filtered_push_count + 1;
                        //     }
                        //  if ($notification->trigger_action_types_name == 'turn-off'){
                        //    $filtered_off_count = $filtered_off_count + 1;
                        //     }
                        // if ($notification->trigger_action_types_name == 'turn-on'){
                        //    $filtered_on_count = $filtered_on_count + 1;
                        //     }
                        //Create returned array
                        }
                        
                    }

                    // $notificationslist = array(
                    //         "message" => 
                    //         // 'count' => $filtered_count,
                    //         // 'email_count' => $filtered_email_count,
                    //         // 'sms_count' => $filtered_sms_count,
                    //         // 'push_count' => $filtered_push_count,
                    //         // 'on_count' => $filtered_on_count,
                    //         // 'off_count' => $filtered_off_count,
                    //         // 'notifications' => $notificationslist,
                    //     ); 
                    $notificationslist = array_count_values($notificationslist);
                   foreach ($notificationslist as $key => $value) {
                    $n = array(
                        "trigger_action_type_id" => substr($key, strpos($key, "~") + 1),
                        "message" => substr($key, 0, strpos($key, "~")),
                        "count" => $value
                    );
                    array_push($nlist, $n);

                }
                return $nlist;
                    
            } else {
                 $notificationslist = [];
                 $nlist = [];
                // $notifications = array(
                //     'count' => $total_count,
                //     'email_count' => $email_count,
                //     'sms_count' => $sms_count,
                //     'push_count' => $push_count,
                //     'on_count' => $on_count,
                //     'off_count' => $off_count,
                //     'notifications' => $notifications,
                // );
                //$topnotifications = array_count_values($topnotifications);
                foreach ($topnotifications as $notification) {
                        // $n = array(
                        //     "message" => $notification->message,
                        // );
                        array_push($notificationslist, $notification->message."~".$notification->trigger_action_type_id);
                    }

                $notificationslist = array_count_values($notificationslist);
                foreach ($notificationslist as $key => $value) {
                    $n = array(
                        "trigger_action_type_id" => substr($key, strpos($key, "~") + 1),
                        "message" => substr($key, 0, strpos($key, "~")),
                        "count" => $value
                    );
                    array_push($nlist, $n);

                }
                return $nlist;
               
            }

        return false;
}

/**
* Get trigger Notification by datapoint Id
* @param 
* return notifications
*/
public function getTopTriggersNotificationsByDatasourceId($datasourceId, $fromdate, $todate){
    
    $topnotifications = \DB::table('triggers_notifications')
            //->select(\DB::raw('triggers_notifications.id AS notifications_count, triggers_notifications.message, triggers_notifications.created_at'))
            ->where('triggers_notifications.datasource_id', '=', $datasourceId)
            //if(($fromdate > 0) && ($todate > 0)){
            //    $fdate = date("YYYY-10-22 H:i:s", $fromdate);
            //    $tdate = date("YYYY-10-22 H:i:s", $todate);
            //    ->where('triggers_notifications.created_at', '<', $todate)
            //    ->where('triggers_notifications.created_at', '>', $fromdate)
            //}
            //->groupBy('triggers_notifications.message')
            ->get();

    //convert to timestap created_at values
            foreach ($topnotifications as $notification) {
                // $total_count = $total_count + 1;
                // if ($notification->trigger_action_types_name == 'send-email'){
                //     $email_count = $email_count + 1;
                //  }
                //  if ($notification->trigger_action_types_name == 'send-sms-message'){
                //    $sms_count = $sms_count + 1;
                //     }
                // if ($notification->trigger_action_types_name == 'system-notification'){
                //    $push_count = $push_count + 1;
                //     }
                //  if ($notification->trigger_action_types_name == 'turn-off'){
                //    $off_count = $off_count + 1;
                //     }
                // if ($notification->trigger_action_types_name == 'turn-on'){
                //    $on_count = $on_count + 1;
                //     }
                 $notification->date_created = strtotime($notification->created_at);
            }
    //If daterange is provided
            if(($fromdate > 0) && ($todate >0)){
                 $notificationslist = [];
                 $nlist = [];
                // $filtered_count = 0;
                // $filtered_email_count = 0;
                // $filtered_sms_count = 0;
                // $filtered_push_count = 0;
                // $filtered_on_count = 0;
                // $filtered_off_count = 0;
                    foreach ($topnotifications as $notification) {
                        if(($notification->date_created > $fromdate) && ($notification->date_created < $todate)){
                        // $n = array(
                        //     "message" => $notification->message,
                        // );
                        array_push($notificationslist, $notification->message."~".$notification->trigger_action_type_id);
                        //$filtered_count = $filtered_count + 1;
                        //  if ($notification->trigger_action_types_name == 'send-email'){
                        //     $filtered_email_count = $filtered_email_count + 1;
                        //  }
                        //  if ($notification->trigger_action_types_name == 'send-sms-message'){
                        //    $filtered_sms_count = $filtered_sms_count + 1;
                        //     }
                        // if ($notification->trigger_action_types_name == 'system-notification'){
                        //    $filtered_push_count = $filtered_push_count + 1;
                        //     }
                        //  if ($notification->trigger_action_types_name == 'turn-off'){
                        //    $filtered_off_count = $filtered_off_count + 1;
                        //     }
                        // if ($notification->trigger_action_types_name == 'turn-on'){
                        //    $filtered_on_count = $filtered_on_count + 1;
                        //     }
                        //Create returned array
                        }
                        
                    }

                    // $notificationslist = array(
                    //         "message" => 
                    //         // 'count' => $filtered_count,
                    //         // 'email_count' => $filtered_email_count,
                    //         // 'sms_count' => $filtered_sms_count,
                    //         // 'push_count' => $filtered_push_count,
                    //         // 'on_count' => $filtered_on_count,
                    //         // 'off_count' => $filtered_off_count,
                    //         // 'notifications' => $notificationslist,
                    //     ); 
                    $notificationslist = array_count_values($notificationslist);
                   foreach ($notificationslist as $key => $value) {
                    $n = array(
                        "trigger_action_type_id" => substr($key, strpos($key, "~") + 1),
                        "message" => substr($key, 0, strpos($key, "~")),
                        "count" => $value
                    );
                    array_push($nlist, $n);

                }
                return $nlist;
                    
            } else {
                 $notificationslist = [];
                 $nlist = [];
                // $notifications = array(
                //     'count' => $total_count,
                //     'email_count' => $email_count,
                //     'sms_count' => $sms_count,
                //     'push_count' => $push_count,
                //     'on_count' => $on_count,
                //     'off_count' => $off_count,
                //     'notifications' => $notifications,
                // );
                //$topnotifications = array_count_values($topnotifications);
                foreach ($topnotifications as $notification) {
                        // $n = array(
                        //     "message" => $notification->message,
                        // );
                        array_push($notificationslist, $notification->message."~".$notification->trigger_action_type_id);
                    }

                $notificationslist = array_count_values($notificationslist);
                foreach ($notificationslist as $key => $value) {
                    $n = array(
                        "trigger_action_type_id" => substr($key, strpos($key, "~") + 1),
                        "message" => substr($key, 0, strpos($key, "~")),
                        "count" => $value
                    );
                    array_push($nlist, $n);

                }
                return $nlist;
               
            }

        return false;
}

    
}