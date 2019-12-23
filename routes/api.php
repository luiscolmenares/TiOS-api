<?php

use Illuminate\Http\Request;
//SOLVE CORS ISSUE
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Permissions and roles
Route::get('permissions', 'RoleController@getAllPermissions');
Route::middleware('auth:api')->get('roles', 'RoleController@getRoles');
Route::middleware('auth:api')->get('role/{roleParam}/permissions', 'RoleController@getPermissions');
Route::middleware('auth:api')->post('role/permission/add', 'RoleController@attachPermission');

//Users
Route::middleware('auth:api')->get('users', 'UserController@getUsers');
Route::middleware('auth:api')->get('getloggeduser', 'UserController@getLoggedUser');
Route::middleware('auth:api')->get('users/{user_id}/roles/{role_name}', 'UserController@attachUserRole');
Route::middleware('auth:api')->get('user/{user_id}', 'UserController@getUser');
Route::middleware('auth:api')->get('user/username/{username}', 'UserController@getUserByUsername');
Route::middleware('auth:api')->post('user/create', 'UserController@createUser');
Route::middleware('auth:api')->post('user/edit/{user_id}', 'UserController@updateUser');
Route::middleware('auth:api')->delete('user/delete/{user_id}', 'UserController@deleteUser');
Route::middleware('auth:api')->get('user/{user_id}/project/{project_id}', 'UserController@attachUserProject');
Route::middleware('auth:api')->get('user/{user_id}/role', 'UserController@getRoleUser');
Route::middleware('auth:api')->get('user/{user_id}/organization', 'UserController@getOrganizationUser');
Route::middleware('auth:api')->get('users/count', 'UserController@getTotalUsersCount');
Route::middleware('auth:api')->get('user/{user_id}/organization/{organization_id}', 'UserController@attachUserOrganization');
Route::middleware('auth:api')->get('remove/user/{user_id}/project/{project_id}', 'UserController@removeUserProject');
Route::middleware('auth:api')->get('user/password/reset/{user_email}', 'UserController@passwordReset');
Route::middleware('auth:api')->post('user/password/change/{user_id}', 'UserController@changePassword');
Route::middleware('auth:api')->post('user/devicetoken', 'UserController@deviceToken');



//Organizations
Route::middleware('auth:api')->delete('organization/delete/{organization_id}', 'OrganizationController@deleteOrganization');
Route::middleware('auth:api')->get('organizations', 'OrganizationController@getOrganizations');
Route::middleware('auth:api')->get('organizations/count', 'OrganizationController@getTotalOrganizationsCount');
Route::middleware('auth:api')->get('organization/{organization_id}', 'OrganizationController@getOrganization');
Route::middleware('auth:api')->post('organization/create', 'OrganizationController@createOrganization');
Route::middleware('auth:api')->post('organization/edit/{organization_id}', 'OrganizationController@updateOrganization');	
Route::middleware('auth:api')->get('organization/{organization_id}/project/{project_id}', 'OrganizationController@attachProjectOrganization');
Route::middleware('auth:api')->get('organization/{organization_id}/users', 'OrganizationController@getOrgUsers');
Route::middleware('auth:api')->get('organization/{organization_id}/users/count', 'OrganizationController@getOrgUsersCount');
Route::middleware('auth:api')->get('organization/{organization_id}/projects', 'OrganizationController@getOrgProjects');
Route::middleware('auth:api')->get('organization/{organization_id}/projects/count', 'OrganizationController@getOrgProjectsCount');
Route::middleware('auth:api')->get('organization/{organization_id}/dashboards/count', 'OrganizationController@getOrgDashboardsCount');
Route::middleware('auth:api')->get('organization/{organization_id}/triggers/count', 'OrganizationController@getOrgTriggersCount');
Route::middleware('auth:api')->get('organizations/projects/dashboards', 'OrganizationController@getOrgProjectsDasboards');

