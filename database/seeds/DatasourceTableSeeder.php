<?php

use Illuminate\Database\Seeder;

class DatasourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('datasources')->insert([
            'name' => 'DSSeed',
            'type' => 'Seed',
            'unitid' => '999',
            'ip' => '0.0.0.0',
            'port' => '999',
            'data' => '999',
            'notes' => 'This is a seeder datasource',
            'active' => 0,
            'project_id' => '1' 

        ]);
    }
}
