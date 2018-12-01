<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Twilio;
use App\EventDB;
use App\User;
use App\DatasourceSensorData;
use App\Trigger;
use App\Datasource;
use App\Mail\EventEmail;
use App\Mail\TriggerEmail;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Kernel extends ConsoleKernel
{
/**
* The Artisan commands provided by your application.
*
* @var array
*/
protected $commands = [
//
];

/**
* Define the application's command schedule.
*
* @param  \Illuminate\Console\Scheduling\Schedule  $schedule
* @return void
*/
protected function schedule(Schedule $schedule)
{
// $schedule->command('inspire')
//          ->hourly();

// Check if there is any trigger for the past minute
    $schedule->call(function(){
        $message = "";
        $triggers = Trigger::where('active', 1)->get();
// console.log($triggers);
// error_log($triggers);
        $now =  \Carbon\Carbon::now()->format('Y-m-d H:i');
        $now_timestamp =  strtotime($now);
        $one_min_ago = $now_timestamp - 60;

        //get datasources Sensor data for the last minute

        $lastmindatasourcesensordatas = DatasourceSensorData::whereBetween('timestamp', [$one_min_ago, $now_timestamp])->get();

        foreach ($triggers as $trigger) {

            $sendemailcount = 0;
            $smscount = 0;
            $systemnotificationcount = 0;
            $turnoffcount = 0;
            $turnoncount = 0;
            $newvaluecount = 0;

/* Check type of action: 
* 1- Send email
* 2- SMS
* 3- System Notification
* 4- Turn off
* 5- Turn on
* 6- new value
*/

foreach ($lastmindatasourcesensordatas as $lastmindatasourcesensordata) {
    if($trigger->datasource_id = $lastmindatasourcesensordata->datasource_id){
        switch ($trigger->operator) {
            case '<':
            if($lastmindatasourcesensordata->value < $trigger->value){

                //1. Send Email
                if($trigger->trigger_action_type_id == 1){
                    //Count for numbers of times the trigger has been activated for the last minute
                    $sendemailcount = $sendemailcount + 1;

                    if($sendemailcount == 3){                    
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {
                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');
                            } else {
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send mail using Mailgun
                                \Mail::to($user)->send(new TriggerEmail($trigger));    

                            }

                        }


                    }

                }
                //2. Send SMS
                if($trigger->trigger_action_type_id == 2){
                    $smscount = $smscount +1;

                    if($smscount ==3){

                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is less than trigger value (".$trigger->value.")";
                        }
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {

                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            error_log(count($user));

                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');

                            } else {
                                $user_phone = $user[0]->phone;
                                error_log($user_phone);
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send to SMS using Twillio
                                Twilio::message($user_phone, $message);

                            }

                        }    

                    }

                }
                //3. Send Push Notification
                if($trigger->trigger_action_type_id == 3){
                    $systemnotificationcount = $systemnotificationcount +1;

                    if ($systemnotificationcount == 3){
                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is less than trigger value (".$trigger->value.")";
                        }
                        $tokenList = $this->getTokenListfromRecipients($trigger->recipients);

                        //Save in triggers_notifications table
                        app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);

                        //Send to pusher for web notifications
                        // $pusher->trigger('my-channel', 'my-event', $message);

                        //Send to firebase for Android Push Notifications
                        app('App\Http\Controllers\UserController')->pushNotification($tokenList, $message);
                    }

                }
                //4. Turn off
                if($trigger->trigger_action_type_id == 4){
                    $turnoffcount = $turnoffcount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    if($turnoffcount == 3){

                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=OFF';
                        error_log('----');
                        error_log($url_on);
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }


                }

                //5. Turn on
                if($trigger->trigger_action_type_id == 5){
                    $turnoncount = $turnoncount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    if ($turnoncount == 3){


                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($options);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=ON';
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }

                }

                //6. New Value
                if($trigger->trigger_action_type_id == 6){

                    $newvaluecount = $newvaluecount +1;

                    if($newvaluecount == 3){
                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($trigger->act_new_value);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value='.$trigger->act_new_value;
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }                             

                }

            }
            break;


            case '>':

            if($lastmindatasourcesensordata->value > $trigger->value){

                //1. Send Email
                if($trigger->trigger_action_type_id == 1){
                    //Count for numbers of times the trigger has been activated for the last minute
                    $sendemailcount = $sendemailcount + 1;

                    if($sendemailcount == 3){                    
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {
                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');
                            } else {
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send mail using Mailgun
                                \Mail::to($user)->send(new TriggerEmail($trigger));    

                            }

                        }


                    }

                }
                //2. Send SMS
                if($trigger->trigger_action_type_id == 2){
                    $smscount = $smscount +1;

                    if($smscount ==3){

                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is more than trigger value (".$trigger->value.")";
                        }
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {

                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            error_log(count($user));

                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');

                            } else {
                                $user_phone = $user[0]->phone;
                                error_log($user_phone);
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send to SMS using Twillio
                                Twilio::message($user_phone, $message);

                            }

                        }    

                    }

                }
                //3. Send Push Notification
                if($trigger->trigger_action_type_id == 3){
                    $systemnotificationcount = $systemnotificationcount +1;

                    if ($systemnotificationcount == 3){
                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is more than trigger value (".$trigger->value.")";
                        }
                        $tokenList = $this->getTokenListfromRecipients($trigger->recipients);

                        //Save in triggers_notifications table
                        app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);

                        //Send to pusher for web notifications
                        // $pusher->trigger('my-channel', 'my-event', $message);

                        //Send to firebase for Android Push Notifications
                        app('App\Http\Controllers\UserController')->pushNotification($tokenList, $message);
                    }

                }
                //4. Turn off
                if($trigger->trigger_action_type_id == 4){
                    $turnoffcount = $turnoffcount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    if($turnoffcount == 3){

                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=OFF';
                        error_log('----');
                        error_log($url_on);
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }


                }

                //5. Turn on
                if($trigger->trigger_action_type_id == 5){
                    $turnoncount = $turnoncount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    if ($turnoncount == 3){


                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($options);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=ON';
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }

                }

                //6. New Value
                if($trigger->trigger_action_type_id == 6){

                    $newvaluecount = $newvaluecount +1;

                    if($newvaluecount == 3){
                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($trigger->act_new_value);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value='.$trigger->act_new_value;
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    }                             

                }
            }
            break;
            // case '<=':
            // # code...
            // break;
            // case '>=':
            // # code...
            // break;
            case '=':
            error_log('----');
                error_log('case equal before validation');
                error_log('----');
                 error_log($lastmindatasourcesensordata->id);
                 error_log($lastmindatasourcesensordata->value."=".$trigger->value."?");
            if($lastmindatasourcesensordata->value == $trigger->value){
                error_log('----');
                error_log('case equal');
                error_log('----');

                //1. Send Email
                if($trigger->trigger_action_type_id == 1){
                    //Count for numbers of times the trigger has been activated for the last minute
                    $sendemailcount = $sendemailcount + 1;

                    // if($sendemailcount == 3){                    
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {
                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');
                            } else {
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send mail using Mailgun
                                \Mail::to($user)->send(new TriggerEmail($trigger));    

                            }

                        }


                    }

                // }
                //2. Send SMS
                if($trigger->trigger_action_type_id == 2){
                    // $smscount = $smscount +1;

                    // if($smscount ==3){

                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is more than trigger value (".$trigger->value.")";
                        }
                        $recipients =  app('App\Http\Controllers\SensorController')->getRecipientsArray($trigger->recipients);
                        foreach ($recipients as $recipient_id) {

                            $matchThese = ['id' => $recipient_id, 'active_sms' => '1'];
                            $user = \DB::table('users')
                            ->where($matchThese)
                            ->get();
                            error_log(count($user));

                            if(count($user) == 0){
                                error_log('----');
                                error_log('No SMS sent');

                            } else {
                                $user_phone = $user[0]->phone;
                                error_log($user_phone);
                                app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);                 
                                //Send to SMS using Twillio
                                Twilio::message($user_phone, $message);

                            }

                        }    

                    // }

                }
                //3. Send Push Notification
                if($trigger->trigger_action_type_id == 3){
                    // $systemnotificationcount = $systemnotificationcount +1;
                    error_log('----');
                    error_log('Send Push Notification');
                    error_log('----');
                    // if ($systemnotificationcount == 3){
                        if($trigger->custommessage != null){
                            $message = $trigger->custommessage;
                        } else {
                            $message = $trigger->name.": Sensor value (".$lastmindatasourcesensordata->value.") is more than trigger value (".$trigger->value.")";
                        }
                        $tokenList = $this->getTokenListfromRecipients($trigger->recipients);
                        error_log('----');
                        error_log('tokenList');
                        error_log(json_decode($tokenList));
                        //Save in triggers_notifications table
                        app('App\Http\Controllers\SensorController')->saveTrigger($trigger, $message);

                        //Send to pusher for web notifications
                        // $pusher->trigger('my-channel', 'my-event', $message);

                        //Send to firebase for Android Push Notifications
                        app('App\Http\Controllers\UserController')->pushNotification($tokenList, $message);
                    // }

                }
                //4. Turn off
                if($trigger->trigger_action_type_id == 4){
                    // $turnoffcount = $turnoffcount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    // if($turnoffcount == 3){

                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=OFF';
                        error_log('----');
                        error_log($url_on);
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    // }


                }

                //5. Turn on
                if($trigger->trigger_action_type_id == 5){
                    // $turnoncount = $turnoncount +1;

                    //Trigger ONLY when there are at least 3 values that triggers the action

                    // if ($turnoncount == 3){


                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($options);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value=ON';
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    // }

                }

                //6. New Value
                if($trigger->trigger_action_type_id == 6){

                    // $newvaluecount = $newvaluecount +1;

                    // if($newvaluecount == 3){
                        $act_datasource = Datasource::find($trigger->act_datasource_id);
                        $noderedurl = "https://node-red.tiosplatform.com:1080";
                        $options = json_decode($act_datasource->options, true);
                        error_log('----');
                        error_log($trigger->act_new_value);
                        $url_on = $noderedurl.'/thingstatus?topic='.$options['topic'].'/control&value='.$trigger->act_new_value;
                        $client = new Client(); //GuzzleHttp\Cliente
                        $response = $client->get($url_on);

                    // }                             

                }

            }
            # code...
            break;
            // case '><':
            // # code...
            // break;

            default:
            # code...
            break;
            }
            }

            }


}

})->everyMinute();


