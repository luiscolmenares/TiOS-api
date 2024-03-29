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
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
        'left_coordinate' => $datasource->left_coordinate,
        'top_coordinate' => $datasource->top_coordinate,
        'image' => $datasource->image,
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
* Get Project  by Datasource ID
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
* Get Space  by Datasource ID
* @param datasourcetypeId
* return datasourcetype
*/
public function getSpaceByDatasourceId($datasourceId) {
// $datasource = \DB::table('datasources')->where('id', '=', $datasourceId)->get();
    $datasource = Datasource::find($datasourceId);
    $spaceId = $datasource->space_id;
    $space = Space::find($spaceId);
    $space = array("space" => $space);
    return $space;
}

/**
* Get Organization  by Datasource ID
* @param datasourcetypeId
* return datasourcetype
*/
public function getOrganizationByDatasourceId($datasourceId) {
    $datasource = Datasource::find($datasourceId);
    $projectId = $datasource->project_id;
    $organization = app('App\Http\Controllers\ProjectController')->getOrganizationByProjectId($projectId);
// $organization = array("organization" => $organization);
    return $organization;
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
            'verification_digits' => $datasource->verification_digits,
            'left_coordinate' => $datasource->left_coordinate,
            'top_coordinate' => $datasource->top_coordinate,
            'image' => $datasource->image,
            'datasourcetype' =>$datasourcetype


        );
        array_push($datasources_list, $d);
    }


    return $datasources_list;
}

/**
* GetActiveDatasourcesBySpaceId
*
*/ 
public function GetActiveDatasourcesBySpaceId($space_id){
    $url = url('/');
    $datasources = Datasource::where('space_id', '=', $space_id)
    ->where('active', '=', 1)
    ->get();
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
            'verification_digits' => $datasource->verification_digits,
            'left_coordinate' => $datasource->left_coordinate,
            'top_coordinate' => $datasource->top_coordinate,
            'image' => $datasource->image,
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
    if ($request->left_coordinate) {$datasource->left_coordinate = $request->left_coordinate;}
    if ($request->top_coordinate) {$datasource->top_coordinate = $request->top_coordinate;}
    if ($request->image) {$datasource->image = $request->image;}
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
            'verification_digits' => $datasource->verification_digits,
            'left_coordinate' => $datasource->left_coordinate,
            'top_coordinate' => $datasource->top_coordinate,
            'image' => $datasource->image,
            'datasourcetype' =>$datasourcetype


        );
        array_push($datasources_list, $d);
    }
    $datasources_list = array("datasources" => $datasources_list);

    return $datasources_list;

}

/**
* @SWG\Get(
*      path="/project/{project_id}/hp/datasources",
*      operationId="getProjectHpDatasources",
*      tags={"Datasources"},
*      summary="Get Datasources and hotspot coordinates related to a particular project",
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
public function getProjectHpDatasources($project_id) {
    $url = url('/');
    $datasources = Datasource::where('project_id', $project_id)->get();
    $datasources_list = array();
    foreach ($datasources as $datasource) {
        if ($datasource->space_id > 0){
            $space = Space::find($datasource->space_id);
            $datasource_space_id = $space->id;
            $datasource_space_name =  $space->name;
            $datasource_space_url = "/project/".$space->project_id."/space/".$datasource->space_id;
            $datasource_space_link = array(
                'url' => "/#/project/".$space->project_id."/space/".$datasource->space_id,
                'label' => 'Go to space'
            );

        } else {
            $datasource_space_id = 0;
            $datasource_space_name =  null;
            $datasource_space_url = null;
            $datasource_space_link = null;

        }
        $datasourcetype = $this->getDatasourcesTypebyTypeName($datasource->type);
        $options_array = json_decode($datasource->options, true);
        $position = array(
            'left' => $datasource->left_coordinate,
            'top' => $datasource->top_coordinate

        );

        if (($datasource->left_coordinate != null) && ($datasource->top_coordinate != null) && ($datasource->image != null)){

            $d = array(
                'type' => 'text',
                'title' => $datasource->name,
                'options_array' => $options_array,
                'description' => '<img class="picture" src="'.$url.'/datasources/images/'.$datasource->image.'" alt="'.$datasource->name.'"><p class="description">'.$datasource->type.'</p><p><button id="custombtn" onclick="showDataSourceDetail(\''.$datasource->id.'\')">View Datasource</button></p>',
                'position' => $position,
// 'picturePath' => $url.'/datasources/images/'.$datasource->image,
                'space_id' => $datasource_space_id,
                'space_name' => $datasource_space_name,
                'space_url' => $datasource_space_url,
                'customClassName' => "custom-ii",
                'link' => $datasource_space_link,
                'sticky' => false,
            );
            array_push($datasources_list, $d);

        }

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
            'verification_digits' => $datasource->verification_digits,
            'left_coordinate' => $datasource->left_coordinate,
            'top_coordinate' => $datasource->top_coordinate,
            'image' => $datasource->image,
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

/**
* Thingstatus
* @param Request request
* return thing stattus
*/
public function Thingstatus(Request $request) {
    $noderedurl = "https://node-red.tiosplatform.com:1080";
// $noderedurl = $request->base_nr;
// $options = json_decode($act_datasource->options, true);
    $url_on = $noderedurl.'/thingstatus?topic='.$request->topic.'/control&value='.$request->value;
$client = new Client(); //GuzzleHttp\Cliente
$response = $client->get($url_on);
return $response;
}

