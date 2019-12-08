<?php

namespace App\Http\Controllers;

use App\Space;
use App\Project;
use App\Organization;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Validator;
use Illuminate\Routing\UrlGenerator;

class SpaceController extends Controller
{
    /**
* @SWG\Get(
*      path="/spaces",
*      operationId="getSpaces",
*      tags={"Spaces"},
*      summary="Get list of spaces",
*      description="Returns list of spaces",
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
* Returns list of spaces
*/
    public function getSpaces() {
        // return Space::all();
        $url = url('/');
        $spaces_list = array();
        $spaces = Space::all();
        foreach ($spaces as $space) {
        	$space = Space::find($space->id);
        	$datasources = app('App\Http\Controllers\DatasourceController')->GetDatasourcesBySpaceId($space->id);

			$complete_space = array(
			'id' => $space->id,
			'name' => $space->name,
			'image' => $space->image,
			'image_url' => $url.'/spaces/images/'.$space->image,
			'icon_image' => $space->icon_image,
			'icon_image_url' => $url.'/spaces/icons/'.$space->icon_image,
			'organization_id' => $space->organization_id,
			'project_id' => $space->project_id,
			'datasources' => $datasources,
			
			);


        	array_push($spaces_list, $complete_space);


        }
	
	return $spaces_list;
    }

    /**
	* Create space
	* @param Request request
	* return User
	*/
	public function createSpace(Request $request)
	{
	    $space = new Space($request->all());
	    if (!$space->save()) {
	        abort(500, 'Could not save space.');
	    }
	    return $space;
	}

	/**
* Updates space
* @param Request request, datapointId
* return datapoint
*/
public function updateSpace(Request $request, $space_id) {
    $space = Space::find($space_id);
    if ($request->name) {$space->name = $request->name;}
    if ($request->image) {$space->image = $request->image;}
    if ($request->organization_id) {$space->organization_id = $request->organization_id;}
    if ($request->project_id) {$space->project_id = $request->project_id;}
    if ($request->icon_image) {$space->icon_image = $request->icon_image;}
    if (!$space->save()) {
        abort(500, 'Could not update space.');
    }
    return $space;
}

	/**
     * Deletes space
     * @param spaceId
     * return boolean
     */
    public function deleteSpace($spaceId) {

        return Space::destroy($spaceId);
    }

	/**
* @SWG\Get(
*      path="/space/{id}",
*      operationId="getSpace",
*      tags={"Spaces"},
*      summary="Get space information",
*      description="Returns space data",
*      @SWG\Parameter(
*          name="id",
*          description="Space id",
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
	public function getSpace($spaceId){
	$url = url('/');
	$space = Space::find($spaceId);
    $project = Project::find($space->project_id);
	$datasources = app('App\Http\Controllers\DatasourceController')->GetActiveDatasourcesBySpaceId($spaceId);
/*
*  +----+-----------------------------------------+----------------------------------+
*  | id | name                                    | codename                         |
*  +----+-----------------------------------------+----------------------------------+
*  |  1 | Vemetris Gateway                        | ds-vemetris-gateway              |
*  |  2 | Control: Smart Bulb                     | ds-smart-bulb                    |
*  |  3 | Control: Smart Switch (Light)           | ds-smart-switch-light            |
*  |  4 | Control: Smart Switch (AC)              | ds-smart-switch-ac               |
*  |  5 | Control: Smart Switch (Water Valve)     | ds-smart-switch-wv               |
*  |  6 | Control: Smart Switch (Gas Valve)       | ds-smart-switch-gv               |
*  |  7 | Control: Smart Switch (Lock)            | ds-smart-switch-lock             |
*  |  8 | Control: Smart Switch (Power)           | ds-smart-switch-pw               |
*  |  9 | Monitor: Temperature Sensor (Celsius)   | ds-temperature-celsius           |
*  | 10 | Monitor: Temperature Sensor (Farenheit) | ds-temperature-celsius-farenheit |
*  | 11 | Monitor: Humidity Sensor                | ds-humidity                      |
*  | 12 | Monitor: Proximity Sensor               | ds-proximity                     |
*  | 13 | Monitor: Door Sensor                    | ds-door                          |
*  | 14 | Monitor: Flood Sensor                   | ds-flood                         |
*  | 15 | Monitor: Voltage (V)                    | ds-voltage                       |
*  | 16 | Monitor: Electric Current (A)           | ds-current                       |
*  | 17 | Monitor: Electric Power (W)             | ds-power                         | 
*  | 18 | Monitor: Electric Energy (E)            | ds-energy                        | 
*  | 19 | Monitor: Electric Energy (kWh)          | ds-kwhenergy                     | 
*  | 20 | Monitor: Apparent power (KVA)           | ds-apower                        | 
*  | 21 | Monitor: Real power (KW)                | ds-rpower                        | 
+----+-----------------------------------------+------------+------------+-----------+
*/
$datasources_list = array();
    foreach ($datasources as $datasource) {
        if (($datasource['type'] === "Control: Smart Bulb") ||
            ($datasource['type'] === "Control: Smart Switch (Light)") ||
            ($datasource['type'] === "Control: Smart Switch (AC)") ||
            ($datasource['type'] === "Control: Smart Switch (Water Valve)") ||
            ($datasource['type'] === "Control: Smart Switch (Gas Valve)") ||
            ($datasource['type'] === "Control: Smart Switch (Lock)") ||
            ($datasource['type'] === "Control: Smart Switch (Power)") ||
            ($datasource['type'] === "Monitor: Temperature Sensor (Celsius)") ||
            ($datasource['type'] === "Monitor: Temperature Sensor (Farenheit)") ||
            ($datasource['type'] === "Monitor: Humidity Sensor") ||
            ($datasource['type'] === "Monitor: Proximity Sensor") ||
            ($datasource['type'] === "Monitor: Door Sensor") ||
            ($datasource['type'] === "Monitor: Flood Sensor") ||
            ($datasource['type'] === "Monitor: Voltage (V)") ||
            ($datasource['type'] === "Monitor: Electric Current (A)") ||
            ($datasource['type'] === "Monitor: Electric Power (W)") ||
            ($datasource['type'] === "Monitor: Electric Energy (E)") ||
            ($datasource['type'] === "Monitor: Electric Energy (kWh)") ||
            ($datasource['type'] === "Monitor: Apparent power (KVA)") ||
            ($datasource['type'] === "Monitor: Real power (KW)")
    ){
            $datasource['data'] = app('App\Http\Controllers\DashboardController')->getLastSensorDataFromDatasource($datasource['options_array']['topic']);
            // $datasource['data'] = "data de Control: Smart Switch (Light)";
            array_push($datasources_list, $datasource);
            // return $datasource['data'];
        }

    }

