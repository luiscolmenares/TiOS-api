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
            'name' => 'Gateway (Raspberry PI)'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'Smart Gateway'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'Smart Bulb'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Temperature Sensor (Celsius)'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Temperature Sensor (Farenheit)'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Humidity Sensor'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Proximity Sensor'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Flood Sensor'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Voltage (V)'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Electric Current (A)'
        ]);
        DB::table('datasource_type')->insert([
            'name' => 'MQTT Electric Power (W)'
        ]);
         DB::table('datasource_type')->insert([
            'name' => 'MQTT Electric Energy (E)'
        ]);
    }
}
