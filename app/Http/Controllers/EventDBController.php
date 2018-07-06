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
*              @SWG\Property(property="event_id", description="event id", type="integer", example="1512581640"),
*              @SWG\Property(property="title", type="string", example="Send email"),
*              @SWG\Property(property="action", type="string", example="[{'action':'send-email','recipients':'[6]','message':'custom message','triggered':0}]"),
*              @SWG\Property(property="valueFrom", type="string", example="1512581640"),
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
    $events = \DB::table('events')
    ->where('events.organization_id', '=', $organization_id)
    ->select('events.*')
    ->get();
    $events = array('events' => $events);
    return $events;
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