/**
* Upload new File
*
* @param Request $request
*
* @return \Illuminate\Http\JsonResponse
*/
public function uploadImage(Request $request, $datasource_id)
{

    $validator = Validator::make($request->file(), [
        'file' => 'required|image|max:1000',
    ]);

    if ($validator->fails()) {

        $errors = [];
        foreach ($validator->messages()->all() as $error) {
            array_push($errors, $error);
        }

        return response()->json(['errors' => $errors, 'status' => 400], 400);
    }

    $datasource = Datasource::find($datasource_id);
    $datasource->image = $request->file('file')->getClientOriginalName();
    if (!$datasource->save()) {
        abort(500, 'Could not update datasources image.');
    }
    $request->file('file')->move(__DIR__ . '/../../../public/datasources/images/', $request->file('file')->getClientOriginalName());

    return response()->json(['errors' => [], 'datasources' => Datasource::find($request->datasource_id), 'status' => 200], 200);
}   


/**
* Datasources values by daterange
* @param Request request, datapointId
* return datapoint
*/
public function getDatasourceValuesByDateRange($datasourceId, $from_date, $to_date) {
    $datasource = Datasource::find($datasourceId);
// $datasource = $this->GetDatasourceByDatapointId($datapoint_id);
    $project = $this->GetProjectByDatasourceId($datasourceId)['project'];
    $organization = $this->GetOrganizationByDatasourceId($datasourceId)['organization'];

// return $project;
    $triggersnotifications = app('App\Http\Controllers\TriggerController')->getTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
    $toptriggersnotifications = app('App\Http\Controllers\TriggerController')->getTopTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
    $options_array = json_decode($datasource->options, true);
    $topic =  $options_array['topic'];
// $sensordata = \DB::table('sensordata')
// $sensordata = \DB::table('datasource_sensor_datas')
// ->select('created_at', 'created_at as date_created', 'value as data', 'topic')
// ->where('topic', "=", $topic)
// ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
// ->get()
// ->reverse();

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get()
    ->reverse();

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as total_count'), \DB::raw('MAX(value) as total_max'), \DB::raw('min(value) as total_min'), \DB::raw('AVG(value) as total_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->get();
// return $sensordatatotals;
    $sensordafilteredttotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as filtered_count'), \DB::raw('MAX(value) as filtered_max'), \DB::raw('min(value) as filtered_min'), \DB::raw('AVG(value) as filtered_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();
// return $sensordafilteredttotals;

    if($sensordafilteredttotals){
        $filtered_avg = $sensordafilteredttotals[0]->filtered_average;
        $filtered_maxvalue = $sensordafilteredttotals[0]->filtered_max;
        $filtered_minvalue = $sensordafilteredttotals[0]->filtered_min;
        $filtered_count = $sensordafilteredttotals[0]->filtered_count;

    } else {
        $filtered_avg = 0;
        $filtered_maxvalue = 0;
        $filtered_minvalue = 0; 
        $filtered_count = 0;
    }

    if($sensordatatotals){
        $total_avg = $sensordatatotals[0]->total_average;
        $total_maxvalue = $sensordatatotals[0]->total_max;
        $total_minvalue = $sensordatatotals[0]->total_min;
        $total_count = $sensordatatotals[0]->total_count;

    } else {
        $total_avg = 0; 
        $total_maxvalue = 0;
        $total_minvalue = 0;
        $total_count = 0;
    }

    $sum = 0;
    $count = 0;
    $totalsum = 0;
    $totalcount = 0;  
    $totalmaxvalue = 0;
    $totalminvalue=0;
// return $sensordata;
// foreach ($sensordata as $datapointvalue) {
//     // $sum = $sum + $datapointvalue->data;
//     // $totalsum = $totalsum + $datapointvalue->data;
//     // $count = $count + 1;
//     // $totalcount = $totalcount + 1;
//     // if ($totalmaxvalue < $datapointvalue->data){
//     //     $totalmaxvalue = $datapointvalue->data;
//     // }
//     // if ($totalminvalue > $datapointvalue->data){
//     //     $totalminvalue = $datapointvalue->data;
//     // }
//     // $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);

// }
    if(($from_date > 0) && ($to_date > 0)){
        $datasourceValuelist = [];
// $datasourceValuelist = (array) $sensordata;
// $datasourceValuelist = json_decode(json_encode($sensordata), true);
// $filtered_sum = 0;
// $filtered_count = 0;
// $filtered_maxvalue = 0;
// $filtered_minvalue=0;
        foreach ($sensordata as $datapointvalue) {
//     $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);
//     // if(($datapointvalue->date_created > $from_date) && ($datapointvalue->date_created < $to_date)){
            array_push($datasourceValuelist, $datapointvalue);
//         // $filtered_sum = $filtered_sum + $datapointvalue->data;
//         // $filtered_count = $filtered_count + 1;
//         // if ($filtered_maxvalue < $datapointvalue->data){
//         //     $filtered_maxvalue = $datapointvalue->data;
//         // }
//         // if ($filtered_minvalue > $datapointvalue->data){
//         //     $filtered_minvalue = $datapointvalue->data;
//         // }
//     // }
        }

        $datasourceValuelist = array(
            'datasource_name' => $datasource->name,
            'project_name' => $project->name,
            'organization_name' => $organization->name,
// 'sum' => $filtered_sum,
            'count' => $filtered_count,
            'totalcount' => $total_count,
            'average' => $filtered_avg,
            'totalaverage' => $total_avg,
            'highest' => $filtered_maxvalue,
            'lowest' => $filtered_minvalue,
            'totalhighest' => $total_maxvalue,
            'totallowest' => $total_minvalue,
            'toptriggersnotifications' => $toptriggersnotifications,
            'triggersnotifications' => $triggersnotifications,
            'sensordata' => $datasourceValuelist,
        );

        return $datasourceValuelist;
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
* Datasources values by daterange
* @param Request request, datapointId
* return datapoint
*/
public function getDatasourceValuesByDateRangeAnalytics($datasourceId, $from_date, $to_date) {
    $datasource = Datasource::find($datasourceId);
// $datasource = $this->GetDatasourceByDatapointId($datapoint_id);
    $project = $this->GetProjectByDatasourceId($datasourceId)['project'];
    $organization = $this->GetOrganizationByDatasourceId($datasourceId)['organization'];
    $space = $this->GetSpaceByDatasourceId($datasourceId)['space'];

// return $project;
// $triggersnotifications = app('App\Http\Controllers\TriggerController')->getTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
// $toptriggersnotifications = app('App\Http\Controllers\TriggerController')->getTopTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
    $options_array = json_decode($datasource->options, true);
    $topic =  $options_array['topic'];
// $sensordata = \DB::table('sensordata')
// $sensordata = \DB::table('datasource_sensor_datas')
// ->select('created_at', 'created_at as date_created', 'value as data', 'topic')
// ->where('topic', "=", $topic)
// ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
// ->get()
// ->reverse();

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get()
    ->reverse();

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as total_count'), \DB::raw('MAX(value) as total_max'), \DB::raw('min(value) as total_min'), \DB::raw('AVG(value) as total_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->get();
// return $sensordatatotals;
    $sensordafilteredttotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as filtered_count'), \DB::raw('MAX(value) as filtered_max'), \DB::raw('min(value) as filtered_min'), \DB::raw('AVG(value) as filtered_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();
// return $sensordafilteredttotals;

    if($sensordafilteredttotals){
        $filtered_avg = $sensordafilteredttotals[0]->filtered_average;
        $filtered_maxvalue = $sensordafilteredttotals[0]->filtered_max;
        $filtered_minvalue = $sensordafilteredttotals[0]->filtered_min;
        $filtered_count = $sensordafilteredttotals[0]->filtered_count;

    } else {
        $filtered_avg = 0;
        $filtered_maxvalue = 0;
        $filtered_minvalue = 0; 
        $filtered_count = 0;
    }

    if($sensordatatotals){
        $total_avg = $sensordatatotals[0]->total_average;
        $total_maxvalue = $sensordatatotals[0]->total_max;
        $total_minvalue = $sensordatatotals[0]->total_min;
        $total_count = $sensordatatotals[0]->total_count;

    } else {
        $total_avg = 0; 
        $total_maxvalue = 0;
        $total_minvalue = 0;
        $total_count = 0;
    }

    $sum = 0;
    $count = 0;
    $totalsum = 0;
    $totalcount = 0;  
    $totalmaxvalue = 0;
    $totalminvalue=0;
// return $sensordata;
// foreach ($sensordata as $datapointvalue) {
//     // $sum = $sum + $datapointvalue->data;
//     // $totalsum = $totalsum + $datapointvalue->data;
//     // $count = $count + 1;
//     // $totalcount = $totalcount + 1;
//     // if ($totalmaxvalue < $datapointvalue->data){
//     //     $totalmaxvalue = $datapointvalue->data;
//     // }
//     // if ($totalminvalue > $datapointvalue->data){
//     //     $totalminvalue = $datapointvalue->data;
//     // }
//     // $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);

// }
    if(($from_date > 0) && ($to_date > 0)){
        $datasourceValuelist = [];
// $datasourceValuelist = (array) $sensordata;
// $datasourceValuelist = json_decode(json_encode($sensordata), true);
// $filtered_sum = 0;
// $filtered_count = 0;
// $filtered_maxvalue = 0;
// $filtered_minvalue=0;
        foreach ($sensordata as $datapointvalue) {
//     $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);
//     // if(($datapointvalue->date_created > $from_date) && ($datapointvalue->date_created < $to_date)){
            array_push($datasourceValuelist, $datapointvalue);
//         // $filtered_sum = $filtered_sum + $datapointvalue->data;
//         // $filtered_count = $filtered_count + 1;
//         // if ($filtered_maxvalue < $datapointvalue->data){
//         //     $filtered_maxvalue = $datapointvalue->data;
//         // }
//         // if ($filtered_minvalue > $datapointvalue->data){
//         //     $filtered_minvalue = $datapointvalue->data;
//         // }
//     // }
        }

        $datasourceValuelist = array(
            'datasource_name' => $datasource->name,
            'space_name' => $space->name,
            'project_name' => $project->name,
            'organization_name' => $organization->name,
// 'sum' => $filtered_sum,
            'count' => $filtered_count,
            'totalcount' => $total_count,
            'average' => $filtered_avg,
            'totalaverage' => $total_avg,
            'highest' => $filtered_maxvalue,
            'lowest' => $filtered_minvalue,
            'totalhighest' => $total_maxvalue,
            'totallowest' => $total_minvalue,
// 'toptriggersnotifications' => $toptriggersnotifications,
// 'triggersnotifications' => $triggersnotifications,
            'sensordata' => $datasourceValuelist,
        );

        return $datasourceValuelist;
    } else {

        $sensordata = array(
            'datasource_name' => $datasource->name,
            'space_name' => $space->name,
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
// 'toptriggersnotifications' => $toptriggersnotifications,
// 'triggersnotifications' => $triggersnotifications,
            'sensordata' => $sensordata
        );

        return $sensordata;

    }


}

/**
* Datasources values by daterange
* @param Request request, datapointId
* return datapoint
*/
public function getDatasourceValuesBySpaceByDateRangeAnalytics($datasourceId, $from_date, $to_date) {
    $datasource = Datasource::find($datasourceId);
// $datasource = $this->GetDatasourceByDatapointId($datapoint_id);
    $project = $this->GetProjectByDatasourceId($datasourceId)['project'];
    $organization = $this->GetOrganizationByDatasourceId($datasourceId)['organization'];
    $space = $this->GetSpaceByDatasourceId($datasourceId)['space'];

// return $project;
// $triggersnotifications = app('App\Http\Controllers\TriggerController')->getTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
// $toptriggersnotifications = app('App\Http\Controllers\TriggerController')->getTopTriggersNotificationsByDatasourceId($datasourceId, $from_date, $to_date);
    $options_array = json_decode($datasource->options, true);
    $topic =  $options_array['topic'];
// $sensordata = \DB::table('sensordata')
// $sensordata = \DB::table('datasource_sensor_datas')
// ->select('created_at', 'created_at as date_created', 'value as data', 'topic')
// ->where('topic', "=", $topic)
// ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
// ->get()
// ->reverse();

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get()
    ->reverse();

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as total_count'), \DB::raw('MAX(value) as total_max'), \DB::raw('min(value) as total_min'), \DB::raw('AVG(value) as total_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->get();
// return $sensordatatotals;
    $sensordafilteredttotals = \DB::table('datasource_sensor_datas')
    ->select('topic', \DB::raw('COUNT(id) as filtered_count'), \DB::raw('MAX(value) as filtered_max'), \DB::raw('min(value) as filtered_min'), \DB::raw('AVG(value) as filtered_average'))
    ->groupBy('topic')
    ->where('topic', "=", $topic)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();
// return $sensordafilteredttotals;

    if($sensordafilteredttotals){
        $filtered_avg = $sensordafilteredttotals[0]->filtered_average;
        $filtered_maxvalue = $sensordafilteredttotals[0]->filtered_max;
        $filtered_minvalue = $sensordafilteredttotals[0]->filtered_min;
        $filtered_count = $sensordafilteredttotals[0]->filtered_count;

    } else {
        $filtered_avg = 0;
        $filtered_maxvalue = 0;
        $filtered_minvalue = 0; 
        $filtered_count = 0;
    }

    if($sensordatatotals){
        $total_avg = $sensordatatotals[0]->total_average;
        $total_maxvalue = $sensordatatotals[0]->total_max;
        $total_minvalue = $sensordatatotals[0]->total_min;
        $total_count = $sensordatatotals[0]->total_count;

    } else {
        $total_avg = 0; 
        $total_maxvalue = 0;
        $total_minvalue = 0;
        $total_count = 0;
    }

    $sum = 0;
    $count = 0;
    $totalsum = 0;
    $totalcount = 0;  
    $totalmaxvalue = 0;
    $totalminvalue=0;
// return $sensordata;
// foreach ($sensordata as $datapointvalue) {
//     // $sum = $sum + $datapointvalue->data;
//     // $totalsum = $totalsum + $datapointvalue->data;
//     // $count = $count + 1;
//     // $totalcount = $totalcount + 1;
//     // if ($totalmaxvalue < $datapointvalue->data){
//     //     $totalmaxvalue = $datapointvalue->data;
//     // }
//     // if ($totalminvalue > $datapointvalue->data){
//     //     $totalminvalue = $datapointvalue->data;
//     // }
//     // $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);

// }
    if(($from_date > 0) && ($to_date > 0)){
        $datasourceValuelist = [];
// $datasourceValuelist = (array) $sensordata;
// $datasourceValuelist = json_decode(json_encode($sensordata), true);
// $filtered_sum = 0;
// $filtered_count = 0;
// $filtered_maxvalue = 0;
// $filtered_minvalue=0;
        foreach ($sensordata as $datapointvalue) {
//     $datapointvalue->_blank = "";
//     // $datapointvalue->date_created = strtotime($datapointvalue->created_at);
//     // if(($datapointvalue->date_created > $from_date) && ($datapointvalue->date_created < $to_date)){
            array_push($datasourceValuelist, $datapointvalue);
//         // $filtered_sum = $filtered_sum + $datapointvalue->data;
//         // $filtered_count = $filtered_count + 1;
//         // if ($filtered_maxvalue < $datapointvalue->data){
//         //     $filtered_maxvalue = $datapointvalue->data;
//         // }
//         // if ($filtered_minvalue > $datapointvalue->data){
//         //     $filtered_minvalue = $datapointvalue->data;
//         // }
//     // }
        }

        $datasourceValuelist = array(
            'datasource_name' => $datasource->name,
            'space_name' => $space->name,
            'project_name' => $project->name,
            'organization_name' => $organization->name,
// 'sum' => $filtered_sum,
            'count' => $filtered_count,
            'totalcount' => $total_count,
            'average' => $filtered_avg,
            'totalaverage' => $total_avg,
            'highest' => $filtered_maxvalue,
            'lowest' => $filtered_minvalue,
            'totalhighest' => $total_maxvalue,
            'totallowest' => $total_minvalue,
// 'toptriggersnotifications' => $toptriggersnotifications,
// 'triggersnotifications' => $triggersnotifications,
            'sensordata' => $datasourceValuelist,
        );

        return $datasourceValuelist;
    } else {

        $sensordata = array(
            'datasource_name' => $datasource->name,
            'space_name' => $space->name,
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
// 'toptriggersnotifications' => $toptriggersnotifications,
// 'triggersnotifications' => $triggersnotifications,
            'sensordata' => $sensordata
        );

        return $sensordata;

    }


}


public function getDatasourceAverageValueByDateRange(Request $request, $datasourceId) {
    $average = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('AVG(value) as total_average'))
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasource_id' => $datasourceId,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'average' => $sensordatatotals[0]->total_average,
        );

    }


    return $datasourceValuelist;

}