//Projects
Route::middleware('auth:api')->delete('project/delete/{project_id}', 'ProjectController@deleteProject');
Route::middleware('auth:api')->get('projects', 'ProjectController@getProjects');
Route::middleware('auth:api')->get('projects/count', 'ProjectController@getTotalProjectsCount');
Route::middleware('auth:api')->get('project/{project_id}', 'ProjectController@getProject');
Route::middleware('auth:api')->get('project/{project_id}/organization', 'ProjectController@getOrganizationByProjectId');
Route::middleware('auth:api')->post('project/create', 'ProjectController@createProject');
Route::middleware('auth:api')->post('project/edit/{project_id}', 'ProjectController@updateProject');
Route::middleware('auth:api')->get('project/{project_id}/users', 'ProjectController@getProjectUsers');
Route::middleware('auth:api')->get('project/{project_id}/users/count', 'ProjectController@getProjectUsersCount');
Route::middleware('auth:api')->get('project/{project_id}/dashboards/count', 'ProjectController@getProjectDashboardsCount');
Route::middleware('auth:api')->get('project/{project_id}/dashboards', 'ProjectController@getDashboardByProjectId');
Route::post('project/upload/{project_id}', 'ProjectController@uploadImage');



//Dashboard
Route::middleware('auth:api')->get('dashboards', 'DashboardController@getDashboards');
Route::middleware('auth:api')->post('dashboard/create', 'DashboardController@createDashboard');
Route::middleware('auth:api')->get('dashboard/{dashboard_id}', 'DashboardController@getDashboard');
Route::middleware('auth:api')->get('dashboards/count', 'DashboardController@getTotalDashboardscount');
Route::middleware('auth:api')->get('dashboard/{dashboard_id}/panels', 'DashboardController@getPanelsByDashboardId');
Route::middleware('auth:api')->delete('dashboard/delete/{dashboard_id}', 'DashboardController@deleteDashboard');

//Panel
Route::middleware('auth:api')->post('panel/create', 'DashboardController@createPanel');
Route::middleware('auth:api')->get('panel/{panel_id}', 'DashboardController@getPanel');
Route::middleware('auth:api')->get('panels/type', 'DashboardController@getPanelsType');
Route::middleware('auth:api')->delete('panel/delete/{panel_id}', 'DashboardController@deletePanel');

//Spaces
Route::middleware('auth:api')->post('space/create', 'SpaceController@createSpace');
Route::middleware('auth:api')->get('space/{space_id}', 'SpaceController@getSpace');
Route::middleware('auth:api')->post('space/edit/{space_id}', 'SpaceController@updateSpace');
Route::middleware('auth:api')->get('spaces', 'SpaceController@getSpaces');
Route::middleware('auth:api')->get('project/{project_id}/spaces', 'SpaceController@getSpacesByProjectId');
Route::middleware('auth:api')->get('organization/{organization_id}/spaces', 'SpaceController@getSpacesByOrganizationId');
Route::middleware('auth:api')->delete('space/delete/{space_id}', 'SpaceController@deleteSpace');
Route::middleware('auth:api')->get('project/{project_id}/spaces/count', 'SpaceController@getProjectSpacesCount');
Route::post('space/upload/{space_id}', 'SpaceController@uploadImage');


