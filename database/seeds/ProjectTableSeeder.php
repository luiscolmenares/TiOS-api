<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
            'name' => 'Project Seed',
            'notes' => 'Seed',
            'active' => 0,
            'organization_id' => 1

        ]);
    }
}
