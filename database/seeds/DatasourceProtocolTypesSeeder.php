<?php

use Illuminate\Database\Seeder;

class DatasourceProtocolTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('datasource_protocol_types')->insert([
            'name' => 'Modbus TCP/IP'
        ]);
        DB::table('datasource_protocol_types')->insert([
            'name' => 'SNMP'
        ]);
        DB::table('datasource_protocol_types')->insert([
            'name' => 'MQTT'
        ]);
    }
}
