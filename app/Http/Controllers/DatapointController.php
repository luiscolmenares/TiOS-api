<?php
namespace App\Http\Controllers;

use App\Datapoint;
use App\Datasource;
use App\Project;
use App\Organization;
use App\Space;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DatapointController extends Controller
{
/**
* @SWG\Get(
*      path="/datapoints",
*      operationId="getDatapoints",
*      tags={"Datapoints"},
*      summary="Get list of datapoints",
*      description="Returns list of datapoints",
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
public function getDatapoints(){
    $datapoints = Datapoint::all();
    $datapoints = array("datapoints" => $datapoints);
    return $datapoints;
}

/**
* @SWG\Get(
*      path="/datapoint/{id}",
*      operationId="getDatapoint",
*      tags={"Datapoints"},
*      summary="Get datapoint information",
*      description="Returns datapoint data",
*      @SWG\Parameter(
*          name="id",
*          description="datapoint id",
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

public function getDatapoint($datapointId){
    $datapoint = Datapoint::find($datapointId);
    $datapoint = array("datapoint" => $datapoint);
    return $datapoint;
}


public function createDatapoint(Request $request)
{
    $datapoint = new Datapoint($request->all());
    if (!$datapoint->save()) {
        abort(500, 'Could not save datapoint.');
    }
    return $datapoint;
}
/**
* @SWG\Get(
*      path="/datapoints/types",
*      operationId="getDatapointTypes",
*      tags={"Datapoints"},
*      summary="Get list of datapoints types",
*      description="Returns list of datapoint types",
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

public function getDatapointTypes(){
    $types = \DB::table('datapoint_type')->select('id', 'name', 'codename')->get();
    $datapointtypes = array('datapointtypes' => $types);
    return $datapointtypes;
} 



public function getDatapointsByName($datapointName){
    $datapoints = Datapoint::where('name', $datapointName)->get();
    $labels = [];
    $data =[];
    foreach ($datapoints as $datapoint) {
//$datepoint = array('date'=>$datapoint->created_at);
        array_push($labels, array('date'=>$datapoint->created_at,'data'=>json_decode($datapoint->data)));
//array_push($data, json_decode($datapoint->data));
    }
    $content = array('datapoints' => $labels);

    return $content;
}

/**
* @SWG\Get(
*      path="/datapoint/datasource/{datasource_id}",
*      operationId="getDataPointByDataSource",
*      tags={"Datapoints"},
*      summary="Get datapoints information by Datasource",
*      description="Returns datapoints information",
*      @SWG\Parameter(
*          name="datasource_id",
*          description="Datasource id",
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

public function getDataPointByDataSource($datasourceId){
    $datapoints = Datapoint::where('datasource_id', $datasourceId)->get();
    $datapoints = array("datapoints" => $datapoints);
    return $datapoints;
}
public function getActiveDataPointByDataSource($datasourceId){
    $datapoints = Datapoint::where('datasource_id', $datasourceId)
    ->where('active', 1)->get();
    $datapoints = array("datapoints" => $datapoints);
    return $datapoints;
}
/**
* Deletes an datapoint
* @param datapointId
* return boolean
*/
public function deleteDatapoint($datapointId) {
    return Datapoint::destroy($datapointId);
}

/**
* Updates datapoint
* @param Request request, datapointId
* return datapoint
*/
public function updateDatapoint(Request $request, $datapoint_id) {
    $datapoint = Datapoint::find($datapoint_id);
    if ($request->name) {$datapoint->name = $request->name;}
    if ($request->type) {$datapoint->type = $request->type;}
    if ($request->unitid) {$datapoint->unitid = $request->unitid;}
    if ($request->address) {$datapoint->address = $request->address;}
    if ($request->options) {$datapoint->options = $request->options;}
    if ($request->data) {$datapoint->data = $request->data;}  
    if ($request->active == 0) {
        $datapoint->active = 0;
    }
    if ($request->active == 1) {
        $datapoint->active = 1;
    }
    if ($request->notes) {$datapoint->notes = $request->notes;}
    if ($request->datasource_id) {$datapoint->datasource_id = $request->datasource_id;}

    if (!$datapoint->save()) {
        abort(500, 'Could not update datapoint.');
    }
    return $datapoint;
}

/**
* Datapoint values by daterange
* @param Request request, datapointId
* return datapoint
*/
public function getDatapointValuesByDateRange($datapoint_id, $from_date, $to_date) {
    $datapoint = Datapoint::find($datapoint_id);
    $datasource = $this->GetDatasourceByDatapointId($datapoint_id);
    $project = $this->GetProjectByDatapointId($datapoint_id);
    $organization = $this->GetOrganizationByDatapointId($datapoint_id);

    $triggersnotifications = app('App\Http\Controllers\TriggerController')->getTriggersNotificationsByDatapointId($datapoint_id, $from_date, $to_date);
    $toptriggersnotifications = app('App\Http\Controllers\TriggerController')->getTopTriggersNotificationsByDatapointId($datapoint_id, $from_date, $to_date);
    $sensordata = \DB::table('sensordata')
    ->select('created_at', 'created_at as date_created', 'data', 'type', 'unitid', 'address', 'ip', 'port')
    ->where([
        ['type', '=', $datapoint->type],
        ['unitid', '=', $datapoint->unitid],
        ['address', '=', $datapoint->address],
        ['ip', '=', $datasource->ip],
        ['port', '=', $datasource->port],
    ])
    ->orderBy('created_at', 'desc')
    ->get()
    ->reverse();
    $sum = 0;
    $count = 0;
    $totalsum = 0;
    $totalcount = 0;  
    $totalmaxvalue = 0;
    $totalminvalue=0;
    foreach ($sensordata as $datapointvalue) {
        $sum = $sum + $datapointvalue->data;
        $totalsum = $totalsum + $datapointvalue->data;
        $count = $count + 1;
        $totalcount = $totalcount + 1;
        if ($totalmaxvalue < $datapointvalue->data){
            $totalmaxvalue = $datapointvalue->data;
        }
        if ($totalminvalue > $datapointvalue->data){
            $totalminvalue = $datapointvalue->data;
        }
        $datapointvalue->_blank = "";
        $datapointvalue->date_created = strtotime($datapointvalue->created_at);

    }
    if(($from_date > 0) && ($to_date > 0)){
        $datapointvaluelist = [];
        $filtered_sum = 0;
        $filtered_count = 0;
        $filtered_maxvalue = 0;
        $filtered_minvalue=0;
        foreach ($sensordata as $datapointvalue) {
            if(($datapointvalue->date_created > $from_date) && ($datapointvalue->date_created < $to_date)){
                array_push($datapointvaluelist, $datapointvalue);
                $filtered_sum = $filtered_sum + $datapointvalue->data;
                $filtered_count = $filtered_count + 1;
                if ($filtered_maxvalue < $datapointvalue->data){
                    $filtered_maxvalue = $datapointvalue->data;
                }
                if ($filtered_minvalue > $datapointvalue->data){
                    $filtered_minvalue = $datapointvalue->data;
                }
            }
        }

        $datapointvaluelist = array(
            'datasource_name' => $datasource->name,
            'project_name' => $project->name,
            'organization_name' => $organization->name,
            'sum' => $filtered_sum,
            'count' => $filtered_count,
            'totalcount' => $totalcount,
            'average' => ($filtered_sum/$filtered_count),
            'totalaverage' => ($totalsum/$totalcount),
            'highest' => $filtered_maxvalue,
            'lowest' => $filtered_minvalue,
            'totalhighest' => $totalmaxvalue,
            'totallowest' => $totalminvalue,
            'toptriggersnotifications' => $toptriggersnotifications,
            'triggersnotifications' => $triggersnotifications,
            'sensordata' => $datapointvaluelist,
        );

        return $datapointvaluelist;
    } else {

        $sensordata = array(
            'datasource_name' => $datasource->name,
            'project_name' => $project->name,
            'organization_name' => $organization->name,
            'sum' => $sum,
            'count' => $count,
            'totalcount' => $totalcount,
            'average' => ($sum/$count),
            'totalaverage' => ($totalsum/$totalcount),
            'highest' => $totalmaxvalue,
            'lowest' => $totalminvalue,
            'totalhighest' => $totalmaxvalue,
            'totallowest' => $totalminvalue,
            'toptriggersnotifications' => $toptriggersnotifications,
            'triggersnotifications' => $triggersnotifications,
            'sensordata' => $sensordata
        );

        return $sensordata;

    }


}


/**
* Datasource  by datapoint id
* @param datapointId
* return datapoint
*/
public function GetDatasourceByDatapointId($datapointId){
    $datapoint = Datapoint::find($datapointId);
    $datasource = Datasource::find($datapoint->datasource_id);

    return $datasource;
}

/**
* @SWG\Get(
*      path="/space/{space_id}/datapoints",
*      operationId="GetDatapointsBySpaceId",
*      tags={"Datapoints"},
*      summary="Get datapoint information related to space",
*      description="Returns datapoint data related to space",
*      @SWG\Parameter(
*          name="space_id",
*          description="space id",
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
public function GetDatapointsBySpaceId($spaceId){
    $datapoints = Datapoint::where('space_id', '=', $spaceId)->get();
    // $datasource = Datasource::find($datapoint->datasource_id);

    return $datapoints;
}

/**
* Project  by datapoint id
* @param datapointId
* return Project
*/
public function GetProjectByDatapointId($datapointId){
    $datapoint = Datapoint::find($datapointId);
    $datasource = Datasource::find($datapoint->datasource_id);
    $project = Project::find($datasource->project_id);

    return $project;
}

/**
* Organization  by datapoint id
* @param datapointId
* return Organization
*/
public function GetOrganizationByDatapointId($datapointId){
    $datapoint = Datapoint::find($datapointId);
    $datasource = Datasource::find($datapoint->datasource_id);
    $project = Project::find($datasource->project_id);
    $organization = Organization::find($project->organization_id);

    return $organization;
}

}
