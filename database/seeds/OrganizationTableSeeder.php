<?php

use Illuminate\Database\Seeder;

class OrganizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Organization
        \App\Organization::create([
            'name' => 'Organization One',
            'address' => '742 Evergreen Terrace',
            'address2' => '',
            'phone' => '1234567890',
            'notes' => 'Organization Seeder',
            'active' => 1,
        ]);
    }
}