//Datasources
Route::middleware('auth:api')->get('datasources', 'DatasourceController@getDatasources');
Route::middleware('auth:api')->get('datasource/{datasources_id}', 'DatasourceController@getDatasource');
Route::middleware('auth:api')->post('datasource/create', 'DatasourceController@createDatasource');
Route::middleware('auth:api')->get('datasource/{datasource_id}/project/{project_id}', 'ProjectController@attachDatasourceProject');
Route::middleware('auth:api')->post('datasource/edit/{datasource_id}', 'DatasourceController@updateDatasource');
Route::middleware('auth:api')->delete('datasource/delete/{datasource_id}', 'DatasourceController@deleteDatasource');
Route::middleware('auth:api')->get('project/{projectParam}/datasources', 'DatasourceController@getProjectDatasources');
Route::middleware('auth:api')->get('project/{projectParam}/hp/datasources', 'DatasourceController@getProjectHpDatasources');
Route::middleware('auth:api')->get('project/{projectParam}/datasources/actives', 'DatasourceController@getActiveProjectDatasources');
Route::middleware('auth:api')->get('project/{projectParam}/datasources/count', 'DatasourceController@getProjectDatasourcesCount');
Route::middleware('auth:api')->get('datasource/{datasourceParam}/datapoints', 'DatasourceController@getDatasourceDatapoints');
Route::middleware('auth:api')->get('datasources/type', 'DatasourceController@getDatasourcesType');
Route::middleware('auth:api')->get('datasource/types/{datasource_type_id}', 'DatasourceController@getDatasourceTypeNameById');
Route::middleware('auth:api')->get('datasource/protocols/{datasource_protocol_type_id}', 'DatasourceController@getDatasourceProtocolTypeById');
Route::middleware('auth:api')->get('datasource/protocols', 'DatasourceController@getDatasourceProtocolTypes');
Route::middleware('auth:api')->get('datasource/{datasource_id}/project', 'DatasourceController@getProjectByDatasourceId');
Route::middleware('auth:api')->get('space/{space_id}/datasources', 'DatasourceController@GetDatasourcesBySpaceId');
Route::middleware('auth:api')->post('thingstatus', 'DatasourceController@ThingStatus');
Route::post('datasource/upload/{datasource_id}', 'DatasourceController@uploadImage');

//Organization analytics
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/average', 'DatasourceController@getOrganizationDatasourceTypeAverageValueByDateRange');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/max', 'DatasourceController@getOrganizationDatasourceTypeMaxValueByDateRange');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/min', 'DatasourceController@getOrganizationDatasourceTypeMinValueByDateRange');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/count', 'DatasourceController@getOrganizationDatasourceTypeCountValueByDateRange');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/values', 'DatasourceController@getOrganizationDatasourceTypeValuesByDateRange');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/values/month', 'DatasourceController@getOrganizationDatasourceTypeValuesByDateRangeMonth');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/values/day', 'DatasourceController@getOrganizationDatasourceTypeValuesByDateRangeDay');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/values/hour', 'DatasourceController@getOrganizationDatasourceTypeValuesByDateRangeHour');
Route::middleware('auth:api')->post('organization/{organization_id}/analytics/values/minute', 'DatasourceController@getOrganizationDatasourceTypeValuesByDateRangeMinute');

//Project analytics
Route::middleware('auth:api')->post('project/{project_id}/analytics/average', 'DatasourceController@getProjectDatasourceTypeAverageValueByDateRange');
Route::middleware('auth:api')->post('project/{project_id}/analytics/min', 'DatasourceController@getProjectDatasourceTypeMinValueByDateRange');
Route::middleware('auth:api')->post('project/{project_id}/analytics/max', 'DatasourceController@getProjectDatasourceTypeMaxValueByDateRange');
Route::middleware('auth:api')->post('project/{project_id}/analytics/count', 'DatasourceController@getProjectDatasourceTypeValueCountByDateRange');
Route::middleware('auth:api')->post('project/{project_id}/analytics/values', 'DatasourceController@getProjectDatasourceTypeValuesByDateRange');
Route::middleware('auth:api')->post('project/{project_id}/analytics/values/average/minute', 'DatasourceController@getProjectDatasourceTypeValuesByDateRangeMinute');
Route::middleware('auth:api')->post('project/{project_id}/analytics/values/average/hour', 'DatasourceController@getProjectDatasourceTypeValuesByDateRangeHour');
Route::middleware('auth:api')->post('project/{project_id}/analytics/values/average/day', 'DatasourceController@getProjectDatasourceTypeValuesByDateRangeDay');
Route::middleware('auth:api')->post('project/{project_id}/analytics/values/average/month', 'DatasourceController@getProjectDatasourceTypeValuesByDateRangeMonth');

//Space Analytics
Route::middleware('auth:api')->post('space/{space_id}/analytics/average', 'DatasourceController@getSpaceDatasourceTypeAverageValueByDateRange');
Route::middleware('auth:api')->post('space/{space_id}/analytics/min', 'DatasourceController@getSpaceDatasourceTypeMinValueByDateRange');
Route::middleware('auth:api')->post('space/{space_id}/analytics/max', 'DatasourceController@getSpaceDatasourceTypeMaxValueByDateRange');
Route::middleware('auth:api')->post('space/{space_id}/analytics/count', 'DatasourceController@getSpaceDatasourceTypeValueCountByDateRange');
Route::middleware('auth:api')->post('space/{space_id}/analytics/values', 'DatasourceController@getSpaceDatasourceTypeValuesByDateRange');

