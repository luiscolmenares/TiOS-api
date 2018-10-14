<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Twilio;
use App\EventDB;
use App\User;
use App\Mail\EventEmail;
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
        $noderedurl = "https://node-red.tiosplatform.com:1080"
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
                     $url_off = $noderedurl.'/thingstatus?topic='.$action['topic'].'&value=OFF';
                     $client = new Client(); //GuzzleHttp\Client
                     $response = $client->get($url_off);
                    // $response = $client->get('http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=0');
                        break;
                     case 'turn-on':
                        # code...
                      // $url_on = $action[0]['broker'].'/thingstatus?topic='.$action[0]['topic'].'&value=ON';
                      $url_on = $noderedurl.'/thingstatus?topic='.$action['topic'].'&value=ON';
                     //$url_on = 'http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=1';
                      $this->disableEvent($activeevent->id);
                    $client = new Client(); //GuzzleHttp\Cliente
                    //$response = $client->get('http://ec2-54-208-184-235.compute-1.amazonaws.com:1880/thingstatus?topic=room/b1&value=1');
                    $response = $client->get($url_on);
         //Twilio::message('+17862032077',$url_on);
                        break;
                    case 'new-value':
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
}
