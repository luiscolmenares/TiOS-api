<?php

namespace App\Http\Controllers;

use App\Project;
use App\Datapoint;
use App\Datasource;
use App\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Routing\UrlGenerator;

class DatasourceController extends Controller {

/**
* @SWG\Get(
*      path="/datasources",
*      operationId="getDatasources",
*      tags={"Datasources"},
*      summary="Get list of datasources",
*      description="Returns list of datasources",
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
* Returns list of datasources
*/
public function getDatasources() {
    return Datasource::all();
}

/**
* @SWG\Get(
*      path="/datasource/{id}",
*      operationId="getDatasource",
*      tags={"Datasources"},
*      summary="Get datasource information",
*      description="Returns datasource data",
*      @SWG\Parameter(
*          name="id",
*          description="datasource id",
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

public function getDatasource($DatasourceId) {
    $datasource = Datasource::find($DatasourceId);
    if ($datasource->space_id > 0){
        $space = Space::find($datasource->space_id);
        $datasource_space_id = $space->id;
        $datasource_space_name =  $space->name;

    } else {
        $datasource_space_id = 0;
        $datasource_space_name =  null;

    }
    
    $options_array = json_decode($datasource->options, true);
    $complete_datasource = array(
    'id' => $datasource->id,
    'name' => $datasource->name,
    'type' => $datasource->type,
    'unitid' => $datasource->image,
    'ip' => $datasource->ip,
    'port' => $datasource->port,
    'options' => $datasource->options,
    'options_array' => $options_array,
    'data' => $datasource->data,
    'notes' => $datasource->notes,
    'active' => $datasource->active,
    'project_id' => $datasource->project_id,
    'created_at' => $datasource->created_at,
    'updated_at' => $datasource->updated_at,
    'deleted_at' => $datasource->deleted_at,
    'space_id' => $datasource_space_id,
    'space_name' => $datasource_space_name,
    'type_codename' => $datasource->type_codename,
    'toggle' => $datasource->toggle,
    'verification_enable' => $datasource->verification_enable,
    'verification_digits' => $datasource->verification_digits,
    );

    // return $complete_space;
    $datasource = array("datasource" => $complete_datasource);
    return $datasource;
}

/**
* Creates Datasource
* @param Request request
* return datasource
*/
public function createDatasource(Request $request) {
    $datasource = new Datasource($request->all());
    if (!$datasource->save()) {
        abort(500, 'Could not save datasource.');
    }
    return $datasource;
}

/**
* @SWG\Get(
*      path="/datasources/type",
*      operationId="getDatasourcesType",
*      tags={"Datasources"},
*      summary="Get list of datasources type",
*      description="Returns list of datasources type",
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
* Returns list of Type of datasources
*/
public function getDatasourcesType() {
    $url = url('/');
    $types = \DB::table('datasource_type')->select('id', 'name', 'codename', 'icon_image')->get();
    $types_list = array();
    foreach ($types as $type) {
        $complete_type = array(
            'id' => $type->id,
            'name' => $type->name,
            'codename' => $type->codename,
            'icon_image' => $type->icon_image,
            'icon_image_on_url' => $url.'/datasources/icons/'.$type->icon_image.'_ON.png', 
            'icon_image_off_url' => $url.'/datasources/icons/'.$type->icon_image.'_OFF.png'         
            );


            array_push($types_list, $complete_type);
    }
    $types_list = array('datasourcestype' => $types_list);
    return $types_list;
}

public function getDatasourcesTypebyTypeName($typename) {
    $url = url('/');
    $type = \DB::table('datasource_type')
            ->where('name', '=', $typename)
            ->select('id', 'name', 'codename', 'icon_image')
            ->get();

        $complete_type = array(
            'id' => $type[0]->id,
            'name' => $type[0]->name,
            'codename' => $type[0]->codename,
            'icon_image' => $type[0]->icon_image,
            'icon_image_on_url' => $url.'/datasources/icons/'.$type[0]->icon_image.'_ON.png', 
            'icon_image_off_url' => $url.'/datasources/icons/'.$type[0]->icon_image.'_OFF.png'         
            );
    return $complete_type;
}


public function getDatasourceProtocolTypes() {
    $types = \DB::table('datasource_protocol_types')->select('id', 'name')->get();
    $datasourceprotocoltypes = array('datasourceprotocoltypes' => $types);
    return $datasourceprotocoltypes;
}

public function getDatasourceTypeById($datasourcetypeId) {
    $datasourcetype = \DB::table('datasource_type')->where('id', '=', $datasourcetypeId)->get();
    return $datasourcetype;
}



/**
* Get Datasource Type by Id
* @param datasourcetypeId
* return datasourcetype
*/
public function getProjectByDatasourceId($datasourceId) {
// $datasource = \DB::table('datasources')->where('id', '=', $datasourceId)->get();
    $datasource = Datasource::find($datasourceId);
    $projectId = $datasource->project_id;
    $project = Project::find($projectId);
    $project = array("project" => $project);
    return $project;
}

/**
* @SWG\Get(
*      path="/datasource/types/{datasource_type_id}",
*      operationId="getDatasourceTypeNameById",
*      tags={"Datasources"},
*      summary="Get Datasource Type by ID",
*      description="Returns Datasource type name",
*      @SWG\Parameter(
*          name="datasource_type_id",
*          description="Datasource type id",
*          required=true,
*          type="integer",
*          in="path"
*      ),
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
* Returns list of Type of datasources
*/
public function getDatasourceTypeNameById($datasourcetypeId) {
    $datasourcetypename = \DB::table('datasource_type')->where('id', '=', $datasourcetypeId)->value('name');
    return $datasourcetypename;
}

/**
* @SWG\Get(
*      path="/space/{space_id}/datasources",
*      operationId="GetDatasourcesBySpaceId",
*      tags={"Datasources"},
*      summary="Get datasources information related to space",
*      description="Returns datasources data related to space",
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
public function GetDatasourcesBySpaceId($space_id){
    $url = url('/');
    $datasources = Datasource::where('space_id', '=', $space_id)->get();
    // $datasource = Datasource::find($datasources->datasource_id);
    // $datasources = \DB::table('datasources')->where('space_id', '=', $space_id)->get();
    $datasources_list = array();
        foreach ($datasources as $datasource) {
                // $datasourcetype = \DB::table('datasource_type')->where('name', '=', $datasource->type)->get();
            $datasourcetype = $this->getDatasourcesTypebyTypeName($datasource->type);
            $options_array = json_decode($datasource->options, true);
            $d = array(
                    'id' => $datasource->id,
                    'name' => $datasource->name,
                    'type' => $datasource->type,
                    'unitid' => $datasource->image,
                    'ip' => $datasource->ip,
                    'port' => $datasource->port,
                    'options' => $datasource->options,
                    'options_array' => $options_array,
                    'data' => $datasource->data,
                    'notes' => $datasource->notes,
                    'active' => $datasource->active,
                    'project_id' => $datasource->project_id,
                    'created_at' => $datasource->created_at,
                    'updated_at' => $datasource->updated_at,
                    'deleted_at' => $datasource->deleted_at,
                    'space_id' => $datasource->space_id,
                    'toggle' => $datasource->toggle,
                    'verification_enable' => $datasource->verification_enable,
                    'verification_digits' => $datasource->toggle,
                    'datasourcetype' =>$datasourcetype

                    
         );
         array_push($datasources_list, $d);
     }


    return $datasources_list;
}

/**
* Get Datasoutce Protocol type by Id
* @param datasourceprotocoltypeId
* return datasourceprotocoltype
*/
public function getDatasourceProtocolTypeById($datasourceprotocoltypeId) {
    $datasourceprotocoltype = \DB::table('datasource_protocol_types')->where('id', '=', $datasourceprotocoltypeId)->get();
    return $datasourceprotocoltype;
}

/**
* Updates Datasource
* @param Request request, datasourceId
* return datasource
*/
public function updateDatasource(Request $request, $datasourceId) {
    $datasource = Datasource::find($datasourceId);
    if ($request->name) {$datasource->name = $request->name;}
    if ($request->type) {$datasource->type = $request->type;}
    if ($request->options) {$datasource->options = $request->options;}
    if ($request->data) {$datasource->data = $request->data;}
    if ($request->unitid) {$datasource->unitid = $request->unitid;}
    if ($request->ip) {$datasource->ip = $request->ip;}
    if ($request->port) {$datasource->port = $request->port;}
    if ($request->space_id) {$datasource->space_id = $request->space_id;}
    if ($request->notes) {$datasource->notes = $request->notes;}
    if ($request->toggle) {$datasource->toggle = $request->toggle;}
    if ($request->verification_enable) {$datasource->verification_enable = $request->verification_enable;}
    if ($request->verification_digits) {$datasource->verification_digits = $request->verification_digits;}
    if ($request->active == 0) {
        $datasource->active = 0;
    }
    if ($request->active == 1) {
        $datasource->active = 1;
    }

    if (!$datasource->save()) {
        abort(500, 'Could not update datasource.');
    }
    return $datasource;
}

/**
* Add Datapoint to a Datasource
* @param Request request
* return mixed
*/
public function attachDatapointDatasource($datapointId, $datasourceId) {
    $datasource = Datasource::where('id', $datasourceId)->first();
    $datapoint = Datapoint::where('id', $datapointId)->first();
    $datasource->Datapoints()->attach($datapointId);
    return $datasource;
}

/**
* @SWG\Get(
*      path="/project/{project_id}/datasources",
*      operationId="getProjectDatasources",
*      tags={"Datasources"},
*      summary="Get Datasources related to a particular project",
*      description="Returns List of datasources from a project",
*      @SWG\Parameter(
*          name="project_id",
*          description="Project ID",
*          required=true,
*          type="integer",
*          in="path"
*      ),
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
* Returns list of datasources
*/
public function getProjectDatasources($project_id) {
    $url = url('/');
    $datasources = Datasource::where('project_id', $project_id)->get();
    $datasources_list = array();
        foreach ($datasources as $datasource) {
            $datasourcetype = $this->getDatasourcesTypebyTypeName($datasource->type);
            $options_array = json_decode($datasource->options, true);
            $d = array(
                    'id' => $datasource->id,
                    'name' => $datasource->name,
                    'type' => $datasource->type,
                    'unitid' => $datasource->image,
                    'ip' => $datasource->ip,
                    'port' => $datasource->port,
                    'options' => $datasource->options,
                    'options_array' => $options_array,
                    'data' => $datasource->data,
                    'notes' => $datasource->notes,
                    'active' => $datasource->active,
                    'project_id' => $datasource->project_id,
                    'created_at' => $datasource->created_at,
                    'updated_at' => $datasource->updated_at,
                    'deleted_at' => $datasource->deleted_at,
                    'space_id' => $datasource->space_id,
                    'toggle' => $datasource->toggle,
                    'verification_enable' => $datasource->verification_enable,
                    'verification_digits' => $datasource->toggle,
                    'datasourcetype' =>$datasourcetype

                    
         );
         array_push($datasources_list, $d);
     }
     $datasources_list = array("datasources" => $datasources_list);

    return $datasources_list;

}

/**
* @SWG\Get(
*      path="/project/{project_id}/datasources/actives",
*      operationId="getActiveProjectDatasources",
*      tags={"Datasources"},
*      summary="Get Active Datasources related to a particular project",
*      description="Returns List of active datasources from a project",
*      @SWG\Parameter(
*          name="project_id",
*          description="Project ID",
*          required=true,
*          type="integer",
*          in="path"
*      ),
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
* Returns list of active datasources
*/
public function getActiveProjectDatasources($project_id) {
    $url = url('/');
    $datasources = Datasource::where('project_id', $project_id)
    ->where('active', 1)
    ->get();
    $datasources_list = array();
        foreach ($datasources as $datasource) {
            $datasourcetype = $this->getDatasourcesTypebyTypeName($datasource->type);
            $options_array = json_decode($datasource->options, true);
            $d = array(
                    'id' => $datasource->id,
                    'name' => $datasource->name,
                    'type' => $datasource->type,
                    'unitid' => $datasource->image,
                    'ip' => $datasource->ip,
                    'port' => $datasource->port,
                    'options' => $datasource->options,
                    'options_array' => $options_array,
                    'data' => $datasource->data,
                    'notes' => $datasource->notes,
                    'active' => $datasource->active,
                    'project_id' => $datasource->project_id,
                    'created_at' => $datasource->created_at,
                    'updated_at' => $datasource->updated_at,
                    'deleted_at' => $datasource->deleted_at,
                    'space_id' => $datasource->space_id,
                    'toggle' => $datasource->toggle,
                    'verification_enable' => $datasource->verification_enable,
                    'verification_digits' => $datasource->toggle,
                    'datasourcetype' =>$datasourcetype

                    
         );
         array_push($datasources_list, $d);
     }
     $datasources_list = array("datasources" => $datasources_list);

    return $datasources_list;
}


/**
* @SWG\Get(
*      path="/project/{project_id}/datasources/count",
*      operationId="getProjectDatasourcesCount",
*      tags={"Datasources"},
*      summary="Get count of  Datasources related to a particular project",
*      description="Returns count of active datasources from a project",
*      @SWG\Parameter(
*          name="project_id",
*          description="Project ID",
*          required=true,
*          type="integer",
*          in="path"
*      ),
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
* Returns count of datasources
*/
public function getProjectDatasourcesCount($project_id) {
    $project = Project::where('id', $project_id)->with('DataSources')->first();
    return $project->datasources->count();
//Reponse by Dingo
//return $this->response->array($project->datasources);
}

/**
* @SWG\Get(
*      path="/datasource/{datasource_id}/datapoints",
*      operationId="getDatasourceDatapoints",
*      tags={"Datapoints"},
*      summary="Get list of  Datapoints of a parrticular Datasource",
*      description="Returns list of  Datapoints of a parrticular Datasource",
*      @SWG\Parameter(
*          name="datasource_id",
*          description="Datasource_ ID",
*          required=true,
*          type="integer",
*          in="path"
*      ),
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
* Returns datapoints from a  datasources
*/
public function getDatasourceDatapoints($datasource_id) {
    $datapoints = Datapoint::where('datasource_id', $datasource_id)->get();
    $datapoints = array("datapoints" => $datapoints);
    return $datapoints;
}

/**
* Get all datapoints related to the datasource Count.
* @param $projectParam
* return int
*/
public function getDatasourceDatapointsCount($datasourceParam) {
    $datasource = Datasource::where('id', $datasourceParam)->first();
    return $datasources->datapoints->count();
//Reponse by Dingo
//return $this->response->array($project->datasources);
}

/**
* Deletes an datasource
* @param datasourceId
* return boolean
*/
public function deleteDatasource($datasourceId) {
    $dataPoints = Datapoint::where('datasource_id', $datasourceId)->get();
    if($dataPoints && count($dataPoints) > 0) {
        foreach($dataPoints as $point) {
            Datapoint::destroy($point->id);
        }
    }
    return Datasource::destroy($datasourceId);
}
}