//Datasources analitycs
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/average', 'DatasourceController@getDatasourceAverageValueByDateRange');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/max', 'DatasourceController@getDatasourceMaxValueByDateRange');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/min', 'DatasourceController@getDatasourceMinValueByDateRange');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/count', 'DatasourceController@getDatasourceValueCountByDateRange');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/values', 'DatasourceController@getDatasourceValuesNewByDateRange');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/values/average/minute', 'DatasourceController@getDatasourceAverageValuesNewByDateRangeMinute');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/values/average/hour', 'DatasourceController@getDatasourceAverageValuesNewByDateRangeHour');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/values/average/day', 'DatasourceController@getDatasourceAverageValuesNewByDateRangeDay');
Route::middleware('auth:api')->post('datasource/{datasource_id}/analytics/values/average/month', 'DatasourceController@getDatasourceAverageValuesNewByDateRangeMonth');


//Datapoints
Route::middleware('auth:api')->get('datapoints', 'DatapointController@getDatapoints');
Route::middleware('auth:api')->get('datapoint/{datapoint_id}', 'DatapointController@getDatapoint');
Route::middleware('auth:api')->post('datapoint/create', 'DatapointController@createDatapoint');
Route::middleware('auth:api')->post('datapoint/edit/{datapoint_id}', 'DatapointController@updateDatapoint');
Route::middleware('auth:api')->delete('datapoint/delete/{datapoint_id}', 'DatapointController@deleteDatapoint');
Route::middleware('auth:api')->get('datapoint/datasource/{datasource_id}', 'DatapointController@getDataPointByDataSource');
Route::middleware('auth:api')->get('datapoint/datasource/{datasource_id}/actives', 'DatapointController@getActiveDataPointByDataSource');
Route::middleware('auth:api')->get('datapoints/types', 'DatapointController@getDatapointTypes');
Route::middleware('auth:api')->get('datapoint/{datapoint_id}/fromdate/{from_date}/todate/{to_date}', 'DatapointController@getDatapointValuesByDateRange');
Route::middleware('auth:api')->get('datasource/{datasource_id}/fromdate/{from_date}/todate/{to_date}', 'DatasourceController@getDatasourceValuesByDateRange');
Route::middleware('auth:api')->get('datasource/{datasource_id}/analytics/fromdate/{from_date}/todate/{to_date}', 'DatasourceController@getDatasourceValuesByDateRangeAnalytics');
Route::middleware('auth:api')->get('space/{space_id}/datapoints', 'DatapointController@GetDatapointsBySpaceId');


//Triggers
Route::middleware('auth:api')->get('trigger/types', 'TriggerController@getTriggerTypes');
Route::middleware('auth:api')->get('trigger/type/{type_id}', 'TriggerController@getTriggerTypeById');
//Route::middleware('auth:api')->get('triggers', 'TriggerController@getTriggers');
Route::middleware('auth:api')->get('triggers/count', 'TriggerController@getTotalTriggersCount');
Route::middleware('auth:api')->get('trigger/{trigger_id}', 'TriggerController@getTriggerById');
Route::middleware('auth:api')->post('trigger/create', 'TriggerController@createTrigger');
Route::middleware('auth:api')->post('trigger/edit/{trigger_id}', 'TriggerController@updateTrigger');
Route::middleware('auth:api')->delete('trigger/delete/{trigger_id}', 'TriggerController@deleteTrigger');
Route::middleware('auth:api')->get('trigger/datasource/{datasource_id}', 'TriggerController@getTriggerByDatasourceId');
Route::middleware('auth:api')->get('trigger/datapoint/{datapoint_id}', 'TriggerController@getTriggerByDatapointId');
Route::middleware('auth:api')->get('project/{project_id}/trigger/count', 'TriggerController@getProjectTriggersCount');
Route::middleware('auth:api')->get('project/{project_id}/triggers', 'TriggerController@getTriggersByProjectId');
Route::middleware('auth:api')->get('operators', 'TriggerController@getTriggerOperators');
//Triggers unauthorized
Route::get('triggers', 'TriggerController@getTriggers');
Route::get('nopagination/triggers', 'TriggerController@getTriggersNoPagination');

