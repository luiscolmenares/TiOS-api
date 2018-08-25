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
            'codename' => 'ds-vemetris-gateway'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Bulb',
            'codename' => 'ds-smart-bulb'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Light)',
            'codename' => 'ds-smart-switch-light'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (AC)',
            'codename' => 'ds-smart-switch-ac'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Water Valve)',
            'codename' => 'ds-smart-switch-wv'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Control: Smart Switch (Gas Valve)',
            'codename' => 'ds-smart-switch-gv'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Temperature Sensor (Celsius)',
            'codename' => 'ds-temperature-celsius'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Temperature Sensor (Farenheit)',
            'codename' => 'ds-temperature-celsius-farenheit'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Humidity Sensor',
            'codename' => 'ds-humidity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Proximity Sensor',
            'codename' => 'ds-proximity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Flood Sensor',
            'codename' => 'ds-flood'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Voltage (V)',
            'codename' => 'ds-voltage'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Current (A)',
            'codename' => 'ds-current'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Power (W)',
            'codename' => 'ds-power'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Monitor: Electric Energy (E)',
            'codename' => 'ds-energy'
        ]);
    }
}
