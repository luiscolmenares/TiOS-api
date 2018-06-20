<?php

use Illuminate\Database\Seeder;

class DatapointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('datapoints')->insert([
            'name' => 'DPSeed',
            'type' => 'Seed',
            'unitid' => '999',
            'address' => '999',
            'data' => '999',
            'options' => '{seed}',
            'notes' => 'This is a seeder datapoint',
            'active' => 0,
            'datasource_id' => '1' 

        ]);
    }
}
