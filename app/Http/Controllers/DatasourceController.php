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



}
