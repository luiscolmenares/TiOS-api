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
            'name' => 'Smart Bulb',
            'codename' => 'ds-smart-bulb'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Smart Switch',
            'codename' => 'ds-smart-switch'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Temperature Sensor (Celsius)',
            'codename' => 'ds-temperature-celsius'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Temperature Sensor (Farenheit)',
            'codename' => 'ds-temperature-celsius-farenheit'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Humidity Sensor',
            'codename' => 'ds-humidity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Proximity Sensor',
            'codename' => 'ds-proximity'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Flood Sensor',
            'codename' => 'ds-flood'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Voltage (V)',
            'codename' => 'ds-voltage'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Electric Current (A)',
            'codename' => 'ds-current'

        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Electric Power (W)',
            'codename' => 'ds-power'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Electric Energy (E)',
            'codename' => 'ds-energy'
        ]);
    }
}
