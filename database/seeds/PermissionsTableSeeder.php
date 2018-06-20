<?php
use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Organizations
        $createOrganization = new Permission();
        $createOrganization->name = 'create-organization';
        $createOrganization->display_name = 'Create Organization'; //optional'
        //allow user to...
        $createOrganization->description = 'Create new Organization';
        $createOrganization->save(); 

        $editOrganization = new Permission();
        $editOrganization->name = 'edit-organization';
        $editOrganization->display_name = 'Edit Organization'; //optional'
        //allow user to...
        $editOrganization->description = 'Edit existing Organization';
        $editOrganization->save(); 

        $editOwnOrganization = new Permission();
        $editOwnOrganization->name = 'edit-own-organization';
        $editOwnOrganization->display_name = 'Edit own Organization'; //optional'
        //allow user to...
        $editOwnOrganization->description = 'Edit existing own Organization';
        $editOwnOrganization->save(); 

        $deleteOrganization = new Permission();
        $deleteOrganization->name = 'delete-organization';
        $deleteOrganization->display_name = 'Delete Organization'; //optional'
        //allow user to...
        $deleteOrganization->description = 'Delete existing Organization';
        $deleteOrganization->save(); 

        $viewOrganization = new Permission();
        $viewOrganization->name = 'view-organization';
        $viewOrganization->display_name = 'View Organization'; //optional'
        //allow user to...
        $viewOrganization->description = 'View Organization';
        $viewOrganization->save(); 

        $viewOwnOrganization = new Permission();
        $viewOwnOrganization->name = 'view-own-organization';
        $viewOwnOrganization->display_name = 'View own Organization'; //optional'
        //allow user to...
        $viewOwnOrganization->description = 'View own Organization';
        $viewOwnOrganization->save(); 

        //Users
        $createUser = new Permission();
        $createUser->name = 'create-user';
        $createUser->display_name = 'Create User'; //optional'
        //allow user to...
        $createUser->description = 'Create new User';
        $createUser->save();

        $createOwnUser = new Permission();
        $createOwnUser->name = 'create-own-user';
        $createOwnUser->display_name = 'Create User'; //optional'
        //allow user to...
        $createOwnUser->description = 'Create new own User';
        $createOwnUser->save();  

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit User'; //optional'
        //allow user to...
        $editUser->description = 'Edit existing User';
        $editUser->save(); 

        $editOwnUser = new Permission();
        $editOwnUser->name = 'edit-own-user';
        $editOwnUser->display_name = 'Edit own User'; //optional'
        //allow user to...
        $editOwnUser->description = 'Edit existing own User';
        $editOwnUser->save(); 

        $deleteUser = new Permission();
        $deleteUser->name = 'delete-user';
        $deleteUser->display_name = 'Delete User'; //optional'
        //allow user to...
        $deleteUser->description = 'Delete existing User';
        $deleteUser->save(); 

        $deleteOwnUser = new Permission();
        $deleteOwnUser->name = 'delete-own-user';
        $deleteOwnUser->display_name = 'Delete own User'; //optional'
        //allow user to...
        $deleteOwnUser->description = 'Delete existing own User';
        $deleteOwnUser->save();

        $viewUser = new Permission();
        $viewUser->name = 'view-user';
        $viewUser->display_name = 'View User'; //optional'
        //allow user to...
        $viewUser->description = 'View existing User';
        $viewUser->save(); 

        $viewOwnUser = new Permission();
        $viewOwnUser->name = 'view-own-user';
        $viewOwnUser->display_name = 'View own User'; //optional'
        //allow user to...
        $viewOwnUser->description = 'View existing own User';
        $viewOwnUser->save();  

        //Projects
        $createProject = new Permission();
        $createProject->name = 'create-project';
        $createProject->display_name = 'Create Project'; //optional'
        //allow user to...
        $createProject->description = 'Create new Project';
        $createProject->save(); 

        $createOwnProject = new Permission();
        $createOwnProject->name = 'create-own-project';
        $createOwnProject->display_name = 'Create own Project'; //optional'
        //allow user to...
        $createOwnProject->description = 'Create new own Project';
        $createOwnProject->save(); 

        $editProject = new Permission();
        $editProject->name = 'edit-project';
        $editProject->display_name = 'Edit Project'; //optional'
        //allow user to...
        $editProject->description = 'Edit existing Project';
        $editProject->save(); 

        $editOwnProject = new Permission();
        $editOwnProject->name = 'edit-own-project';
        $editOwnProject->display_name = 'Edit own Project'; //optional'
        //allow user to...
        $editOwnProject->description = 'Edit existing own Project';
        $editOwnProject->save(); 

        $deleteProject = new Permission();
        $deleteProject->name = 'delete-project';
        $deleteProject->display_name = 'Delete Project'; //optional'
        //allow user to...
        $deleteProject->description = 'Delete existing Project';
        $deleteProject->save(); 

        $deleteOwnProject = new Permission();
        $deleteOwnProject->name = 'delete-own-project';
        $deleteOwnProject->display_name = 'Delete own Project'; //optional'
        //allow user to...
        $deleteOwnProject->description = 'Delete existing own Project';
        $deleteOwnProject->save();

        $viewProject = new Permission();
        $viewProject->name = 'view-project';
        $viewProject->display_name = 'View Project'; //optional'
        //allow user to...
        $viewProject->description = 'View existing Project';
        $viewProject->save(); 

        $viewOwnProject = new Permission();
        $viewOwnProject->name = 'view-own-project';
        $viewOwnProject->display_name = 'View own Project'; //optional'
        //allow user to...
        $viewOwnProject->description = 'View existing own Project';
        $viewOwnProject->save();  

        //Dashboards
        $createDashboard = new Permission();
        $createDashboard->name = 'create-dashboard';
        $createDashboard->display_name = 'Create Dashboard'; //optional'
        //allow user to...
        $createDashboard->description = 'Create new Dashboard';
        $createDashboard->save(); 

        $createOwnDashboard = new Permission();
        $createOwnDashboard->name = 'create-own-dashboard';
        $createOwnDashboard->display_name = 'Create own Dashboard'; //optional'
        //allow user to...
        $createOwnDashboard->description = 'Create new own Dashboard';
        $createOwnDashboard->save();

        $editDashboard = new Permission();
        $editDashboard->name = 'edit-dashboard';
        $editDashboard->display_name = 'Edit Dashboards'; //optional'
        //allow user to...
        $editDashboard->description = 'Edit existing Dashboard';
        $editDashboard->save(); 

        $editOwnDashboard = new Permission();
        $editOwnDashboard->name = 'edit-own-dashboard';
        $editOwnDashboard->display_name = 'Edit own Dashboards'; //optional'
        //allow user to...
        $editOwnDashboard->description = 'Edit existing own Dashboard';
        $editOwnDashboard->save(); 

        $deleteDashboard = new Permission();
        $deleteDashboard->name = 'delete-dashboard';
        $deleteDashboard->display_name = 'Delete Dashboards'; //optional'
        //allow user to...
        $deleteDashboard->description = 'Delete existing Dashboard';
        $deleteDashboard->save(); 

        $deleteOwnDashboard = new Permission();
        $deleteOwnDashboard->name = 'delete-own-dashboard';
        $deleteOwnDashboard->display_name = 'Delete own Dashboards'; //optional'
        //allow user to...
        $deleteOwnDashboard->description = 'Delete existing own Dashboard';
        $deleteOwnDashboard->save();

        $viewDashboard = new Permission();
        $viewDashboard->name = 'view-dashboard';
        $viewDashboard->display_name = 'View Dashboards'; //optional'
        //allow user to...
        $viewDashboard->description = 'View existing Dashboard';
        $viewDashboard->save(); 

        $viewOwnDashboard = new Permission();
        $viewOwnDashboard->name = 'view-own-dashboard';
        $viewOwnDashboard->display_name = 'View own Dashboards'; //optional'
        //allow user to...
        $viewOwnDashboard->description = 'View existing own Dashboard';
        $viewOwnDashboard->save(); 


        //Panels
        $createPanel = new Permission();
        $createPanel->name = 'create-panel';
        $createPanel->display_name = 'Create Panel'; //optional'
        //allow user to...
        $createPanel->description = 'Create new Panel';
        $createPanel->save(); 

        $createOwnPanel = new Permission();
        $createOwnPanel->name = 'create-own-panel';
        $createOwnPanel->display_name = 'Create own Panel'; //optional'
        //allow user to...
        $createOwnPanel->description = 'Create new own Panel';
        $createOwnPanel->save(); 

        $editPanel = new Permission();
        $editPanel->name = 'edit-panel';
        $editPanel->display_name = 'Edit Panel'; //optional'
        //allow user to...
        $editPanel->description = 'Edit existing Panel';
        $editPanel->save(); 

        $editOwnPanel = new Permission();
        $editOwnPanel->name = 'edit-own-panel';
        $editOwnPanel->display_name = 'Edit own Panel'; //optional'
        //allow user to...
        $editOwnPanel->description = 'Edit existing own Panel';
        $editOwnPanel->save(); 

        $deletePanel = new Permission();
        $deletePanel->name = 'delete-panel';
        $deletePanel->display_name = 'Delete Panel'; //optional'
        //allow user to...
        $deletePanel->description = 'Delete existing Panel';
        $deletePanel->save(); 

        $deleteOwnPanel = new Permission();
        $deleteOwnPanel->name = 'delete-own-panel';
        $deleteOwnPanel->display_name = 'Delete own Panel'; //optional'
        //allow user to...
        $deleteOwnPanel->description = 'Delete existing own Panel';
        $deleteOwnPanel->save(); 

        $viewPanel = new Permission();
        $viewPanel->name = 'view-panel';
        $viewPanel->display_name = 'View Panel'; //optional'
        //allow user to...
        $viewPanel->description = 'View existing Panel';
        $viewPanel->save(); 

        $viewOwnPanel = new Permission();
        $viewOwnPanel->name = 'view-own-panel';
        $viewOwnPanel->display_name = 'View own Panel'; //optional'
        //allow user to...
        $viewOwnPanel->description = 'View existing own Panel';
        $viewOwnPanel->save(); 

        //Datasources
        $createDatasource = new Permission();
        $createDatasource->name = 'create-datasource';
        $createDatasource->display_name = 'Create Datasource'; //optional'
        //allow user to...
        $createDatasource->description = 'Create new Datasource';
        $createDatasource->save(); 

        $createOwnDatasource = new Permission();
        $createOwnDatasource->name = 'create-own-datasource';
        $createOwnDatasource->display_name = 'Create own Datasource'; //optional'
        //allow user to...
        $createOwnDatasource->description = 'Create new own Datasource';
        $createOwnDatasource->save(); 

        $editDatasource = new Permission();
        $editDatasource->name = 'edit-datasource';
        $editDatasource->display_name = 'Edit Datasource'; //optional'
        //allow user to...
        $editDatasource->description = 'Edit existing Datasource';
        $editDatasource->save(); 

        $editOwnDatasource = new Permission();
        $editOwnDatasource->name = 'edit-own-datasource';
        $editOwnDatasource->display_name = 'Edit own Datasource'; //optional'
        //allow user to...
        $editOwnDatasource->description = 'Edit existing own Datasource';
        $editOwnDatasource->save(); 

        $deleteDatasource = new Permission();
        $deleteDatasource->name = 'delete-datasource';
        $deleteDatasource->display_name = 'Delete Datasource'; //optional'
        //allow user to...
        $deleteDatasource->description = 'Delete existing Datasource';
        $deleteDatasource->save(); 

        $deleteOwnDatasource = new Permission();
        $deleteOwnDatasource->name = 'delete-own-datasource';
        $deleteOwnDatasource->display_name = 'Delete own Datasource'; //optional'
        //allow user to...
        $deleteOwnDatasource->description = 'Delete existing own Datasource';
        $deleteOwnDatasource->save(); 

        $viewDatasource = new Permission();
        $viewDatasource->name = 'view-datasource';
        $viewDatasource->display_name = 'View Datasource'; //optional'
        //allow user to...
        $viewDatasource->description = 'View existing Datasource';
        $viewDatasource->save(); 

        $viewOwnDatasource = new Permission();
        $viewOwnDatasource->name = 'view-own-datasource';
        $viewOwnDatasource->display_name = 'View own Datasource'; //optional'
        //allow user to...
        $viewOwnDatasource->description = 'View existing own Datasource';
        $viewOwnDatasource->save(); 

        //Datapoints
        $createDatapoint = new Permission();
        $createDatapoint->name = 'create-datapoint';
        $createDatapoint->display_name = 'Create Datapoint'; //optional'
        //allow user to...
        $createDatapoint->description = 'Create new Datapoint';
        $createDatapoint->save(); 

        $createOwnDatapoint = new Permission();
        $createOwnDatapoint->name = 'create-own-datapoint';
        $createOwnDatapoint->display_name = 'Create own Datapoint'; //optional'
        //allow user to...
        $createOwnDatapoint->description = 'Create new own Datapoint';
        $createOwnDatapoint->save(); 

        $editDatapoint = new Permission();
        $editDatapoint->name = 'edit-datapoint';
        $editDatapoint->display_name = 'Edit Datapoint'; //optional'
        //allow user to...
        $editDatapoint->description = 'Edit existing Datapoint';
        $editDatapoint->save(); 

        $editOwnDatapoint = new Permission();
        $editOwnDatapoint->name = 'edit-own-datapoint';
        $editOwnDatapoint->display_name = 'Edit own Datapoint'; //optional'
        //allow user to...
        $editOwnDatapoint->description = 'Edit existing own Datapoint';
        $editOwnDatapoint->save(); 

        $deleteDatapoint = new Permission();
        $deleteDatapoint->name = 'delete-datapoint';
        $deleteDatapoint->display_name = 'Delete Datapoint'; //optional'
        //allow user to...
        $deleteDatapoint->description = 'Delete existing Datapoint';
        $deleteDatapoint->save(); 

        $deleteOwnDatapoint = new Permission();
        $deleteOwnDatapoint->name = 'delete-own-datapoint';
        $deleteOwnDatapoint->display_name = 'Delete own Datapoint'; //optional'
        //allow user to...
        $deleteOwnDatapoint->description = 'Delete existing own Datapoint';
        $deleteOwnDatapoint->save(); 

        $viewDatapoint = new Permission();
        $viewDatapoint->name = 'view-datapoint';
        $viewDatapoint->display_name = 'View Datapoint'; //optional'
        //allow user to...
        $viewDatapoint->description = 'View existing Datapoint';
        $viewDatapoint->save(); 

        $viewOwnDatapoint = new Permission();
        $viewOwnDatapoint->name = 'view-own-datapoint';
        $viewOwnDatapoint->display_name = 'View own Datapoint'; //optional'
        //allow user to...
        $viewOwnDatapoint->description = 'View existing own Datapoint';
        $viewOwnDatapoint->save(); 

        //Triggers
        $createTrigger = new Permission();
        $createTrigger->name = 'create-trigger';
        $createTrigger->display_name = 'Create Trigger'; //optional'
        //allow user to...
        $createTrigger->description = 'Create new Trigger';
        $createTrigger->save(); 

        $createOwnTrigger = new Permission();
        $createOwnTrigger->name = 'create-own-trigger';
        $createOwnTrigger->display_name = 'Create own Trigger'; //optional'
        //allow user to...
        $createOwnTrigger->description = 'Create new own Trigger';
        $createOwnTrigger->save(); 

        $editTrigger = new Permission();
        $editTrigger->name = 'edit-trigger';
        $editTrigger->display_name = 'Edit Trigger'; //optional'
        //allow user to...
        $editTrigger->description = 'Edit existing Trigger';
        $editTrigger->save(); 

        $editOwnTrigger = new Permission();
        $editOwnTrigger->name = 'edit-own-trigger';
        $editOwnTrigger->display_name = 'Edit own Trigger'; //optional'
        //allow user to...
        $editOwnTrigger->description = 'Edit existing own Trigger';
        $editOwnTrigger->save(); 

        $deleteTrigger = new Permission();
        $deleteTrigger->name = 'delete-trigger';
        $deleteTrigger->display_name = 'Delete Trigger'; //optional'
        //allow user to...
        $deleteTrigger->description = 'Delete existing Trigger';
        $deleteTrigger->save(); 

        $deleteOwnTrigger = new Permission();
        $deleteOwnTrigger->name = 'delete-own-trigger';
        $deleteOwnTrigger->display_name = 'Delete own Trigger'; //optional'
        //allow user to...
        $deleteOwnTrigger->description = 'Delete existing own Trigger';
        $deleteOwnTrigger->save();

        $viewTrigger = new Permission();
        $viewTrigger->name = 'view-trigger';
        $viewTrigger->display_name = 'View Trigger'; //optional'
        //allow user to...
        $viewTrigger->description = 'View existing Trigger';
        $viewTrigger->save(); 

        $viewOwnTrigger = new Permission();
        $viewOwnTrigger->name = 'view-own-trigger';
        $viewOwnTrigger->display_name = 'View own Trigger'; //optional'
        //allow user to...
        $viewOwnTrigger->description = 'View existing own Trigger';
        $viewOwnTrigger->save();  

        //Sensors
        $createSensor = new Permission();
        $createSensor->name = 'create-sensor';
        $createSensor->display_name = 'Create Sensor'; //optional'
        //allow user to...
        $createSensor->description = 'Create new Sensor';
        $createSensor->save(); 

        $createOwnSensor = new Permission();
        $createOwnSensor->name = 'create-own-sensor';
        $createOwnSensor->display_name = 'Create own Sensor'; //optional'
        //allow user to...
        $createOwnSensor->description = 'Create new own Sensor';
        $createOwnSensor->save(); 

        $editSensor = new Permission();
        $editSensor->name = 'edit-sensor';
        $editSensor->display_name = 'Edit Sensor'; //optional'
        //allow user to...
        $editSensor->description = 'Edit existing Sensor';
        $editSensor->save(); 

        $editOwnSensor = new Permission();
        $editOwnSensor->name = 'edit-own-sensor';
        $editOwnSensor->display_name = 'Edit own Sensor'; //optional'
        //allow user to...
        $editOwnSensor->description = 'Edit existing own Sensor';
        $editOwnSensor->save();

        $deleteSensor = new Permission();
        $deleteSensor->name = 'delete-sensor';
        $deleteSensor->display_name = 'Delete Sensor'; //optional'
        //allow user to...
        $deleteSensor->description = 'Delete existing Sensor';
        $deleteSensor->save(); 

        $deleteOwnSensor = new Permission();
        $deleteOwnSensor->name = 'delete-own-sensor';
        $deleteOwnSensor->display_name = 'Delete own Sensor'; //optional'
        //allow user to...
        $deleteOwnSensor->description = 'Delete existing own Sensor';
        $deleteOwnSensor->save();

        $viewSensor = new Permission();
        $viewSensor->name = 'view-sensor';
        $viewSensor->display_name = 'View Sensor'; //optional'
        //allow user to...
        $viewSensor->description = 'View existing Sensor';
        $viewSensor->save(); 

        $viewOwnSensor = new Permission();
        $viewOwnSensor->name = 'view-own-sensor';
        $viewOwnSensor->display_name = 'View own Sensor'; //optional'
        //allow user to...
        $viewOwnSensor->description = 'View existing own Sensor';
        $viewOwnSensor->save();
    }
}