//Events 
$schedule->call(function () {
    $now =  \Carbon\Carbon::now()->format('Y-m-d H:i');
    $now_timestamp =  strtotime($now);
    $events = \DB::table('events')
//->where('events.deleted_at', '=', null)
    ->select('events.id', 'events.title', 'events.description', 'events.action', 'events.valueFrom', 'events.organization_id', 'events.project_id', 'events.datasource_id', 'events.datapoint_id', 'events.active', 'events.created_at')
    ->where([
        ['active', '>', 0],
        ['valueFrom', '=', $now_timestamp],
    ])
    ->get();
    foreach ($events as $activeevent) {
        $noderedurl = "https://node-red.tiosplatform.com:1080";
/* Check what action is needed
* send-email
* sms-message
* turn-on
* turn-off
* new-value
*/
$action = json_decode($activeevent->action, true);
//$recipients = $this->getEventsRecipientsArray($action[0]['recipients']);
switch ($action['action']) {
    case 'send-email':
    $recipients = $this->getEventsRecipientsArray($action['recipients']);
    foreach ($recipients as $recipient_id) {
        $user = User::find($recipient_id);
//Update Event Record
        $this->disableEvent($activeevent->id);  
//Send to Mail 
        \Mail::to($user)->send(new EventEmail(EventDB::find($activeevent->id)));                     
    }
    break;
    case 'sms-message':
    $recipients = $this->getEventsRecipientsArray($action['recipients']);
    foreach ($recipients as $recipient_id) {
        $user = User::find($recipient_id);
//Save in triggers_notifiactions table
        $this->disableEvent($activeevent->id);                    
//Send to Twillio
        Twilio::message($user->phone, $action['message']);
    }    
    case 'system-notification':
    $recipients = $this->getEventsRecipientsArray($action['recipients']);
    foreach ($recipients as $recipient_id) {
        $user = User::find($recipient_id);
//Save in triggers_notifiactions table
        $this->disableEvent($activeevent->id);                    
//Send to Twillio
        Twilio::message($user->phone, $action['message']);
    }    
//Twilio::message('+17862032077', $action[0]['message']);
    break;
    case 'turn-off':
//     # code...
    $this->disableEvent($activeevent->id);
// $url_off = $action[0]['broker'].'/thingstatus?topic='.$action[0]['topic'].'&value=OFF';
    $url_off = $noderedurl.'/thingstatus?topic='.$action['topic'].'/control&value=OFF';
$client = new Client(); //GuzzleHttp\Client
$response = $client->get($url_off);
// $response = $client->get('http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=0');
break;
case 'turn-on':
# code...
// $url_on = $action[0]['broker'].'/thingstatus?topic='.$action[0]['topic'].'&value=ON';
$url_on = $noderedurl.'/thingstatus?topic='.$action['topic'].'/control&value=ON';
echo $url_on;
//$url_on = 'http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=1';
$this->disableEvent($activeevent->id);
$client = new Client(); //GuzzleHttp\Cliente
//$response = $client->get('http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=1');
$response = $client->get($url_on);
//Twilio::message('+17862032077',$url_on);
break;
case 'new-value':
$url_on = $noderedurl.'/thingstatus?topic='.$action['topic'].'/control&value='.$action['new-value'];
$client = new Client(); //GuzzleHttp\Cliente
$response = $client->get($url_on);
$this->disableEvent($activeevent->id);
# code...
break;
default:
# code...
break;
}


}




})->everyMinute();
}



