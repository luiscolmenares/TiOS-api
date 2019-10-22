<?php

namespace App\Http\Controllers;

use App\Project;
use App\Organization;
use App\Dashboard;
use App\Space;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Validator;
use Illuminate\Routing\UrlGenerator;

class ProjectController extends Controller
{
/**
* @SWG\Get(
*      path="/projects",
*      operationId="getProjects",
*      tags={"Projects"},
*      summary="Get list of projects",
*      description="Returns list of projects",
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
* Returns list of projects
*/
public function getProjects(){
//2$projects = Project::withTrashed()->get();
// $projects = Project::all();
// $projects = array('projects' => $projects);
// return $projects;


    $projects = \DB::table('projects')
    ->where('projects.deleted_at', '=', null)
    ->join('organizations', 'organizations.id', '=','projects.organization_id')
    ->select('projects.*', 'organizations.name as organization_name')
    ->orderBy('organization_name', 'desc')->get();

    $projects = array("projects" => $projects);
    return $projects;
}

/**
* @SWG\Get(
*      path="/project/{id}",
*      operationId="getProject",
*      tags={"Projects"},
*      summary="Get project information",
*      description="Returns project data",
*      @SWG\Parameter(
*          name="id",
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
public function getProject($projectId){
    $project = Project::find($projectId);
    $project = array("project" => $project);
    return $project;
}

/**
* @SWG\Get(
*      path="/projects/count",
*      operationId="getTotalProjectsCount",
*      tags={"Projects"},
*      summary="Get count of projects",
*      description="Returns count of projects",
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
* Returns list of organizations
*/
public function getTotalProjectsCount(){
    return Project::all()->count();
}

/**
* @SWG\Get(
*      path="/project/{project_id}/organization",
*      operationId="getOrganizationByProjectId",
*      tags={"Organizations"},
*      summary="Get Organization information by Project ID",
*      description="Returns Organization data by Project ID",
*      @SWG\Parameter(
*          name="id",
*          description="project id",
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
public function getOrganizationByProjectId($projectId){

    $project = Project::find($projectId);
    $organization = Organization::find($project->organization_id);
    $organization = array("organization" => $organization);
    return $organization;

}

/**
* Creates a project
* @param Request $request
* return project
*/
public function createProject(Request $request)
{
    $project = new Project($request->all());
    if (!$project->save()) {
        abort(500, 'Could not save project.');
    }
    return $project;
}

/**
* Updates a project
* @param Request $request, ProjectId
* return project
*/
public function updateProject(Request $request, $projectId){
    $project = Project::find($projectId);
    if($request->name){$project->name = $request->name;}
    if($request->notes){$project->notes = $request->notes;}
    if($request->active == 0){$project->active = 0;}
    if($request->active == 1){$project->active = 1;}
    if($request->organization_id){$project->organization_id = $request->organization_id;}
    if($request->address_1){$project->address_1 = $request->address_1;}
    if($request->address_2){$project->address_2 = $request->address_2;}
    if($request->city){$project->city = $request->city;}
    if($request->state){$project->state = $request->state;}
    if($request->zip){$project->zip = $request->zip;}
    if($request->photo){$project->photo = $request->photo;}
    if($request->website){$project->website = $request->website;}
    if($request->image){$project->image = $request->image;}

    
    if (!$project->save()) {
        abort(500, 'Could not update project.');
    }
    return $project;
}

/**
* @SWG\Get(
*      path="/project/{project_id}/users",
*      operationId="getProjectUsers",
*      tags={"Users"},
*      summary="Get users by Project ID",
*      description="Returns list of users by Project ID",
*      @SWG\Parameter(
*          name="id",
*          description="project id",
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

public function getProjectUsers($projectId){

    $users = \DB::table('project_user')
    ->where('project_user.project_id', '=', $projectId)
    ->join('users', 'users.id', '=', 'project_user.user_id')
    ->select('users.*')->get();

    $users = array("users" => $users);
    return $users;
}

/**
* @SWG\Get(
*      path="/project/{project_id}/users/count",
*      operationId="getProjectUsersCount",
*      tags={"Users"},
*      summary="Get Users count by Project",
*      description="Returns Users count by project id",
*      @SWG\Parameter(
*          name="project_id",
*          description="project id",
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

public function getProjectUsersCount($projectId){
    $project = Project::where('id', $projectId)->first();
    return $project->users->count();
}

/**
* @SWG\Get(
*      path="/project/{project_id}/dashboards/count",
*      operationId="getProjectDashboardsCount",
*      tags={"Dashboards"},
*      summary="Get Dashboards count by Project",
*      description="Returns Dashboards count by project id",
*      @SWG\Parameter(
*          name="project_id",
*          description="project id",
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

public function getProjectDashboardsCount($projectId){
    return Dashboard::where('project_id', '=', $projectId)->count();
}

/**
* @SWG\Get(
*      path="/project/{project_id}/dashboards",
*      operationId="getDashboardByProjectId",
*      tags={"Dashboards"},
*      summary="Get Dashboards by Project ID",
*      description="Returns Dashboards data by project id",
*      @SWG\Parameter(
*          name="project_id",
*          description="project id",
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

public function getDashboardByProjectId($projectId){
    $dashboards = Dashboard::with('panels')->where('project_id', '=', $projectId)->get();
    $dashboards = array("dashboards" => $dashboards);
    return $dashboards;
}

/**
* Deletes a Project
* @param $projectId
* return boolean
*/
public function deleteProject($projectId){
    $project = Project::find($projectId);
    $project->delete();
}




/**
* Add Datasource to a Project
* @param Request request
* return mixed
*/
public function attachDatasourceProject($datasourceId, $projectId){
    $datasource = Datasource::where('id', $datasourceId)->first();
    $project = Project::where('id', $projectId)->first();
    $project->Datasources()->attach($datasourceId);
    return $project;
}

/**
     * Upload new File
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request, $projectId)
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

         $projects = Project::find($projectId);
            //$sportevent = Sportevent::find($re);
            $projects->image = $request->file('file')->getClientOriginalName();
           // $sportevent->logo = 'imagen33.png';
            if (!$projects->save()) {
            abort(500, 'Could not update projects image.');
            }
            $request->file('file')->move(__DIR__ . '/../../../public/projects/images/', $request->file('file')->getClientOriginalName());

        return response()->json(['errors' => [], 'projects' => Project::find($request->projectId), 'status' => 200], 200);
    }   
}