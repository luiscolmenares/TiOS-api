<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
/**
* Get all roles.
* @param $roleParam
* return mixed
*/
public function getRoles(){
	$roles = Role::all();
	$roles = array('roles' => $roles);
	return $roles;
}

/**
* Get all permission related to the role.
* @param $roleParam
* return mixed
*/
public function getPermissions($roleParam){
	$role = Role::where('name', $roleParam)->first();
	return $this->response->array($role->perms);
}

/**
* Get all permissions
* @param 
* return mixed
*/
public function getAllPermissions(){
	 return Permission::all();
}

/**
* Add permission to a role
* @param Request request
* return mixed
*/
public function attachPermission(Request $request){
	$parameters = $request->only('permission', 'role');
	$permissionParam = $parameters['permission'];
	$roleParam = $parameters['role'];
	$role = Role::where('name', $roleParam)->first();
	$permission = Permission::where('name', $permissionParam)->first();
	$role->attachPermission($permission);

//return $role->permissions;
	return $this->response->created();

}

}