<?php

use Illuminate\Database\Seeder;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operators')->insert([
            'name' => '> (Greater than)',
            'value' => '>',
            'active' => 1,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
         DB::table('operators')->insert([
            'name' => '< (Less than)',
            'value' => '<',
            'active' => 1,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
         DB::table('operators')->insert([
            'name' => '≥ (Greater than or equal to)',
            'value' => '≥',
            'active' => 0,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
         DB::table('operators')->insert([
            'name' => '≤ (Less than or equal to)',
            'value' => '≤',
            'active' => 0,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
         DB::table('operators')->insert([
            'name' => '= (Equals)',
            'value' => '=',
            'active' => 1,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
         DB::table('operators')->insert([
            'name' => '> < (Between)',
            'value' => '> <',
            'active' => 1,
            'created_at' => '2017-04-13 02:40:09',
            'updated_at' => '2017-04-13 02:40:09',
        ]);
    }
}
