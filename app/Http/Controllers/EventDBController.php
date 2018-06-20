<?php

namespace App\Http\Controllers;

use App\EventDB;
use Illuminate\Http\Request;

class EventDBController extends Controller {

    /**
     * Creates an event
     * @param Request $request
     * return event
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
     * Gets all events
     * @param 
     * return events
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
     * Gets an event by Id
     * @param eventId
     * return event
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
     * Gets an event by Org Id
     * @param eventId
     * return event
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
* Get Event action types
* return event action types
*/   
public function getEventsDBActions(){
        $types = \DB::table('event_action_types')->select('id', 'name', 'description')->get();
        $event_action_types = array('event_action_types' => $types);
        return $event_action_types;
    } 

   
  
}
