<?php

use Illuminate\Database\Seeder;

class DatasourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('datasource_type')->insert([
            'name' => 'Vemetris Gateway',
            'codename' => 'ds-vemetris-gateway',
            'icon_image' => 'vemetris-gateway'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Bulb',
            'codename' => 'ds-smart-bulb',
            'icon_image' => 'smartbulb'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Light)',
            'codename' => 'ds-smart-switch-light',
            'icon_image' => 'smartswitchL'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (AC)',
            'codename' => 'ds-smart-switch-ac',
            'icon_image' => 'smartswitchAC'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Water Valve)',
            'codename' => 'ds-smart-switch-wv',
            'icon_image' => 'smartswitchWV'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Gas Valve)',
            'codename' => 'ds-smart-switch-gv',
            'icon_image' => 'smartswitchGV'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Lock)',
            'codename' => 'ds-smart-switch-lock',
            'icon_image' => 'smartswitchLock'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Power)',
            'codename' => 'ds-smart-switch-pw',
            'icon_image' => 'smartswitchPW'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Temperature Sensor (Celsius)',
            'codename' => 'ds-temperature-celsius',
            'icon_image' => 'temperatureC'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Temperature Sensor (Farenheit)',
            'codename' => 'ds-temperature-celsius-farenheit',
            'icon_image' => 'temperatureF'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Humidity Sensor',
            'codename' => 'ds-humidity',
            'icon_image' => 'humidity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Proximity Sensor',
            'codename' => 'ds-proximity',
            'icon_image' => 'proximity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Door Sensor',
            'codename' => 'ds-door',
            'icon_image' => 'door'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Flood Sensor',
            'codename' => 'ds-flood',
            'icon_image' => 'flood'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Voltage (V)',
            'codename' => 'ds-voltage',
            'icon_image' => 'voltage'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Current (A)',
            'codename' => 'ds-current',
            'icon_image' => 'current'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Power (W)',
            'codename' => 'ds-power',
            'icon_image' => 'power'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Energy (E)',
            'codename' => 'ds-energy',
            'icon_image' => 'energy'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Energy (kWh)',
            'codename' => 'ds-kwhenergy',
            'icon_image' => 'kwhenergy'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Apparent power (KVA)',
            'codename' => 'ds-apower',
            'icon_image' => 'ds-apower'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Real power (KW)',
            'codename' => 'ds-rpower',
            'icon_image' => 'ds-rpower'
        ]);
    }
}
