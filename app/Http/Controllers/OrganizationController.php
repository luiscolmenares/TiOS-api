<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use App\Project;
use App\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller {

    /**
     * Creates an organization
     * @param Request $request
     * return organization
     */
    public function createOrganization(Request $request) {
        $organization = new Organization($request->all());
        if (!$organization->save()) {
            abort(500, 'Could not save organization.');
        }
        return $organization;
    }

    /**
     * Deletes an organization
     * @param organizationId
     * return boolean
     */
    public function deleteOrganization($organizationId) {

        return Organization::destroy($organizationId);
    }

    /**
     * Gets anlorganizations
     * @param organizationId
     * return organizations
     */
    public function getOrganizations() {
        //$organizations = Organization::withTrashed()->get();
        $organizations = Organization::all();
        $organizations = array('organizations' => $organizations);
        return $organizations;
    }

    /**
* Get org total count
* @param 
* return count int
*/
public function getTotalOrganizationsCount(){
    return Organization::all()->count();
}

    /**
     * Gets an organization by Id
     * @param organizationId
     * return organization
     */
    public function getOrganization($organizationId) {
        $organization = Organization::find($organizationId);
        $organization = array("organization" => $organization);
        return $organization;
    }

    /**
     * Updates an organization
     * @param Request $request, organizationId
     * return organization
     */
    public function updateOrganization(Request $request, $organizationId) {
        $organization = Organization::find($organizationId);
        if ($request->name) {
            $organization->name = $request->name;
        }
        if ($request->email) {
            $organization->phone = $request->email;
        }
        if ($request->address) {
            $organization->address = $request->address;
        }
        if ($request->address2) {
            $organization->address2 = $request->address2;
        }
        if ($request->phone) {
            $organization->phone = $request->phone;
        }
        if ($request->notes) {
            $organization->notes = $request->notes;
        }
        if ($request->active == 0) {
            $organization->active = 0;
        }
        if ($request->active == 1) {
            $organization->active = 1;
        }

        if (!$organization->save()) {
            abort(500, 'Could not update organization.');
        }
        return $organization;
    }

    /**
     * Add Project to a organization
     * @param Request request
     * return mixed
     */
    public function attachProjectOrganization($organizationId, $projectId) {
        $organization = Organization::where('id', $organizationId)->first();
        $Project = Project::where('id', $projectId)->first();
        $organization->Projects()->attach($projectId);
        return $organization;
    }

    /**
     * Get all users related to the organization.
     * @param $organizationParam
     * return mixed
     */
    public function getOrgUsers($organizationParam) {
        $users = User::where('organization_id', $organizationParam)->get();
        $users = array("users" => $users);
        return $users;

    }

    /**
     * Get all users related to the organization Count.
     * @param $organizationParam
     * return int
     */
    public function getOrgUsersCount($organizationParam) {
         $userscount = User::where('organization_id', $organizationParam)->count();
        // return $organization->users->count();
        return $userscount;
    }

    /**
     * Get all projects related to the organization.
     * @param $organizationParam
     * return mixed
     */
    public function getOrgProjects($organizationParam) {
        //$organization = Organization::where('id', $organizationParam)->first();
        $projects_list = array();
        $projects = Project::where('organization_id', $organizationParam)->get();
        //return $this->response->array($organization->projects);
        foreach ($projects  as $project) {
            # code...
            //$project = Project::find($project->id);
            //$dashboards = Dashboard::where('project_id', $project->id)->get();
            $dashboards = Dashboard::with('panels')->where('project_id', '=', $project->id)->get();

            $complete_project = array(
                'id' => $project->id,
                'name' => $project->name,
                'notes' => $project->notes,
                'active' => $project->active,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'deleted_at' => $project->deleted_at,
                'organization_id' => $project->organization_id,
                'dashboards' => $dashboards,

                );
            array_push($projects_list, $complete_project);

        }
        $projects_list = array("projects" => $projects_list);
        return $projects_list;
    }

    public function getOrgProjectsDasboards(){
        $organizations = Organization::all();
        $org_list = array();
        foreach ($organizations as $organization) {
            $org_projects = $this->getOrgProjects($organization->id);
            $complete_org = array(
                'id' => $organization->id,
                'name' => $organization->name,
                'address' => $organization->address,
                'address2' => $organization->address2,
                'phone' => $organization->phone,
                'notes' => $organization->notes,
                'active' => $organization->active,
                'projects' => $org_projects,
                );
            array_push($org_list, $complete_org);
        }
        $org_list = array("organizations" => $org_list);

        return $org_list;
    }

    /**
     * Get all projects related to the organization count.
     * @param $organizationParam
     * return mixed
     */
    public function getOrgProjectsCount($organizationParam) {
        $projectscount = Project::where('organization_id', $organizationParam)->count();
        // return $organization->users->count();
        return $projectscount;
    }

    /**
     * Get all dashboards related to the organization count.
     * @param $organizationParam
     * return mixed
     */
    public function getOrgDashboardsCount($organizationParam) {
        $dashboardscounts = \DB::table('projects')
            ->where('projects.organization_id', '=', $organizationParam)
            ->join('dashboards', 'dashboards.project_id', '=', 'projects.id')
            //->select('dashboards.name')
            ->count();
        return $dashboardscounts;
        
    }
    /**
     * Get all triggers related to the organization count.
     * @param $organizationParam
     * return mixed
     */
    public function getOrgTriggersCount($organizationParam) {
        $triggerscount = \DB::table('triggers')
            ->where('triggers.deleted_at', '=', null)
             ->join('projects', 'triggers.project_id', '=', 'projects.id')
             ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
             ->where('organizations.id', '=', $organizationParam)
            ->count();
        return $triggerscount;
        
    }
}
