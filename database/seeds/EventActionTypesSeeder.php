<?php

use Illuminate\Database\Seeder;

class EventActionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_action_types')->insert([
            'name' => 'send-email',
            'description' => 'Send Email',
        ]);
        DB::table('event_action_types')->insert([
            'name' => 'send-sms-message',
            'description' => 'Send SMS Message',
        ]);
        DB::table('event_action_types')->insert([
            'name' => 'system-notification',
            'description' => 'Send System Notification',
        ]);
        DB::table('event_action_types')->insert([
            'name' => 'turn-off',
            'description' => 'Turn Off Smart Device (control event)',
        ]);
        DB::table('event_action_types')->insert([
            'name' => 'turn-on',
            'description' => 'Turn On Smart Device (control event)',
        ]);
        DB::table('event_action_types')->insert([
            'name' => 'new-value',
            'description' => 'New value For Smart Device (control event)',
        ]);
    }
}
