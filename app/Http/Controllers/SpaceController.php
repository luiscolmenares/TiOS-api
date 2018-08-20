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
        return Space::all();
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
	$datasources = app('App\Http\Controllers\DatasourceController')->GetDatasourcesBySpaceId($spaceId);

	$complete_space = array(
	'id' => $space->id,
	'name' => $space->name,
	'image' => $space->image,
	'image_url' => $url.'/spaces/images/'.$space->image,
	'organization_id' => $space->organization_id,
	'project_id' => $space->project_id,
	'datasources' => $datasources,
	
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
	$spaces = Space::where('project_id', $projectId)->get();
	$project = Project:: find($projectId);
	$organization_id = $project->organization_id;
	$organization = Organization::find($organization_id);
	$spaces_list = array();
        foreach ($spaces as $space) {
            $s = array(
                    'id' => $space->id,
                    'name' => $space->name,
                    'organization_id' => $space->organization_id,
                    'project_id' => $space->project_id,
                    'image' => $space->image,
                    'organization_name' => $organization->name,
                    'project_name' => $project->name,
                    
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


