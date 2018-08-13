<?php

use Illuminate\Database\Seeder;

class PanelstypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('panels_type')->insert([
            'name' => 'Chart - Lines - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Bars - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Humidity',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'History Log - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'History Log - Humidity',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Gauge - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Gauge - Humidity',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Gauge - Power(Kwh)',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Power Switch',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Lines - Humidity',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Bars - Humidity',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Gauge - Voltage',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Lines - Voltage',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Bars - Voltage',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'History Log - Voltage',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Lines - Power(Kwh)',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Bars - Power(Kwh)',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'History Log - Power(Kwh)',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Widget - Gauge - Electric Current',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Lines - Electric Current',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'Chart - Bars - Electric Current',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'History Log - Electric Current',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'MQTT Widget - Gauge - Temperature',
        ]);
        DB::table('panels_type')->insert([
            'name' => 'MQTT Widget - Temperature',
        ]);


    }
}
