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
            'name' => 'Temperature'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Humidity'
        ]);DB::table('datapoint_type')->insert([
            'name' => 'Location'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Level (fluids)'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Light'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Proximity'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Pressure'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Water Quality'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Infra Red'
        ]);
        DB::table('datapoint_type')->insert([
            'name' => 'Chemical/Smoke'
        ]);
    }
}