	$complete_space = array(
	'id' => $space->id,
	'name' => $space->name,
	'image' => $space->image,
	'image_url' => $url.'/spaces/images/'.$space->image,
	'icon_image' => $space->icon_image,
	'icon_image_url' => $url.'/spaces/icons/'.$space->icon_image,
	'organization_id' => $space->organization_id,
	'project_id' => $space->project_id,
    'project_name' => $project->name,
	'datasources' => $datasources_list,
	
	);

	return $complete_space;
	}

	/**
* @SWG\Get(
*      path="/project/{project_id}/spaces/count",
*      operationId="getProjectSpacesCount",
*      tags={"Spaces"},
*      summary="Get spaces count information by project",
*      description="Returns sspaces count information by project",
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
    public function getProjectSpacesCount($projectId) {
        return  Space::where('project_id', $projectId)->count(); 
    }


	/**
* @SWG\Get(
*      path="/project/{project_id}/spaces",
*      operationId="getSpacesByProjectId",
*      tags={"Spaces"},
*      summary="Get spaces information by project",
*      description="Returns spaces information by project",
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
	public function getSpacesByProjectId($projectId){
	$url = url('/');
	$spaces = Space::where('project_id', $projectId)->get();
	$project = Project:: find($projectId);
	$organization_id = $project->organization_id;
	$organization = Organization::find($organization_id);
	$spaces_list = array();
        foreach ($spaces as $space) {
        	$datasources = app('App\Http\Controllers\DatasourceController')->GetDatasourcesBySpaceId($space->id);
            $s = array(
                    'id' => $space->id,
                    'name' => $space->name,
                    'organization_id' => $space->organization_id,
                    'project_id' => $space->project_id,
                    'image' => $space->image,
                    'image_url' => $url.'/spaces/images/'.$space->image,
					'icon_image' => $space->icon_image,
					'icon_image_url' => $url.'/spaces/icons/'.$space->icon_image,
                    'organization_name' => $organization->name,
                    'project_name' => $project->name,
                    'datasources' => $datasources,
                    
         );
         array_push($spaces_list, $s);

        }
         $spaces = array('spaces' => $spaces_list);

	return $spaces;
	}		
			
   /**
* @SWG\Get(
*      path="/organization/{organization_id}/spaces",
*      operationId="getSpacesByOrganizationId",
*      tags={"Spaces"},
*      summary="Get spaces information by organization",
*      description="Returns spaces information by organization",
*      @SWG\Parameter(
*          name="organization_id",
*          description="Organization id",
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
	public function getSpacesByOrganizationId($projectId){
	$spaces = Space::where('organization_id', $projectId)->get();
	return $spaces;
	}	

	/**
     * Upload new File
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request, $space_id)
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

         $space = Space::find($space_id);
            //$sportevent = Sportevent::find($re);
            $space->image = $request->file('file')->getClientOriginalName();
           // $sportevent->logo = 'imagen33.png';
            if (!$space->save()) {
            abort(500, 'Could not update space image.');
            }
            $request->file('file')->move(__DIR__ . '/../../../public/spaces/images/', $request->file('file')->getClientOriginalName());

        return response()->json(['errors' => [], 'space' => Space::find($request->space_id), 'status' => 200], 200);
    }	

}


