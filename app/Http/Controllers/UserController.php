<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Organization;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function __construct(){
    //     $this->middleware('api.auth');
    // }
    
/**
* Get all users
* @param 
* return users
*/
public function index(){
    return User::withTrashed()->get();
}

/**
* Get current user
* @param 
* return users
*/
public function getLoggedUser($request){
    return $request->user();
}

/**
    * @SWG\Get(
    *      path="/users",
    *      operationId="getUsers",
    *      tags={"Users"},
    *      summary="Get list of users",
    *      description="Returns list of users",
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
    * Returns list of dashboards
    */
public function getUsers(){
    //$users =  User::withTrashed()->get();
    $users =  User::all();
    //$users = array("users" => $users);
     $users_list = array();
    foreach ($users as $u) {
         $user = User::find($u->id);
         $user_roleid = $u->role_id;
         $user_organization_id = $u->organization_id;
         $user_role = Role::find($user_roleid);
         $user_organization = Organization::find($user_organization_id);
         //$user_permissions = $this->getPermissionRoleNameList($user_roleid);

        // //return $user;
         $complete_user = array(
        //     //'user' => array(
        //         //$user,
        //         'id' => $user->id,
                    'id' => $u->id,
                    'phone' => $u->phone,
                    'name' => $u->name,
                    'email' => $u->email,
                    'active' => $u->active,
                    'created_at' => $u->created_at,
                    'updated_at' => $u->updated_at,
                    'deleted_at' => $u->deleted_at,
                    'notes' => $u->notes,
                    'role_id' => $u->role_id,
                    'role_name' => $user_role->name,
                    'role_description' => $user_role->description,
                    'organization_id' => $u->organization_id,
                    'organization_name' => $user_organization->name,
                    'active_sms' => $u->active_sms,
                    'active_email' => $u->active_email,
                    'active_email' => $u->active_push,
                    //'permissions' => $user_permissions,
        //        // ),
            
         );
         array_push($users_list, $complete_user);
    }

     $content = array('users' => $users_list);

    return $content;

}


/*
* This method will validate a user based on the access token.
* @return mixed
**/
public function validateUser()
{
    $user = app('Dingo\Api\Auth\Auth')->user();

    if(!$user) {
        $responseArray = [
        'message' => 'Not authorized. Please login again',
        'status' => false
        ];

        return $this->response->array($responseArray)->setStatusCode(403);
    }
    else {
        $responseArray = [
        'message' => 'User is authorized',
        'status' => true
        ];

        return $this->response->array($responseArray)->setStatusCode(200);
    }
}
/**
* Get role from a User
* @param $userId
* return user Role
*/
public function getUserRole($userId){
    return User::find($userId)->roles;
}
/**
* @SWG\Get(
*      path="/user/{user_id}",
*      operationId="getUser",
*      tags={"Users"},
*      summary="Get User by ID",
*      description="Returns User",
*      @SWG\Parameter(
*          name="user_id",
*          description="Userid",
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
public function getUser($userId){
    $user = User::find($userId);
    $user_roleid = $user->role_id;
    $user_organization_id = $user->organization_id;
    $user_role = Role::find($user_roleid);
    $user_organization = Organization::find($user_organization_id);
    $user_permissions = $this->getPermissionRoleNameList($user_roleid);
    $user_projects = \DB::table('project_user')
    ->where('project_user.user_id', '=', $userId)
    ->select('project_user.project_id')
    ->get();
    $user_projects_list = array();
    foreach ($user_projects as $user_project) {
        $project = Project::find($user_project->project_id);
        array_push($user_projects_list, $project);

    }
    $complete_user = array(
        'user' => array(
            'id' => $user->id,
            'phone' => $user->phone,
            'name' => $user->name,
            'email' => $user->email,
            'active' => $user->active,
            'active_sms' => $user->active_sms,
            'active_email' => $user->active_email,
            'active_push' => $user->active_push,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => $user->deleted_at,
            'notes' => $user->notes,
            'role_id' => $user->role_id,
            'role_name' => $user_role->name,
            'role_description' => $user_role->description,
            'organization_id' => $user->organization_id,
            'projects' => $user_projects[0],
            'project_list' => $user_projects_list,
            'organization_name' => $user_organization->name,
            'permissions' => $user_permissions

            ),
        
    );
    //array('triggers' => $triggers);
    
    
    return $complete_user;
}

/**
* get User by Username
* @param username
* return user
*/
public function getUserByUsername ($username){

    $user = User::where('email', $username)->first();

    return $this->getUser($user->id);


}

/**
* Delete User from user Id
* @param userId
* return boolean
*/
public function deleteUser($userId){

    $user = User::find($userId);

    $user->delete();

}
/**
* Add Role to a user (deprecated)
* @param userId role
* return user
*/
public function attachUserRole($userId, $role){
    $user = User::find($userId);
    $roleId = Role::where('name', $role)->first();
    $user->roles()->attach($roleId);
    return $user;
}
/**
* Create user
* @param Request request
* return User
*/
public function createUser(Request $request)
{
    $hash_pass = hash::make($request->password);
    $user = new User($request->all());
    $user->password = $hash_pass;
    if (!$user->save()) {
        abort(500, 'Could not save user.');
    }
    return $user;
}
/**
* Update User
* @param Request userId
* return User
*/
public function updateUser(Request $request, $userId){
    $user = User::find($userId);

    if(isset($request->password)){$user->password = hash::make($request->password);}
    if($request->name){$user->name = $request->name;}
    if($request->phone){$user->phone = $request->phone;}
    if($request->notes){$user->notes = $request->notes;}
    if(isset($request->active) && $request->active == 0){$user->active = 0;}
    if(isset($request->active) && $request->active == 1){$user->active = 1;}
    if(isset($request->active_sms) && $request->active_sms == 0){$user->active_sms = 0;}
    if(isset($request->active_sms) && $request->active_sms == 1){$user->active_sms = 1;}
    if(isset($request->active_email) && $request->active_email == 0){$user->active_email = 0;}
    if(isset($request->active_email) && $request->active_email == 1){$user->active_email = 1;}
    if(isset($request->active_push) && $request->active_push == 0){$user->active_push = 0;}
    if(isset($request->active_push) && $request->active_push == 1){$user->active_push = 1;}
    if($request->role_id){$user->role_id = $request->role_id;}
    if($request->organization_id){$user->organization_id = $request->organization_id;}

    if (!$user->save()) {

        abort(500, 'could not update user.');

    }
    return $user;
}


public function changePassword(Request $request, $userId ){
    $user = User::find($userId);

    if (Hash::check($request->oldpassword, $user->password)) {
    //return "The passwords match...";
        if(isset($request->newpassword)){
            $user->password = hash::make($request->newpassword);
            if (!$user->save()) {

                abort(500, 'could not update user.');

            }
        } else {
            return 'false';
        }

        
    return 'true';


} else {

    return 'false';
}
}

public function deviceToken(Request $request){
    $data = \DB::table('users_device_tokens')->insert(
    ['user_id' => $request->user_id, 
    'device_token' => $request->device_token,
    'created_at' =>  \Carbon\Carbon::now(), # \Datetime()
    'updated_at' => \Carbon\Carbon::now()]  # \Datetime()
);
    if (!$data) {
            abort(500, 'Could not save device token.');
        }
        else {
            return array(true, "Device token saved.");
        }
}

/**
* Add user to a project
* @param userId Request 
* return project
*/
public function attachUserProject($userId, $projectId){
    $project = Project::where('id', $projectId)->first();
    $user = User::where('id', $userId)->first();
    $project->users()->attach($userId);
    return $project;
}
/**
* Get role from user
* @param userId
* return role
*/
public function getRoleUser($userId){
    $user = User::find($userId);
    $roleId = $user->role_id;
    $role = Role::find($roleId);
    $role = array("role" => $role);
    return $role;
}

/**
* Get permissions from role
* @param roleId
* return permissions array
*/
public function getPermissionRoleNameList($roleId){
    $permissions = \DB::table('permission_role')
    ->where('permission_role.role_id', '=', $roleId)
    ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
    //->select('permission_role.*', 'permissions.description', 'permissions.name as permission_name', 'permissions.display_name as permissions_display_name')
    ->select('permissions.name')
    ->get();
    $permissions_list = array();
    foreach ($permissions as $permission) {
        array_push($permissions_list, $permission->name);
    }
    return $permissions_list;
}
/**
* Get Organization from user 
* @param userId
* return mixed
*/
public function passwordReset($email){
    // To be completed
    $array = array(
        'email' => $email,
        'reset' => true
        
    );
    return $array;
}

/**
* Get Organization from user 
* @param userId
* return mixed
*/
public function getOrganizationUser($userId){
    $user = User::find($userId);
    $organizationId = $user->organization_id;
    $organization = Organization::find($organizationId);
    $organization = array("organization" => $organization);
    return $organization;
}

/**
* Get user total count
* @param 
* return count int
*/
public function getTotalUsersCount(){
    return User::all()->count();
}

/**
* Add user to a organization
* @param Request request
* return organization
*/
public function attachUserOrganization($userId, $organizationId){
    $organization = Organization::where('id', $organizationId)->first();
    $user = User::where('id', $userId)->first();
    $organization->users()->attach($userId);
    return $organization;
}

/**
* remove user form
* @param Request request
* return true
*/
 public function removeUserProject($userId, $projectId){
    $data = \DB::table('project_user')
                ->where([
                        ['user_id' , '=', $userId],
                        ['project_id', '=', $projectId]
                        ])
                ->delete();
    $response = array(
        'message' => 'User removed from project');
    return $response;
 }


}