/**
* Register the Closure based commands for the application.
*
* @return void
*/
protected function commands()
{
    require base_path('routes/console.php');
}

/**
* get recipients array
* @param $recipients
* return array
*/
public function getEventsRecipientsArray($recipients){
    $recipients = ltrim($recipients, '[');
    $recipients = ltrim($recipients, ']');
    $recipients_array = explode(',', $recipients);
    return $recipients_array;
}

/**
* disable event
* @param $recipients
* return boolean
*/
public function disableEvent($eventDBid){
    \DB::table('events')
    ->where('id', $eventDBid)
    ->update(['active' => 0]);
    return true;
}

/**
* get Token List from recipients
* @param $recipients
* return array
*/
public function getTokenListfromRecipients($recipients){
    $recipients = ltrim($recipients, '[');
    $recipients = ltrim($recipients, ']');
    $tokenList = array();
    $recipients_array = explode(',', $recipients);
    foreach ($recipients_array as $recipient) {
        $tokens =  \DB::table('users_device_tokens')
        ->where('users_device_tokens.user_id', $recipient)
        ->join('users', 'users.id', '=','users_device_tokens.user_id')
        ->where('users.active_push', '=', '1')
        ->select('users_device_tokens.*', 'users.*')
        ->get();
        error_log('----');
        error_log('tokens');
        error_log($tokens);
        error_log('----');

        foreach ($tokens as $token) {
            array_push($tokenList, $token->device_token);
        }

    }

    return $tokenList;
}
}
