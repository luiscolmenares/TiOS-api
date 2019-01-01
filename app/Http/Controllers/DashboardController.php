<?php
namespace App\Http\Controllers;

use App\Dashboard;
use App\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller {
/**
* @SWG\Get(
*      path="/dashboards",
*      operationId="getDashboards",
*      tags={"Dashboards"},
*      summary="Get list of dashboards for the web app",
*      description="Returns list of dashboards",
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
public function getDashboards() {
    return Dashboard::all();
}

/**
* @SWG\Get(
*      path="/dashboards/count",
*      operationId="getTotalDashboardsCount",
*      tags={"Dashboards"},
*      summary="Get count of dashboards",
*      description="Returns count of dashboards",
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
public function getTotalDashboardsCount(){
    return Dashboard::all()->count();
}

/**
* @SWG\Get(
*      path="/dashboard/{id}",
*      operationId="getDashboard",
*      tags={"Dashboards"},
*      summary="Get dashboard information",
*      description="Returns dashboard data",
*      @SWG\Parameter(
*          name="id",
*          description="dashboard id",
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
public function getDashboard($dashboardId) {
    $dashboard = Dashboard::find($dashboardId);
    $dashboard = array('dashboard' => $dashboard);
    return $dashboard;
}

/**
* @SWG\Get(
*      path="/panels/type",
*      operationId="getPanelsType",
*      tags={"Panels"},
*      summary="Get list of Panels type",
*      description="Returns list of Panels type",
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
public function getPanelsType() {
    $panelstype = \DB::table('panels_type')
    ->orderBy('name', 'desc')
    ->get();
    $panelstype = array('panelstype' => $panelstype);
    return $panelstype;
}


/**
* @SWG\Get(
*      path="/panel/{id}",
*      operationId="getPanel",
*      tags={"Panels"},
*      summary="Get panel information",
*      description="Returns panel data",
*      @SWG\Parameter(
*          name="id",
*          description="panel id",
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
public function getPanel($panelId) {
    $panel = \DB::table('panels')
    ->where('panels.id', '=', $panelId)
    ->join('panels_type', 'panels_type.id', '=','panels.type')
    ->select('panels.*', 'panels_type.name as panelstype_name')
    ->get();
    $panel = array('panel' => $panel);
    return $panel;

} 

/**
* Creates Dashboard
* @param Request request
* return dashboard
*/
public function createDashboard(Request $request) {
    $dashboard = new Dashboard($request->all());
    if (!$dashboard->save()) {
        abort(500, 'Could not save dashboard.');
    }
    return $dashboard;
}

/**
* Deletes a Panel
* @param $panelId
* return boolean
*/
public function deletePanel($panelId){
    $panel = Panel::find($panelId);
    if (!$panel->delete()) {
        abort(500, 'Could not delete panel.');
    }

}

/**
* Deletes a Dashboard
* @param $dashboardId
* return boolean
*/
public function deleteDashboard($dashboardId){
    $dashboard = Dashboard::find($dashboardId);
    if (!$dashboard->delete()) {
        abort(500, 'Could not delete dashboard.');
    }
}


/**
* Creates Panel
* @param Request request
* return panel
*/
public function createPanel(Request $request) {
    $panel = new Panel($request->all());
    if (!$panel->save()) {
        abort(500, 'Could not save panel.');
    }
    return $panel;
}

/**
* Get Panels by DashboardId
* @param dashboardId
* return panels 
Panel types
+----+------------------------------+------------+------------+------------+
| id | name                         | created_at | updated_at | deleted_at |
+----+------------------------------+------------+------------+------------+
|  1 | Chart - Lines - Temperature  | NULL       | NULL       | NULL       |
|  2 | Chart - Bars - Temperature   | NULL       | NULL       | NULL       |
|  3 | Widget - Temperature         | NULL       | NULL       | NULL       |
|  4 | Widget - Humidity            | NULL       | NULL       | NULL       |
|  5 | History Log - Temperature    | NULL       | NULL       | NULL       |
|  6 | History Log - Humidity       | NULL       | NULL       | NULL       |
|  7 | Widget - Gauge - Temperature | NULL       | NULL       | NULL       |
|  8 | Widget - Gauge - Humidity    | NULL       | NULL       | NULL       |
|  9 | Widget - Gauge - Power(Kwh)  | NULL       | NULL       | NULL       |
| 10 | Widget - Power Switch        | NULL       | NULL       | NULL       |
| 11 | Chart - Lines - Humidity     | NULL       | NULL       | NULL       |
| 12 | Chart - Bars - Humidity      | NULL       | NULL       | NULL       |
+----+------------------------------+------------+------------+------------+
*/
/**
* @SWG\Get(
*      path="/dashboard/{id}/panels",
*      operationId="getPanelsByDashboardId",
*      tags={"Panels"},
*      summary="Get panels information from a particular dashboard",
*      description="Returns panel data",
*      @SWG\Parameter(
*          name="id",
*          description="dashboard id",
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
public function getPanelsByDashboardId($dashboardId) {
    $panels = \DB::table('panels')->where('dashboard_id', '=', $dashboardId)->get();
//$chartTypePanels = ['1', '2', '3', '4', '5', '6', '7'];
    $chartTypePanelsChartTemperature = ['1', '2'];
    $chartTypePanelsChartHumidity = ['11', '12'];
    $chartTypePanelsWidgetTemperature = ['3', '7'];
    $chartTypePanelsWidgetHumidity = ['4', '8'];
    $chartTypePanelsChartVoltage = ['14', '15'];
    $chartTypePanelsChartPower = ['17', '18'];
    $chartTypePanelsChartCurrent = ['21', '22'];
    $chartTypePanelsWidgetTemperatureMQTT = ['24', '25'];
// $chartTypePanelsWidgetVoltage = ['13', '8'];
    foreach ($panels as $panel) {
        $panel->datasource = array();
// if (in_array($panel->type, $chartTypePanels)) {
//     $datepoint = \App\Datapoint::find($panel->datapoint_id);
//     $datesource = \App\Datasource::find($panel->datasource_id);
//     $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
// } 
        if (in_array($panel->type, $chartTypePanelsChartTemperature)){
            // alert('yup');
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            $options_array = json_decode($datesource->options, true);
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-temperature-celsius';
//Its a SmartThing via MQTT (no datapoint)
// $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                // $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getSensorDataFromDatasource($options_array['topic']);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            }
        } else if (in_array($panel->type, $chartTypePanelsChartHumidity)){
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-humidity';
//Its a SmartThing via MQTT (no datapoint)
// $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                $panel->datasource = $datesource;
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            }
        }
        else if (in_array($panel->type, $chartTypePanelsChartVoltage)){
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-voltage';
//Its a SmartThing via MQTT (no datapoint)
// $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                $panel->datasource = $datesource;
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            }
        }
        else if (in_array($panel->type, $chartTypePanelsChartPower)){
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-power';
//Its a SmartThing via MQTT (no datapoint)
// $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                $panel->datasource = $datesource;
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            }
        }
        else if (in_array($panel->type, $chartTypePanelsChartCurrent)){
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-current';
//Its a SmartThing via MQTT (no datapoint)
// $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                $panel->datasource = $datesource;
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            }
        }
        else if (in_array($panel->type, $chartTypePanelsWidgetTemperature)) {
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
             $options_array = json_decode($datesource->options, true);
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-temperature-celsius';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLastSensorDataFromDatasource($options_array['topic']);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                // $panel->datasource = $datesource;
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            }

        } else if (in_array($panel->type, $chartTypePanelsWidgetTemperatureMQTT)) {
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-temperature-celsius';
                $mqtt_info = json_decode($datesource->options, true);
//Its a SmartThing via MQTT (no datapoint)
                // $mqtt_info = array(
                //         'host' => "mqtt.tiosplatform.com",
                //         'port' => "8083",
                //         'id' => "id_".$panel->id,
                //         'topic' => 'org1/room1/monitor/temperature',                   
                //         );
                $panel->MQTTInfo = $mqtt_info;
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                // $panel->datasource = $datesource;
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            }

        } 
        else if (in_array($panel->type, $chartTypePanelsWidgetHumidity)) {
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-humidity';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLastSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
                // $panel->datasource = $datesource;
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            }

        } else if ($panel->type == '5') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            $options_array = json_decode($datesource->options, true);
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-temperature-celsius';
//Its a SmartThing via MQTT (no datapoint)
                // $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                $panel->sensorData = $this->getLast10SensorDataFromDatasource($options_array['topic']);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            }
        } else if ($panel->type == '6') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Humidity', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-humidity';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
            }
        }  
        else if ($panel->type == '16') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Humidity', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-voltage';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Voltage', $datepoint->unitid);
            }
        } 
        else if ($panel->type == '19') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Humidity', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-energy';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Voltage', $datepoint->unitid);
            }
        } 
        else if ($panel->type == '23') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Humidity', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='ds-current';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Voltage', $datepoint->unitid);
            }
        } 
// else if ($panel->type == '7') {
//     $datepoint = \App\Datapoint::find($panel->datapoint_id);
//     $datesource = \App\Datasource::find($panel->datasource_id);
//     $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
// }  
// else if ($panel->type == '8') {
//     $datepoint = \App\Datapoint::find($panel->datapoint_id);
//     $datesource = \App\Datasource::find($panel->datasource_id);
//     $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Humidity', $datepoint->unitid);
// }  
        else if ($panel->type == '9') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='MQTT electric power (W)';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLastSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power', $datepoint->unitid);
            }
        }  else if ($panel->type == '10') {
            $datepoint = \App\Datapoint::find($panel->datapoint_id);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power Switch', $datepoint->unitid);
        }
        else if ($panel->type == '13') {
// $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $datesource = \App\Datasource::find($panel->datasource_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power', $datepoint->unitid);
            $datesource = \App\Datasource::find($panel->datasource_id);
            $panel->datasource = $datesource;
            if($datesource->ip == '0.0.0.0'){
//datasource ID as address to identify sensor data
                $datesource->address=0;
                $datesource->type='MQTT Voltage (V)';
//Its a SmartThing via MQTT (no datapoint)
                $panel->sensorData = $this->getLastSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
            } else {
//Modbus with datasource -> datapoint
                $datepoint = \App\Datapoint::find($panel->datapoint_id);
// $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Voltage', $datepoint->unitid);
            }
        }
    }
    return $panels;
}


/**
* @SWG\Get(
*      path="/node-red/sensor-data/address/{address}/ip/{ip}/port/{port}/type/{type}/unitid/{unitid}",
*      operationId="getSensorData",
*      tags={"Sensors"},
*      summary="Get sensor data",
*      description="Returns sensor data (last 20 entries recorded in DB). This data gets collected from a datapoint (for example, a temperature sensor) connected to a datasource (for example, a Vemetris Gateway)",
*      @SWG\Parameter(
*          name="address",
*          description="Datasource address",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="ip",
*          description="Datasource ip",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="port",
*          description="Datasource port",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="type",
*          description="Datapoint type",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="unitid",
*          description="Datapoint Unit ID",
*          required=true,
*          type="string",
*          in="path"
*      ),
*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*      @SWG\Response(response=400, description="Bad request"),
*      @SWG\Response(response=404, description="Resource Not Found"),
* )
*
*/ 

public function getSensorData($address, $ip, $port, $type, $unitid) {
    $sensordata = \DB::table('sensordata')
    ->select('created_at', 'data')
    ->where([
        ['type', '=', $type],
        ['unitid', '=', $unitid],
        ['address', '=', $address],
        ['ip', '=', $ip],
        ['port', '=', $port],
    ])
    ->orderBy('created_at', 'desc')
    ->take(20)
    ->get()
    ->reverse();
    return $sensordata;
}



// public function getSensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
//     $sensordata = \DB::table('sensordata')
//     ->select('created_at', 'data')
//     ->where([
//         ['type', '=', $type],
//         ['unitid', '=', $unitid],
//         ['address', '=', $address],
//         ['ip', '=', $ip],
//         ['port', '=', $port],
//         ['datasource', '=', $datasource],
//     ])
//     ->orderBy('created_at', 'desc')
//     ->take(20)
//     ->get()
//     ->reverse();
//     return $sensordata;
// }

public function getSensorDataFromDatasource($topic) {
    $now =  \Carbon\Carbon::now()->format('Y-m-d H:i');
    $now_timestamp =  strtotime($now);
    $one_min_ago = $now_timestamp - 60;

    $sensordata = $lastsensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', 'value as data')
    ->where('topic', '=', $topic)
    ->where('timestamp', '>', $one_min_ago)
    ->orderBy('created_at', 'desc')
    // ->take(20)
    ->get()
    ->reverse();
    return $sensordata;
}

/**
* @SWG\Get(
*      path="/node-red/last-sensor-data/address/{address}/ip/{ip}/port/{port}/type/{type}/unitid/{unitid}",
*      operationId="getLastSensorData",
*      tags={"Sensors"},
*      summary="Get last sensor data",
*      description="Returns sensor data (last entry recorded in DB). This data gets collected from a datapoint (for example, a temperature sensor) connected to a datasource (for example, a Vemetris Gateway)",
*      @SWG\Parameter(
*          name="address",
*          description="Datasource address",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="ip",
*          description="Datasource ip",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="port",
*          description="Datasource port",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="type",
*          description="Datapoint type",
*          required=true,
*          type="string",
*          in="path"
*      ),
*   @SWG\Parameter(
*          name="unitid",
*          description="Datapoint Unit ID",
*          required=true,
*          type="string",
*          in="path"
*      ),
*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*      @SWG\Response(response=400, description="Bad request"),
*      @SWG\Response(response=404, description="Resource Not Found"),
* )
*
*/ 

public function getLastSensorData($address, $ip, $port, $type, $unitid) {
    $lastsensordata = \DB::table('sensordata')
    ->select('created_at', 'data')
    ->where([
        ['type', '=', $type],
        ['unitid', '=', $unitid],
        ['address', '=', $address],
        ['ip', '=', $ip],
        ['port', '=', $port],
    ])
    ->orderBy('created_at', 'desc')
    ->first();
    return $lastsensordata;
}



// public function getLastSensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
//     $lastsensordata = \DB::table('sensordata')
//     ->select('created_at', 'data')
//     ->where([
//         ['type', '=', $type],
//         ['unitid', '=', $unitid],
//         ['address', '=', $address],
//         ['ip', '=', $ip],
//         ['port', '=', $port],
//         ['datasource', '=', $datasource],
//     ])
//     ->orderBy('created_at', 'desc')
//     ->first();
//     return $lastsensordata;
// }

public function getLastSensorDataFromDatasource($topic) {
    $lastsensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', 'value as data')
    ->where('topic', '=', $topic)
    ->orderBy('created_at', 'desc')
    ->first();
    return $lastsensordata;
}

// public function getLastSensorDataFromDatasourceMQTT($datasource, $address, $ip, $port, $type, $unitid) {
//     $lastsensordata = \DB::table('sensordata')
//     ->select('created_at', 'data')
//     ->where([
//         ['type', '=', $type],
//         ['unitid', '=', $unitid],
//         ['address', '=', $address],
//         ['ip', '=', $ip],
//         ['port', '=', $port],
//         ['datasource', '=', $datasource],
//     ])
//     ->orderBy('created_at', 'desc')
//     ->first();
//     return $lastsensordata;
// }

public function getLastSensorDataFromDatasourceMQTT($topic) {
    $lastsensordata =  \DB::table('datasource_sensor_datas')
    ->select('created_at', 'value as data')
    ->where('topic', '=', $topic)
    ->orderBy('created_at', 'desc')
    ->first();
    return $lastsensordata;
}

// public function getLast10SensorData($address, $ip, $port, $type, $unitid) {
//     $lastsensordata = \DB::table('sensordata')
//     ->select('created_at', 'data')
//     ->where([
//         ['type', '=', $type],
//         ['unitid', '=', $unitid],
//         ['address', '=', $address],
//         ['ip', '=', $ip],
//         ['port', '=', $port],
//     ])
//     ->orderBy('created_at', 'desc')
//     ->take(10)
//     ->get();
//     return $lastsensordata;
// }

public function getLast10SensorData($topic) {
    $lastsensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', 'value as data')
    ->where('topic', '=', $topic)
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
    return $lastsensordata;
}

// public function getLast10SensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
//     $lastsensordata = \DB::table('sensordata')
//     ->select('created_at', 'data')
//     ->where([
//         ['type', '=', $type],
//         ['unitid', '=', $unitid],
//         ['address', '=', $address],
//         ['ip', '=', $ip],
//         ['port', '=', $port],
//         ['datasource', '=', $datasource],
//     ])
//     ->orderBy('created_at', 'desc')
//     ->take(10)
//     ->get();
//     return $lastsensordata;
// }

public function getLast10SensorDataFromDatasource($topic) {
    $lastsensordata = \DB::table('datasource_sensor_datas')
    ->select('created_at', 'value as data')
    ->where('topic', '=', $topic)
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
    return $lastsensordata;
}

}
