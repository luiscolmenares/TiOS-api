<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use App\Project;
use App\Dashboard;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Geocoder\Laravel\Facades\Geocoder;


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
* @SWG\Get(
*      path="/organizations",
*      operationId="getOrganizations",
*      tags={"Organizations"},
*      summary="Get list of organizations",
*      description="Returns list of organizations",
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
    public function getOrganizations() {
        //$organizations = Organization::withTrashed()->get();
        $organizations = Organization::all();
        $organizations = array('organizations' => $organizations);
        return $organizations;
    }

    /**
* @SWG\Get(
*      path="/organizations/count",
*      operationId="getTotalOrganizationsCount",
*      tags={"Organizations"},
*      summary="Get count of organizations",
*      description="Returns count of organizations",
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
* Returns count of organizations
*/
public function getTotalOrganizationsCount(){
    return Organization::all()->count();
}

    /**
* @SWG\Get(
*      path="/organization/{id}",
*      operationId="getOrganization",
*      tags={"Organizations"},
*      summary="Get organization information",
*      description="Returns organization data",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
* @SWG\Get(
*      path="/organization/{organization_id}/users",
*      operationId="getOrgUsers",
*      tags={"Users"},
*      summary="Get organization's users information",
*      description="Returns organization's users data",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgUsers($organization_id) {
        $users = User::where('organization_id', $organization_id)->get();
        $users = array("users" => $users);
        return $users;

    }

    /**
* @SWG\Get(
*      path="/organization/{organization_id}/users/count",
*      operationId="getOrgUsersCount",
*      tags={"Users"},
*      summary="Get organization's users count",
*      description="Returns organization's users count",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgUsersCount($organizationParam) {
         $userscount = User::where('organization_id', $organizationParam)->count();
        // return $organization->users->count();
        return $userscount;
    }

    /**
* @SWG\Get(
*      path="/organization/{organization_id}/projects",
*      operationId="getOrgProjects",
*      tags={"Projects"},
*      summary="Get organization's projects",
*      description="Returns organization's project",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgProjects($organization_id) {
        //$organization = Organization::where('id', $organization_id)->first();
        $projects_list = array();
        $projects = Project::where('organization_id', $organization_id)->get();
        //return $this->response->array($organization->projects);
        foreach ($projects  as $project) {
            # code...
            //$project = Project::find($project->id);
            //$dashboards = Dashboard::where('project_id', $project->id)->get();
            $dashboards = Dashboard::with('panels')->where('project_id', '=', $project->id)->get();
            if($project->address_1){
                 $address = $project->address_1." ".$project->address_2." ".$project->city." ".$project->state.", ".$project->zip;
                // $geo = json_decode(app('geocoder')->geocode($address)->toJson());
                 $geo = json_decode(app('geocoder')->geocode('Los Angeles, CA')->tojson());

             } else {
                $geo = '';
             }
            

            $complete_project = array(
                'id' => $project->id,
                'name' => $project->name,
                'notes' => $project->notes,
                'active' => $project->active,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'deleted_at' => $project->deleted_at,
                'organization_id' => $project->organization_id,
                'address_1' => $project->address_1,
                'address_2' => $project->address_2,
                'geolocation' => $geo,
                'city' => $project->city,
                'state' => $project->state,
                'zip' => $project->zip,
                'photo' => $project->photo,
                'website' => $project->website,
                'dashboards' => $dashboards,

                );
            array_push($projects_list, $complete_project);

        }
        $projects_list = array("projects" => $projects_list);
        return $projects_list;
    }


public function getOrgProjectsWithDashboards($organization_id) {
        //$organization = Organization::where('id', $organization_id)->first();
        $projects_list = array();
        $projects = Project::where('organization_id', $organization_id)->get();
        //return $this->response->array($organization->projects);
        foreach ($projects  as $project) {
            # code...   
            //$project = Project::find($project->id);
            //$dashboards = Dashboard::where('project_id', $project->id)->get();
            $dashboardscount = Dashboard::with('panels')->where('project_id', '=', $project->id)->count();
            if ($dashboardscount == 0 ){
                return $projects_list;
            } else {

                $dashboards = Dashboard::with('panels')->where('project_id', '=', $project->id)->get();
            if($project->address_1){
                 $address = $project->address_1." ".$project->address_2." ".$project->city." ".$project->state.", ".$project->zip;
                 $geo = json_decode(Geocoder::geocode($address)->toJson());

             } else {
                $geo = '';
             }
            

            $complete_project = array(
                'id' => $project->id,
                'name' => $project->name,
                'notes' => $project->notes,
                'active' => $project->active,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
                'deleted_at' => $project->deleted_at,
                'organization_id' => $project->organization_id,
                'address_1' => $project->address_1,
                'address_2' => $project->address_2,
                'geolocation' => $geo,
                'city' => $project->city,
                'state' => $project->state,
                'zip' => $project->zip,
                'photo' => $project->photo,
                'website' => $project->website,
                'dashboards' => $dashboards,

                );
            array_push($projects_list, $complete_project);

        }
        $projects_list = array("projects" => $projects_list);
        return $projects_list;

            }
            
    }

    /**
* @SWG\Get(
*      path="/organizations/projects/dashboards",
*      operationId="getOrgProjectsDasboards",
*      tags={"Dashboards"},
*      summary="Get organization's project dashboards",
*      description="Returns organization's project dashboards",
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

    public function getOrgProjectsDasboards(){
        $organizations = Organization::all();
        $org_list = array();
        foreach ($organizations as $organization) {
            $org_projects = $this->getOrgProjectsWithDashboards($organization->id);
            if(count($org_projects) > 0){
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
           
        }
        $org_list = array("organizations" => $org_list);

        return $org_list;
    }

    /**
* @SWG\Get(
*      path="/organization/{organization_id}/projects/count",
*      operationId="getOrgProjectsCount",
*      tags={"Projects"},
*      summary="Get organization's projects count",
*      description="Returns organization's projects count",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgProjectsCount($organization_id) {
        $projectscount = Project::where('organization_id', $organization_id)->count();
        // return $organization->users->count();
        return $projectscount;
    }

   /**
* @SWG\Get(
*      path="/organization/{organization_id}/dashboards/count",
*      operationId="getOrgDashboardsCount",
*      tags={"Dashboards"},
*      summary="Get organization's dashboards count",
*      description="Returns organization's dashboards count",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgDashboardsCount($organization_id) {
        $dashboardscounts = \DB::table('projects')
            ->where('projects.organization_id', '=', $organization_id)
            ->join('dashboards', 'dashboards.project_id', '=', 'projects.id')
            //->select('dashboards.name')
            ->count();
        return $dashboardscounts;
        
    }
    /**
* @SWG\Get(
*      path="/organization/{organization_id}/triggers/count",
*      operationId="getOrgTriggersCount",
*      tags={"Triggers"},
*      summary="Get organization's triggers count",
*      description="Returns organization's dashboards count",
*      @SWG\Parameter(
*          name="id",
*          description="organization id",
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
    public function getOrgTriggersCount($organization_id) {
        $triggerscount = \DB::table('triggers')
            ->where('triggers.deleted_at', '=', null)
             ->join('projects', 'triggers.project_id', '=', 'projects.id')
             ->join('organizations', 'projects.organization_id', '=', 'organizations.id')
             ->where('organizations.id', '=', $organization_id)
            ->count();
        return $triggerscount;
        
    }
}