//Triggers Notifications
Route::middleware('auth:api')->get('notifications', 'TriggerController@getTriggersNotifications');
Route::middleware('auth:api')->get('organization/{organizationParam}/notifications', 'TriggerController@getTriggersNotificationsByOrgId');
Route::middleware('auth:api')->delete('notification/delete/{notification_id}', 'TriggerController@deleteTriggerNotification');

//Mobile Notifications
Route::post('mobile/notification/create', 'MobileNotificationsController@createMobileNotification');
Route::middleware('auth:api')->get('/mobile/notifications', 'MobileNotificationsController@getMobileNotifications');
Route::middleware('auth:api')->get('project/{project_id}/mobile/notifications', 'MobileNotificationsController@getMobileNotificationsByProjectId');
Route::middleware('auth:api')->delete('mobile/notification/delete/{mobilenotification_id}', 'MobileNotificationsController@deleteMobileNotification');

//Mobile Support
Route::middleware('auth:api')->post('mobile/support/create', 'MenuController@createMobileSupport');

//Sensors
Route::post('node-red/data', 'SensorController@createData');
Route::post('node-red/sensor-data', 'SensorController@createSensorData');
Route::get('node-red/sensor-data/address/{address}/ip/{ip}/port/{port}/type/{type}/unitid/{unitid}', 'SensorController@getSensorData');
Route::get('node-red/last-sensor-data/address/{address}/ip/{ip}/port/{port}/type/{type}/unitid/{unitid}', 'SensorController@getLastSensorData');

//Datasource Sensor data
Route::post('datasourcesensordata/create', 'DatasourceSensorDataController@createDatasourceSensorData');

//Events 
Route::middleware('auth:api')->post('event/create', 'EventDBController@createEventDB');
Route::middleware('auth:api')->get('events', 'EventDBController@getEventsDB');
Route::middleware('auth:api')->get('event/{eventid}', 'EventDBController@getEventDB');
Route::middleware('auth:api')->get('events/actions', 'EventDBController@getEventsDBActions');
Route::middleware('auth:api')->get('events/organization/{organization_id}', 'EventDBController@getEventsDBByOrgId');
Route::middleware('auth:api')->get('events/project/{project_id}', 'EventDBController@getEventsDBByProjectId');
//Route::middleware('auth:api')->post('event/update/{event_id}', 'EventController@updateEvent');
Route::middleware('auth:api')->delete('event/delete/{event_id}', 'EventDBController@deleteEventDB');

//Mobile 
Route::middleware('auth:api')->get('mobile/about', 'MenuController@getMobileAbout');

//Firebase push notifictaion test
Route::middleware('auth:api')->post('push/test', 'UserController@pushTest');


//Pusher Implementation
Route::get('/push/{message}', 'NotificationController@PostNotify');
Route::get('/pusherapi/{message}', function($message) {
    // event(new App\Events\HelloPusherEvent('Hi there Pusher!'));
    // return "Event has been sent!";
    $options = array(
    'cluster' => 'us2',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    '0cbe3c892f6f009260b0',
    '90fd1ba645c5220b52dd',
    '394671',
    $options
  );

  $data['message'] = $message;
  $pusher->trigger('my-channel', 'my-event', $data);
});	

Route::get('/pusherapi/mobile/{message}', function($message) {
    // event(new App\Events\HelloPusherEvent('Hi there Pusher!'));
    // return "Event has been sent!";
    $options = array(
       'cluster' => 'ap2',
        'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    'ac8a768a0703b3366214',
    'd7a518c31f36914d6ac9',
    '639063',
    $options
  );

  $data['message'] = $message;
  $pusher->trigger('my-channel', 'my-event', $data);
}); 

//Web App Menu
Route::middleware('auth:api')->get('menu/{role_id}', 'MenuController@getMenuItems');

//User from tutorial
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

