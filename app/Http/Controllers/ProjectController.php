<?php

namespace App\Http\Controllers;

use App\Project;
use App\Organization;
use App\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProjectController extends Controller
{
/**
* Get all projects.
* @param 
* return projects
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
* Get Project by projectId
* @param $projectId
* return project
*/
public function getProject($projectId){
    $project = Project::find($projectId);
    $project = array("project" => $project);
    return $project;
}

/**
* Get project total count
* @param 
* return count int
*/
public function getTotalProjectsCount(){
    return Project::all()->count();
}

/**
* Get Organizations by projectId
* @param $projectId
* return organization
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

    if (!$project->save()) {
        abort(500, 'Could not update project.');
    }
    return $project;
}

/**
* Get all users related to the project.
* @param $projectParam (id)
* return users
*/

public function getProjectUsers($projectParam){

      $users = \DB::table('project_user')
            ->where('project_user.project_id', '=', $projectParam)
            ->join('users', 'users.id', '=', 'project_user.user_id')
            ->select('users.*')->get();

        $users = array("users" => $users);
        return $users;
}

/**
* Get all users count related to the project.
* @param $organizationParam
* return mixed
*/

public function getProjectUsersCount($projectParam){
    $project = Project::where('id', $projectParam)->first();
    return $project->users->count();
}

/**
* Get  dashboards count related to the project
* @param $projectParam
* return count (int)
*/

public function getProjectDashboardsCount($projectParam){
    return Dashboard::where('project_id', '=', $projectParam)->count();
}

/**
* Get all users related to the organization.
* @param $organizationParam
* return mixed
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
}