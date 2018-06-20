<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
        	'name' => 'Super Admin',
        	'email' => 'super@admin.com',
        	'phone' => '123-456789',
            'active' => 1,
            'role_id' => 1,
            'organization_id' => 1,
            'active_sms' => 1,
            'active_email' => 1,
        	'password' => Hash::make('super355453'),
        	]);
    }
}