public function getOrganizationDatasourceTypeAverageValueByDateRange(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as total_average'))
        ->where('organization_id',  $organizationId)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }
     
if($sensordatatotals){
        $datasourceValuelist = array(
            'projects_id' => $project_id_list,
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'average' => $sensordatatotals[0]->total_average,
        );

    }


    return $datasourceValuelist;



}

public function getOrganizationDatasourceTypeMaxValueByDateRange(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MAX(value) as max_value'))
        ->where('organization_id',  $organizationId)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }
     
if($sensordatatotals){
        $datasourceValuelist = array(
            'projects_id' => $project_id_list,
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'max_value' => $sensordatatotals[0]->max_value,
        );

    }


    return $datasourceValuelist;



}

public function getOrganizationDatasourceTypeMinValueByDateRange(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MIN(value) as min_value'))
        ->where('organization_id',  $organizationId)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }
     
if($sensordatatotals){
        $datasourceValuelist = array(
            'projects_id' => $project_id_list,
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'min_value' => $sensordatatotals[0]->min_value,
        );

    }


    return $datasourceValuelist;



}

public function getOrganizationDatasourceTypeCountValueByDateRange(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('COUNT(value) as count_value'))
        ->where('organization_id',  $organizationId)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }
     
if($sensordatatotals){
        $datasourceValuelist = array(
            'projects_id' => $project_id_list,
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'count_value' => $sensordatatotals[0]->count_value,
        );

    }


    return $datasourceValuelist;



}

