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
     * Get all Dasboards
     * @param 
     * return dashboards
     */
    public function getDashboards() {
        return Dashboard::all();
    }

    /**
    * Get dashboard total count
    * @param 
    * return count int
    */
    public function getTotalDashboardsCount(){
        return Dashboard::all()->count();
    }

    /**
     * Get Dashboard from dashboardId
     * @param dashboardId
     * return dashboard
     */
    public function getDashboard($dashboardId) {
        $dashboard = Dashboard::find($dashboardId);
        $dashboard = array('dashboard' => $dashboard);
        return $dashboard;
    }

    /**
     * Get pnels type
     * @param dashboardId
     * return dashboard
     */
    public function getPanelsType() {
        $panelstype = \DB::table('panels_type')
                        ->orderBy('name', 'desc')
                        ->get();
        $panelstype = array('panelstype' => $panelstype);
        return $panelstype;
    }
    

   /**
     * Get Panel from panelId
     * @param panelId
     * return panel
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
        // $chartTypePanelsWidgetVoltage = ['13', '8'];
        foreach ($panels as $panel) {
            // if (in_array($panel->type, $chartTypePanels)) {
            //     $datepoint = \App\Datapoint::find($panel->datapoint_id);
            //     $datesource = \App\Datasource::find($panel->datasource_id);
            //     $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
            // } 
            if (in_array($panel->type, $chartTypePanelsChartTemperature)){
                $datesource = \App\Datasource::find($panel->datasource_id);
                    if($datesource->ip == '0.0.0.0'){
                        //datasource ID as address to identify sensor data
                        $datesource->address=0;
                        $datesource->type='ds-temperature-celsius';
                        //Its a SmartThing via MQTT (no datapoint)
                        // $panel->sensorData = $this->getLastSensorDataFromDatapoint($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                        $panel->sensorData = $this->getSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                    } else {
                        //Modbus with datasource -> datapoint
                        $datepoint = \App\Datapoint::find($panel->datapoint_id);
                        // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                        $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
                    }
            } else if (in_array($panel->type, $chartTypePanelsChartHumidity)){
                $datesource = \App\Datasource::find($panel->datasource_id);
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
                        // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                        $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
                    }
            }
            else if (in_array($panel->type, $chartTypePanelsChartVoltage)){
                $datesource = \App\Datasource::find($panel->datasource_id);
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
                        // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                        $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
                    }
            }
             else if (in_array($panel->type, $chartTypePanelsChartPower)){
                $datesource = \App\Datasource::find($panel->datasource_id);
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
                        // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                        $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
                    }
            }
            else if (in_array($panel->type, $chartTypePanelsChartCurrent)){
                $datesource = \App\Datasource::find($panel->datasource_id);
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
                        // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                        $panel->sensorData = $this->getSensorData($datepoint->address, $datesource->ip, $datesource->port, $datepoint->type, $datepoint->unitid);
                    }
            }
            else if (in_array($panel->type, $chartTypePanelsWidgetTemperature)) {
                $datesource = \App\Datasource::find($panel->datasource_id);
                    if($datesource->ip == '0.0.0.0'){
                        //datasource ID as address to identify sensor data
                        $datesource->address=0;
                        $datesource->type='ds-temperature-celsius';
                        //Its a SmartThing via MQTT (no datapoint)
                        $panel->sensorData = $this->getLastSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                    } else {
                        //Modbus with datasource -> datapoint
                        $datepoint = \App\Datapoint::find($panel->datapoint_id);
                        $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                    }
                
            } else if (in_array($panel->type, $chartTypePanelsWidgetHumidity)) {
                $datesource = \App\Datasource::find($panel->datasource_id);
                    if($datesource->ip == '0.0.0.0'){
                        //datasource ID as address to identify sensor data
                        $datesource->address=0;
                        $datesource->type='ds-humidity';
                        //Its a SmartThing via MQTT (no datapoint)
                        $panel->sensorData = $this->getLastSensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
                    } else {
                        //Modbus with datasource -> datapoint
                        $datepoint = \App\Datapoint::find($panel->datapoint_id);
                        $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                    }
                
            } else if ($panel->type == '5') {
                // $datepoint = \App\Datapoint::find($panel->datapoint_id);
                // $datesource = \App\Datasource::find($panel->datasource_id);
                // $panel->sensorData = $this->getLast10SensorData($datepoint->address, $datesource->ip, $datesource->port, 'Temperature', $datepoint->unitid);
                $datesource = \App\Datasource::find($panel->datasource_id);
                    if($datesource->ip == '0.0.0.0'){
                        //datasource ID as address to identify sensor data
                        $datesource->address=0;
                        $datesource->type='ds-temperature-celsius';
                        //Its a SmartThing via MQTT (no datapoint)
                        $panel->sensorData = $this->getLast10SensorDataFromDatasource($datesource->name, $datesource->address, $datesource->ip, $datesource->port, $datesource->type, $datesource->unitid);
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
                $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power Switch', $datepoint->unitid);
            }
            else if ($panel->type == '13') {
                // $datepoint = \App\Datapoint::find($panel->datapoint_id);
                // $datesource = \App\Datasource::find($panel->datasource_id);
                // $panel->sensorData = $this->getLastSensorData($datepoint->address, $datesource->ip, $datesource->port, 'Power', $datepoint->unitid);
                $datesource = \App\Datasource::find($panel->datasource_id);
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

    public function getSensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
        $sensordata = \DB::table('sensordata')
                ->select('created_at', 'data')
                ->where([
                    ['type', '=', $type],
                    ['unitid', '=', $unitid],
                    ['address', '=', $address],
                    ['ip', '=', $ip],
                    ['port', '=', $port],
                    ['datasource', '=', $datasource],
                ])
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get()
                ->reverse();
        return $sensordata;
    }

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

     public function getLastSensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
        $lastsensordata = \DB::table('sensordata')
                ->select('created_at', 'data')
                ->where([
                    ['type', '=', $type],
                    ['unitid', '=', $unitid],
                    ['address', '=', $address],
                    ['ip', '=', $ip],
                    ['port', '=', $port],
                    ['datasource', '=', $datasource],
                ])
                ->orderBy('created_at', 'desc')
                ->first();
        return $lastsensordata;
    }
    
    public function getLast10SensorData($address, $ip, $port, $type, $unitid) {
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
                ->take(10)
                ->get();
        return $lastsensordata;
    }

    public function getLast10SensorDataFromDatasource($datasource, $address, $ip, $port, $type, $unitid) {
        $lastsensordata = \DB::table('sensordata')
                ->select('created_at', 'data')
                ->where([
                    ['type', '=', $type],
                    ['unitid', '=', $unitid],
                    ['address', '=', $address],
                    ['ip', '=', $ip],
                    ['port', '=', $port],
                    ['datasource', '=', $datasource],
                ])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        return $lastsensordata;
    }

}
