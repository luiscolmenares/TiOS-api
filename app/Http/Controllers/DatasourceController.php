<?php

namespace App\Http\Controllers;

use App\Project;
use App\Datapoint;
use App\Datasource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DatasourceController extends Controller {

    /**
     * Get all datasources
     * @param 
     * return datasources
     */
    public function getDatasources() {
        return Datasource::all();
    }

    /**
     * Gets an datasources by Id
     * @param datasourcesId
     * return datasources
     */
    public function getDatasource($DatasourceId) {
        $datasource = Datasource::find($DatasourceId);
        $datasource = array("datasource" => $datasource);
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
     * Get all datasources types
     * @param 
     * return datasources types
     */
    public function getDatasourcesType() {
        $types = \DB::table('datasource_type')->select('id', 'name')->get();
        $datasourcestype = array('datasourcestype' => $types);
        return $datasourcestype;
    }

    /**
     * Get all datasources protocol types
     * @param 
     * return datasources protocol types
     */
    public function getDatasourceProtocolTypes() {
        $types = \DB::table('datasource_protocol_types')->select('id', 'name')->get();
        $datasourceprotocoltypes = array('datasourceprotocoltypes' => $types);
        return $datasourceprotocoltypes;
    }

    /**
     * Get Datasource Type by Id
     * @param datasourcetypeId
     * return datasourcetype
     */
    public function getDatasourceTypeById($datasourcetypeId) {
        $datasourcetype = \DB::table('datasource_type')->where('id', '=', $datasourcetypeId)->get();
        return $datasourcetype;
    }

    /**
     * Get Datasource Name by id
     * @param datasourcetypeId
     * return datasourcetypename
     */
    public function getDatasourceTypeNameById($datasourcetypeId) {
        $datasourcetypename = \DB::table('datasource_type')->where('id', '=', $datasourcetypeId)->value('name');
        return $datasourcetypename;
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
        if ($request->notes) {$datasource->notes = $request->notes;}
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
     * Get all datasources related to the project.
     * @param $projectParam
     * return mixed
     */
    public function getProjectDatasources($projectParam) {
        $datasources = Datasource::where('project_id', $projectParam)->get();
        $datasources = array("datasources" => $datasources);
        return $datasources;
    }

    /**
     * Get all active datasources related to the project.
     * @param $projectParam
     * return mixed
     */
    public function getActiveProjectDatasources($projectParam) {
        $datasources = Datasource::where('project_id', $projectParam)
                                   ->where('active', 1)
                                   ->get();
        $datasources = array("datasources" => $datasources);
        return $datasources;
    }


    /**
     * Get all datasources related to the project Count.
     * @param $projectParam
     * return int
     */
    public function getProjectDatasourcesCount($projectParam) {
        $project = Project::where('id', $projectParam)->with('DataSources')->first();
        return $project->datasources->count();
//Reponse by Dingo
//return $this->response->array($project->datasources);
    }

    /**
     * Get all datapoints related to the datasource.
     * @param $projectParam
     * return mixed
     */
    public function getDatasourceDatapoints($datasourceParam) {
        $datapoints = Datapoint::where('datasource_id', $datasourceParam)->get();
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