public function getOrganizationDatasourceTypeValuesByDateRange(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select('datasource_id', 'project_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
        ->wherein('project_id', $project_id_list)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->orderBy('created_at', 'desc')
        ->get();
    }


    return $sensordata;



}

public function getOrganizationDatasourceTypeValuesByDateRangeMinute(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'))
        ->wherein('project_id', $project_id_list)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
        ->get();

    }


    return $sensordata;



}

public function getOrganizationDatasourceTypeValuesByDateRangeHour(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_hour'))
        ->wherein('project_id', $project_id_list)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
        ->get();

    }


    return $sensordata;



}

public function getOrganizationDatasourceTypeValuesByDateRangeDay(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_day'))
        ->wherein('project_id', $project_id_list)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
        ->get();

    }


    return $sensordata;



}

public function getOrganizationDatasourceTypeValuesByDateRangeMonth(Request $request, $organizationId) {
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get datasources array       
        foreach($project_id_list as $project_id){
            $datasources = Datasource::where('type', $request->datasource_type)
                ->where('project_id', $project_id)
                ->select('id')
                ->get();
                foreach ($datasources as $datasource) {
                array_push($datasource_id_list, $datasource->id);
            }

        }       
        
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($organizationId){
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'))
        ->wherein('project_id', $project_id_list)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();

    }


    return $sensordata;



}
public function getOrganizationDatasourceTypeAverageByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project_name = Project::find($project_id)->name;
            $ProjectDatasourceTypeAverageValueByDateRange = $this->getProjectDatasourceTypeAverageValueByDateRange($request, $project_id);

            $ProjectDatasourceTypeAverageValueByDateRange['project_name'] = $project_name;
            array_push($projects_array, $ProjectDatasourceTypeAverageValueByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeMinByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeMinValueByDateRange = $this->getProjectDatasourceTypeMinValueByDateRange($request, $project_id);

            $ProjectDatasourceTypeMinValueByDateRange['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeMinValueByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeMaxByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeMaxValueByDateRange = $this->getProjectDatasourceTypeMaxValueByDateRange($request, $project_id);

            $ProjectDatasourceTypeMaxValueByDateRange['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeMaxValueByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeCountByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeCountValueByDateRange = $this->getProjectDatasourceTypeValueCountByDateRange($request, $project_id);

            $ProjectDatasourceTypeCountValueByDateRange['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeCountValueByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}


public function getOrganizationDatasourceTypeValuesByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeValuesByDateRange = $this->getProjectDatasourceTypeValuesByDateRange($request, $project_id);

            $ProjectDatasourceTypeValuesByDateRange['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeValuesByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeValuesMonthByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeValuesByDateRangeMonth = $this->getProjectDatasourceTypeValuesByDateRangeMonth($request, $project_id);

            $ProjectDatasourceTypeValuesByDateRangeMonth['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeValuesByDateRangeMonth);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeValuesDayByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeValuesByDateRangeDay = $this->getProjectDatasourceTypeValuesByDateRangeDay($request, $project_id);

            $ProjectDatasourceTypeValuesByDateRangeDay['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeValuesByDateRangeDay);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeValuesHourByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeValuesByDateRangeHour = $this->getProjectDatasourceTypeValuesByDateRangeHour($request, $project_id);

            $ProjectDatasourceTypeValuesByDateRangeHour['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeValuesByDateRangeHour);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getOrganizationDatasourceTypeValuesMinuteByProjectsByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeValuesByDateRangeMinute = $this->getProjectDatasourceTypeValuesByDateRangeMinute($request, $project_id);

            $ProjectDatasourceTypeValuesByDateRangeMinute['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeValuesByDateRangeMinute);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}


public function getOrganizationDatasourceTypeByProjectsValueByDateRange(Request $request, $organizationId){
    if ($request->datasource_type){
        //Get projects id array
        $orgprojects = app('App\Http\Controllers\OrganizationController')->getOrgProjects($organizationId)['projects'];
        $project_id_list = array();
        $datasource_id_list = array();
        foreach ($orgprojects as $project) {
            array_push($project_id_list, $project['id']);
        }

        //get projects average array  
        $projects_array = array();     
        foreach($project_id_list as $project_id){
            $project = Project::find($project_id);
            $ProjectDatasourceTypeMaxValueByDateRange = $this->getProjectDatasourceTypeMaxValueByDateRange($request, $project_id);

            $ProjectDatasourceTypeMaxValueByDateRange['project_name'] = $project->name;
            array_push($projects_array, $ProjectDatasourceTypeMaxValueByDateRange);


        }       
        
    } else {
        return "datasource type not provided";
    }

     return $projects_array;

}

public function getSpaceDatasourceTypeAverageValueByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as total_average'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            // 'spaces_id' => $spaces_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'average' => $sensordatatotals[0]->total_average,
        );

    }


    return $datasourceValuelist;

}

public function getSpaceDatasourceTypeAverageValueDatasourcesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

              $sensordatatotals = \DB::table('datasource_sensor_datas')
            ->select(\DB::raw('AVG(value) as total_average'))
            ->where('datasource_id',  $datasource->id)
            ->whereBetween('timestamp', [$from_date, $to_date])
            ->get();

        if($sensordatatotals){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'average' => $sensordatatotals[0]->total_average,
            );

        }

         array_push($datasources_array, $datasourceValuelist);
            

    }


    return $datasources_array;

}

public function getSpaceDatasourceTypeMaxValueDatasourcesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

              $sensordatatotals = \DB::table('datasource_sensor_datas')
            ->select(\DB::raw('MAX(value) as max_value'))
            ->where('datasource_id',  $datasource->id)
            ->whereBetween('timestamp', [$from_date, $to_date])
            ->get();

        if($sensordatatotals){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'max_value' => $sensordatatotals[0]->max_value,
            );

        }

         array_push($datasources_array, $datasourceValuelist);
            

    }


    return $datasources_array;

}

