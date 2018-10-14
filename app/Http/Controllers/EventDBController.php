<?php

namespace App\Http\Controllers;

use App\EventDB;
use Illuminate\Http\Request;

class EventDBController extends Controller {

/**
*
* @SWG\Post(
*      path="/event/create",
*      tags={"Events"},
*      operationId="createEventDB",
*      summary="Create new event entry",
*      @SWG\Parameter(
*          name="body",
*          in="body",
*          description="JSON Payload",
*          required=true,
*          type="json",
*          format="application/json",
*          @SWG\Schema(
*              type="object",
*              @SWG\Property(property="title", type="string", example="Turn ON my device"),
*              @SWG\Property(property="description", type="string", example="{'ocurrence':'none','count':'none','repeats':'none','every':'none','valueFrom':1535419680}"),
*              @SWG\Property(property="action", type="string", example="[{'action':'turn-on','triggered':'0'}]"),
*              @SWG\Property(property="allday", type="boolean", example="0"),
*              @SWG\Property(property="color", type="string", example="#fac5a5"),
*              @SWG\Property(property="organization_id", type="integer", example="1"),
*              @SWG\Property(property="project_id", type="integer", example="1"),
*              @SWG\Property(property="datasource_id", type="integer", example="1"),
*              @SWG\Property(property="datapoint_id", type="integer", example="1"),
*              @SWG\Property(property="active", type="integer", example="1"),
*              @SWG\Property(property="end", type="string", example="end"),

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
public function createEventDB(Request $request) {

    $ocurrence = json_decode($request->description, true)['ocurrence'];
    $count = json_decode($request->description, true)['count'];
    $repeats = json_decode($request->description, true)['repeats'];
    $every = json_decode($request->description, true)['every'];
    $valuefrom = json_decode($request->description, true)['valueFrom'];
    switch ($repeats) {
        case 'none':

        $event = new EventDB($request->all()); 
        $event->valueFrom = $valuefrom;       
        if (!$event->save()) {
            abort(500, 'Could not save event.');
        }

        break;

        case 'daily':

        for ($i=1; $i <= $count; $i++) { 
            $event = new EventDB($request->all()); 
            $event->valueFrom = $valuefrom;       
            if (!$event->save()) {
                abort(500, 'Could not save event.');
            }
            $valuefrom = (int)$valuefrom + 86400;
//$event->valueFrom = (int)$event->valueFrom + 86400;
        }

        case 'weekly':       
        for ($i=1; $i <= $count; $i++) { 
            $event = new EventDB($request->all()); 
            $event->valueFrom = $valuefrom;       
            if (!$event->save()) {
                abort(500, 'Could not save event.');
            }
            $valuefrom = (int)$valuefrom + 86400 * 7;
//$event->valueFrom = (int)$event->valueFrom + 86400;
        }

        case 'monthly':

        for ($i=1; $i <= $count; $i++) { 
            $event = new EventDB($request->all()); 
            $event->valueFrom = $valuefrom;       
            if (!$event->save()) {
                abort(500, 'Could not save event.');
            }
            $valuefrom = (int)$valuefrom + 86400 * 30;
//$event->valueFrom = (int)$event->valueFrom + 86400;
        }

        case 'yearly':

        for ($i=1; $i <= $count; $i++) { 
            $event = new EventDB($request->all()); 
            $event->valueFrom = $valuefrom;       
            if (!$event->save()) {
                abort(500, 'Could not save event.');
            }
            $valuefrom = (int)$valuefrom + 86400 * 365;
//$event->valueFrom = (int)$event->valueFrom + 86400;
        }

        break;         
        default:
# code...
        break;
    }

    return $event;
}   

/**
* Deletes an event
* @param eventId
* return boolean
*/
public function deleteEventDB($eventId) {

    return EventDB::destroy($eventId);
}

/**
* @SWG\Get(
*      path="/events",
*      operationId="getEventsDB",
*      tags={"Events"},
*      summary="Get list of events",
*      description="Returns list of events",
*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @SWG\Response(response=400, description="Bad request"),
*       security={
*           {"passport": {}}
*       }
*     )
*
* Returns list of dashboards
*/
public function getEventsDB() {
    $events = EventDB::all();
//  $events = \DB::table('events')
// ->select('events.id', 'events.title', 'events.description', 'events.action', 'events.valueFrom', 'events.organization_id', 'events.project_id', 'events.datasource_id', 'events.datapoint_id', 'events.active', 'events.created_at')
// ->where([
//         ['active', '>', 0],
//         //['valueFrom', '>', $now_timestamp],
//         ])
//     ->get();
    $events = array('events' => $events);
    return $events;
}

/**
* Get org total count
* @param 
* return count int
*/
public function getTotalEventsDBCount(){
    return EventDB::all()->count();
}

/**
* @SWG\Get(
*      path="/event/{id}",
*      operationId="getEventDB",
*      tags={"Events"},
*      summary="Get event information",
*      description="Returns event data",
*      @SWG\Parameter(
*          name="id",
*          description="event id",
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
public function getEventDB($eventId) {
// $event = EventDB::find($eventId);
// $event = array("event" => $event);
    $event = \DB::table('events')
    ->where('events.id', '=', $eventId)
    ->join('organizations', 'organizations.id', '=','events.organization_id')
    ->join('projects', 'projects.id', '=','events.project_id')
    ->select('events.*', 'organizations.name as organization_name', 'projects.name as project_name')
//->select('events.*')
    ->get();
    $event = array('event' => $event);
    return $event;
}


/**
* @SWG\Get(
*      path="/events/organization/{organization_id}",
*      operationId="getEventsDBByOrgId",
*      tags={"Events"},
*      summary="Get event information By Organization",
*      description="Returns event data by organization",
*      @SWG\Parameter(
*          name="organization_id",
*          description="organization id",
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
public function getEventsDBByOrgId($organization_id) {
    // $events = \DB::table('events')
    // ->where('events.organization_id', '=', $organization_id)
    // ->select('events.*')
    // ->get();
    // $events = array('events' => $events);
    // return $events;
    $events =  EventDB::where('organization_id', $organization_id)->paginate(10);
    $events_list = array();
    $pagination = array(
        'count' => $events->count(),
        'currentPage' => $events->currentPage(),
        'firstItem' => $events->firstItem(),
        'hasMorePages' => $events->hasMorePages(),
        'lastItem' => $events->lastItem(),
        'lastPage' => $events->lastPage(), //(Not available when using simplePaginate)
        'nextPageUrl' => $events->nextPageUrl(),
        'onFirstPage' => $events->onFirstPage(),
        'perPage' => $events->perPage(),
        'previousPageUrl' => $events->previousPageUrl(),
        'total' => $events->total(), //(Not available when using simplePaginate)
        // 'url' => $mobilenotifications->url($page)

    );

    $pagination = array('pagination' => $pagination);
    array_push($events_list, $pagination);

    foreach ($events as $event) {
       
         array_push($events_list, $event);
     }

    // $pagination = array('pagination' => $pagination);
    // array_push($events, $pagination);
    $events_list = array('events' => $events_list);
    return $events_list;
}

/**
* @SWG\Get(
*      path="/events/project/{project_id}",
*      operationId="getEventsDBByProjectId",
*      tags={"Events"},
*      summary="Get event information By Project",
*      description="Returns event data by project",
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
public function getEventsDBByProjectId($project_id) {
    $events =  EventDB::where('project_id', $project_id)->paginate(10);
    $events_list = array();
    // $events = \DB::table('events')
    // ->where('events.project_id', '=', $project_id)
    // ->select('events.*')
    // ->get();
    $pagination = array(
        'count' => $events->count(),
        'currentPage' => $events->currentPage(),
        'firstItem' => $events->firstItem(),
        'hasMorePages' => $events->hasMorePages(),
        'lastItem' => $events->lastItem(),
        'lastPage' => $events->lastPage(), //(Not available when using simplePaginate)
        'nextPageUrl' => $events->nextPageUrl(),
        'onFirstPage' => $events->onFirstPage(),
        'perPage' => $events->perPage(),
        'previousPageUrl' => $events->previousPageUrl(),
        'total' => $events->total(), //(Not available when using simplePaginate)
        // 'url' => $mobilenotifications->url($page)

    );

    $pagination = array('pagination' => $pagination);
    array_push($events_list, $pagination);

    foreach ($events as $event) {
       
         array_push($events_list, $event);
     }

    // $pagination = array('pagination' => $pagination);
    // array_push($events, $pagination);
    $events_list = array('events' => $events_list);
    return $events_list;
}

/**
* @SWG\Get(
*      path="/events/actions",
*      operationId="getEventsDBActions",
*      tags={"Events"},
*      summary="Get list of events actions",
*      description="Returns list of events actions",
*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @SWG\Response(response=400, description="Bad request"),
*       security={
*           {"passport": {}}
*       }
*     )
*
* Returns list of dashboards
*/ 
public function getEventsDBActions(){
    $types = \DB::table('event_action_types')->select('id', 'name', 'description')->get();
    $event_action_types = array('event_action_types' => $types);
    return $event_action_types;
} 



}
