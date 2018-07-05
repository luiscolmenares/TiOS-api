<?php

namespace App\Http\Controllers;

use App\Space;
use App\Project;
use App\Organization;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SpaceController extends Controller
{
    /**
     * Get all spaces
     * @param 
     * return spaces
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
	* Get Space from Space Id
	* @param spaceId
	* return Space
	*/
	public function getSpace($spaceId){
	$space = Space::find($spaceId);
	// $space_datapoints = $this->getSpacesDatapoints;
	// $space_datasources = $this->getSpacesDatasources;


	//return $user;
	$complete_space = array(
	'id' => $space->id,
	'name' => $space->name,
	'image' => $space->image,
	'organization_id' => $space->organization_id,
	
	);

	return $complete_space;
	}

	/**
     * Get all datasources related to the project Count.
     * @param $projectParam
     * return int
     */
    public function getProjectSpacesCount($projectId) {
        return  Space::where('project_id', $projectId)->count(); 
    }


	/**
	* Get Space from Space Id
	* @param spaceId
	* return Space
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
	* Get Space from Space Id
	* @param spaceId
	* return Space
	*/
	public function getSpacesByOrganizationId($projectId){
	$spaces = Space::where('organization_id', $projectId)->get();
	return $spaces;
	}		

}