public function getSpaceDatasourceTypeMinValueDatasourcesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

              $sensordatatotals = \DB::table('datasource_sensor_datas')
            ->select(\DB::raw('MIN(value) as min_value'))
            ->where('datasource_id',  $datasource->id)
            ->whereBetween('timestamp', [$from_date, $to_date])
            ->get();

        if($sensordatatotals){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'min_value' => $sensordatatotals[0]->min_value,
            );

        }

         array_push($datasources_array, $datasourceValuelist);
            

    }


    return $datasources_array;

}

public function getSpaceDatasourceTypeValueCountDatasourcesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

              $sensordatatotals = \DB::table('datasource_sensor_datas')
            ->select(\DB::raw('COUNT(value) as count_value'))
            ->where('datasource_id',  $datasource->id)
            ->whereBetween('timestamp', [$from_date, $to_date])
            ->get();

        if($sensordatatotals){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'count_value' => $sensordatatotals[0]->count_value,
            );

        }

         array_push($datasources_array, $datasourceValuelist);
            

    }


    return $datasources_array;

}



public function getSpaceDatasourceTypeValuesDatasourcesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

         $sensordata = \DB::table('datasource_sensor_datas')
        ->select('created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
        ->where('datasource_id',  $datasource->id)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->orderBy('created_at', 'desc')
        ->get();

        if($sensordata){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'values' => $sensordata,
            );

        }
        


         array_push($datasources_array, $datasourceValuelist);
            
    }

    return $datasources_array;

}

