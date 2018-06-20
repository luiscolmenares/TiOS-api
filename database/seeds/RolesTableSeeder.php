<?php
use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Super Admin User
    	$admin = new Role();
    	$admin->name = 'super';
    	$admin->display_name = 'Super Admin User'; //Optional
    	$admin->description = 'User is the admin of the system';
    	$admin->save();

    	//Admin User
    	$admin = new Role();
    	$admin->name = 'admin';
    	$admin->display_name = 'Admin User'; //Optional
    	$admin->description = 'User is the admin of a given project';
    	$admin->save();

    	//Project Owner User
    	$owner = new Role();
    	$owner->name = 'owner';
    	$owner->display_name = 'Project Owner'; //Optional
    	$owner->description = 'User is the owner of a given project';
    	$owner->save();

    	//Project Member
    	$member = new Role();
    	$member->name = 'member';
    	$member->display_name = 'Project Member'; //Optional
    	$member->description = 'User is a member of a given project';
    	$member->save();       
    }
}
