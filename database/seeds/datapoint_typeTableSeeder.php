<?php

use Illuminate\Database\Seeder;

class datapoint_typeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('datapoint_type')->insert([
            'name' => 'Temperature',
            'codename' => 'dp-temperature'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Humidity',
            'codename' => 'dp-humidity'
        ]);DB::table('datapoint_type')->insert([
            'name' => 'Location',
            'codename' => 'dp-location'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Level (fluids)',
            'codename' => 'dp-flood'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Light',
            'codename' => 'dp-light'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Proximity',
            'codename' => 'dp-proximity'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Pressure',
            'codename' => 'dp-pressure'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Water Quality',
            'codename' => 'dp-water'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Infra Red',
            'codename' => 'dp-infrared'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Chemical/Smoke',
            'codename' => 'dp-smoke'
        ]);
    }
}