public function getSpaceDatasourceTypeValuesDatasourcesByDateRangeMinute(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

         $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'))
        ->where('datasource_id',  $datasource->id)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
        ->get();

        if($sensordata){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'values' => $sensordata,
            );

        }
        


         array_push($datasources_array, $datasourceValuelist);
            
    }

    return $datasources_array;

}

public function getSpaceDatasourceTypeValuesDatasourcesByDateRangeHour(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

         $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_hour'))
        ->where('datasource_id',  $datasource->id)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
        ->get();

        if($sensordata){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'values' => $sensordata,
            );

        }
        


         array_push($datasources_array, $datasourceValuelist);
            
    }

    return $datasources_array;

}

public function getSpaceDatasourceTypeValuesDatasourcesByDateRangeDay(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

         $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_day'))
        ->where('datasource_id',  $datasource->id)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
        ->get();

        if($sensordata){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'values' => $sensordata,
            );

        }
        


         array_push($datasources_array, $datasourceValuelist);
            
    }

    return $datasources_array;

}

public function getSpaceDatasourceTypeValuesDatasourcesByDateRangeMonth(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id', 'name', 'type')
        ->get();
        $datasource_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_list, $datasource);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

$datasources_array = array();

        foreach ($datasource_list as $datasource) {

         $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'))
        ->where('datasource_id',  $datasource->id)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();

        if($sensordata){
            $datasourceValuelist = array(
                'datasource_id' => $datasource->id,
                'datasource_name' => $datasource->name,
                'datasource_type' => $datasource->type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'values' => $sensordata,
            );

        }
        


         array_push($datasources_array, $datasourceValuelist);
            
    }

    return $datasources_array;

}

public function getSpaceDatasourceTypeMaxValueByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MAX(value) as max_value'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'max_value' => $sensordatatotals[0]->max_value,
        );

    }


    return $datasourceValuelist;

}

public function getSpaceDatasourceTypeMinValueByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MIN(value) as min_value'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'min_value' => $sensordatatotals[0]->min_value,
        );

    }


    return $datasourceValuelist;

}

public function getSpaceDatasourceTypeValuesByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordata = \DB::table('datasource_sensor_datas')
        ->select('datasource_id', 'project_id', 'space_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->orderBy('created_at', 'desc')
        ->get();



    return $sensordata;

}

public function getSpaceDatasourceTypeValuesByDateRangeMinute(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
        ->get();



    return $sensordata;

}

public function getSpaceDatasourceTypeValuesByDateRangeHour(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_hour'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
        ->get();



    return $sensordata;

}

public function getSpaceDatasourceTypeValuesByDateRangeDay(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_day'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
        ->get();



    return $sensordata;

}

public function getSpaceDatasourceTypeValuesByDateRangeMonth(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();



    return $sensordata;

}

public function getSpaceDatasourceTypeValueCountByDateRange(Request $request, $spaceId) {
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('space_id', $spaceId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('COUNT(value) as count_value'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'count_value' => $sensordatatotals[0]->count_value,
        );

    }


    return $datasourceValuelist;

}

public function getProjectDatasourceTypeAverageValueSpacesByDateRange(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces average array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeAverageValueByDateRange = $this->getSpaceDatasourceTypeAverageValueByDateRange($request, $space_id);

            $SpaceDatasourceTypeAverageValueByDateRange['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeAverageValueByDateRange);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeMaxValueSpacesByDateRange(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces max value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeMaxValueByDateRange = $this->getSpaceDatasourceTypeMaxValueByDateRange($request, $space_id);

            $SpaceDatasourceTypeMaxValueByDateRange['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeMaxValueByDateRange);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeMinValueSpacesByDateRange(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeMinValueByDateRange = $this->getSpaceDatasourceTypeMinValueByDateRange($request, $space_id);

            $SpaceDatasourceTypeMinValueByDateRange['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeMinValueByDateRange);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeValueCountSpacesByDateRange(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValueCountByDateRange = $this->getSpaceDatasourceTypeValueCountByDateRange($request, $space_id);

            $SpaceDatasourceTypeValueCountByDateRange['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValueCountByDateRange);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeValuesSpacesByDateRange(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValuesByDateRange = $this->getSpaceDatasourceTypeValuesByDateRange($request, $space_id);

            $SpaceDatasourceTypeValuesByDateRange['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValuesByDateRange);


        }       

    return $spaces_array;

}


public function getProjectDatasourceTypeValuesSpacesByDateRangeMonth(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValuesByDateRangeMonth = $this->getSpaceDatasourceTypeValuesByDateRangeMonth($request, $space_id);

            $SpaceDatasourceTypeValuesByDateRangeMonth['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValuesByDateRangeMonth);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeValuesSpacesByDateRangeDay(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValuesByDateRangeDay = $this->getSpaceDatasourceTypeValuesByDateRangeDay($request, $space_id);

            $SpaceDatasourceTypeValuesByDateRangeDay['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValuesByDateRangeDay);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeValuesSpacesByDateRangeHour(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValuesByDateRangeHour = $this->getSpaceDatasourceTypeValuesByDateRangeHour($request, $space_id);

            $SpaceDatasourceTypeValuesByDateRangeHour['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValuesByDateRangeHour);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeValuesSpacesByDateRangeMinute(Request $request, $projectId) {
    if ($request->datasource_type){
        $prospaces = app('App\Http\Controllers\SpaceController')->getSpacesByProjectId($projectId)['spaces'];
        $spaces_id_list = array();
        $datasource_id_list = array();
        foreach ($prospaces as $prospace) {
            array_push($spaces_id_list, $prospace['id']);
        }
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

//get spaces min value array  
        $spaces_array = array();     
        foreach($spaces_id_list as $space_id){
            $space_name = Space::find($space_id)->name;
            $SpaceDatasourceTypeValuesByDateRangeMinute = $this->getSpaceDatasourceTypeValuesByDateRangeMinute($request, $space_id);

            $SpaceDatasourceTypeValuesByDateRangeMinute['space_name'] = $space_name;
            array_push($spaces_array, $SpaceDatasourceTypeValuesByDateRangeMinute);


        }       

    return $spaces_array;

}

public function getProjectDatasourceTypeAverageValueByDateRange(Request $request, $projectId) {
    $spaces_id = 0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }

    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as total_average'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    } else {
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as total_average'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'spaces_id' => $spaces_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'average' => $sensordatatotals[0]->total_average,
        );

    }


    return $datasourceValuelist;

}

public function getProjectDatasourceTypeMinValueByDateRange(Request $request, $projectId) {
    $spaces_id = 0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }


    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MIN(value) as total_min'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    } else {
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MIN(value) as total_min'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'spaces_id' => $spaces_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'min_value' => $sensordatatotals[0]->total_min,
        );

    }


    return $datasourceValuelist;

}

public function getProjectDatasourceTypeMaxValueByDateRange(Request $request, $projectId) {
    $spaces_id = 0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }


    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MAX(value) as total_max'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    } else {
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('MAX(value) as total_max'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'spaces_id' => $spaces_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'max_value' => $sensordatatotals[0]->total_max,
        );

    }


    return $datasourceValuelist;

}

public function getProjectDatasourceTypeValueCountByDateRange(Request $request, $projectId) {
    $spaces_id = 0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }


    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('COUNT(value) as total_count'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();

    } else {
        $sensordatatotals = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('COUNT(value) as total_count'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->get();
    }

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasources_id' => $datasource_id_list,
            'spaces_id' => $spaces_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'count' => $sensordatatotals[0]->total_count,
        );

    }


    return $datasourceValuelist;

}

public function getProjectDatasourceTypeValuesByDateRange(Request $request, $projectId) {
    $spaces_id=0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }


    $sensordata = \DB::table('datasource_sensor_datas')
    ->select('datasource_id', 'project_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
    ->wherein('datasource_id',  $datasource_id_list)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->orderBy('created_at', 'desc')
    ->get();

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select('datasource_id', 'project_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->orderBy('created_at', 'desc')
        ->get();

    } else {
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select('datasource_id', 'project_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value as data', 'topic', \DB::raw('"" as _blank'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
        ->orderBy('created_at', 'desc')
        ->get();
    }


    return $sensordata;

}

public function getProjectDatasourceTypeValuesByDateRangeMinute(Request $request, $projectId) {
    $spaces_id=0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
        ->get();

    } else {
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
        ->get();
    }


    return $sensordata;

}

public function getProjectDatasourceTypeValuesByDateRangeHour(Request $request, $projectId) {
    $spaces_id=0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_hour'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
        ->get();

    } else {
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_hour'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
        ->get();
    }


    return $sensordata;

}

public function getProjectDatasourceTypeValuesByDateRangeDay(Request $request, $projectId) {
    $spaces_id=0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_day'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
        ->get();

    } else {
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date_day'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
        ->get();
    }


    return $sensordata;

}

public function getProjectDatasourceTypeValuesByDateRangeMonth(Request $request, $projectId) {
    $spaces_id=0;
    if ($request->datasource_type){
        $datasources = Datasource::where('type', $request->datasource_type)
        ->where('project_id', $projectId)
        ->select('id')
        ->get();
        $datasource_id_list = array();
        foreach ($datasources as $datasource) {

            array_push($datasource_id_list, $datasource->id);
        }
    } else {
        return "datasource type not provided";
    }
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    if ($request->spaces){
        $spaces_id = $request->spaces;
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'))
        ->wherein('space_id', $request->spaces)
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();

    } else {
        $sensordata = \DB::table('datasource_sensor_datas')
        ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'))
        ->wherein('datasource_id',  $datasource_id_list)
        ->whereBetween('timestamp', [$from_date, $to_date])
// ->orderBy('created_at', 'desc')
        ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
        ->get();
    }


    return $sensordata;

}

public function getDatasourceMaxValueByDateRange(Request $request, $datasourceId) {
    $max = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('MAX(value) as max_value'))
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasource_id' => $datasourceId,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'max_value' => $sensordatatotals[0]->max_value,
        );

    }


    return $datasourceValuelist;
}

public function getDatasourceMinValueByDateRange(Request $request, $datasourceId) {
    $datasourceValuelist = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('min(value) as min_value'))
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasource_id' => $datasourceId,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'min_value' => $sensordatatotals[0]->min_value,
        );

    }


    return $datasourceValuelist;

}

public function getDatasourceValueCountByDateRange(Request $request, $datasourceId) {
    $datasourceValuelist = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordatatotals = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('COUNT(value) as count_value'))
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->get();

    if($sensordatatotals){
        $datasourceValuelist = array(
            'datasource_id' => $datasourceId,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'count_value' => $sensordatatotals[0]->count_value,
        );

    }


    return $datasourceValuelist;

}

public function getDatasourceValuesNewByDateRange(Request $request, $datasourceId) {
    $sensordata = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select('datasource_id', 'created_at', \DB::raw('UNIX_TIMESTAMP(STR_TO_DATE(created_at, "%Y-%m-%d %H:%i:%s")) as date_created'), 'value', 'topic', \DB::raw('"" as _blank'))
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->orderBy('created_at', 'desc')
    ->get();


    return $sensordata;

}

public function getDatasourceAverageValuesNewByDateRangeHour(Request $request, $datasourceId) {
    $sensordata = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H") as date_and_hour'), 'topic')
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'))
    ->groupBy('topic')
    ->get();


    return $sensordata;

}

public function getDatasourceAverageValuesNewByDateRangeDay(Request $request, $datasourceId) {
    $sensordata = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'), 'topic')
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
    ->groupBy('topic')
    ->get();


    return $sensordata;

}

public function getDatasourceAverageValuesNewByDateRangeMinute(Request $request, $datasourceId) {
    $sensordata = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as date_minute'), 'topic')
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'))
    ->groupBy('topic')
    ->get();


    return $sensordata;

}

public function getDatasourceAverageValuesNewByDateRangeMonth(Request $request, $datasourceId) {
    $sensordata = 0;
    $datasource = Datasource::find($datasourceId);
    if($request->from_date){
        $from_date = $request->from_date;
    } else
    {
        $from_date = 0;
    }
    if($request->to_date){
        $to_date = $request->to_date;
    } else
    {
        $to_date = 99999999999;
    }

    $sensordata = \DB::table('datasource_sensor_datas')
    ->select(\DB::raw('AVG(value) as value'), \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date_month'), 'topic')
    ->where('datasource_id', "=", $datasourceId)
    ->whereBetween('timestamp', [$from_date, $to_date])
    ->groupBy(\DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
    ->groupBy('topic')
    ->get();


    return $sensordata;

}


}
