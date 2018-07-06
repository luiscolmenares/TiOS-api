<?php

namespace App\Http\Controllers;
//require __DIR__ . '/vendor/pusher/pusher-php-server/src/Pusher.php';
use App\User;
use App\Trigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Pusher;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio;
use App\Mail\TriggerEmail;
class SensorController extends Controller
{
/**
*
* @SWG\Post(
*      path="/node-red/sensor-data",
*      tags={"Sensors"},
*      operationId="createSensorData",
*      summary="Create new sensordata entry",
*      @SWG\Parameter(
*          name="body",
*          in="body",
*          description="JSON Payload",
*          required=true,
*          type="json",
*          format="application/json",
*          @SWG\Schema(
*              type="object",
*              @SWG\Property(property="name", description="name", type="string", example="ModbusTCP"),
*              @SWG\Property(property="type", description="type", type="string", example="Temperature"),
*              @SWG\Property(property="unitid", type="integer", example="1"),
*              @SWG\Property(property="ip", type="string", example="192.168.1.11"),
*              @SWG\Property(property="port", type="string", example="8899"),
*              @SWG\Property(property="data", type="string", example="23.44"),
*              @SWG\Property(property="fc", type="string", example="FC4"),
*              @SWG\Property(property="address", type="integer", example="0"),
*              @SWG\Property(property="quantity", type="integer", example="1"),
*         

*          )
*
*      ),

*      @SWG\Response(
*          response=200,
*          description="successful operation"
*       ),
*       @SWG\Response(response=400, description="Bad request"),
*       security={
*           {"passport": {}}
*       }
*  )
*
*/ 

public function createSensorData(Request $request)
{
//var timeStamp = Math.floor(Date.now());
    $data = \DB::table('sensordata')->insert(
        ['name'=>$request->name,
        'type'=>$request->type,
        'unitid'=>$request->unitid,
        'ip'=>$request->ip,
        'port'=>$request->port,
        'address'=>$request->address,
        'data'=>$request->data,
        //'data'=>69,
        'fc'=>$request->fc,
        'quantity'=>$request->quantity,
        'created_at' =>  \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
        'datasource' => $request->datasource,
        ]
        );
    //redirect('/pusher');
   //return redirect('/api/pusherapi/hola bebe');
//Check trigger

    /* TODO: NEEDS TO WORK ONLY WITH DATASOURCES AS WELL
    *  return $this->checkIfTrigger($request);
    */
    
    //return $this->checkIfTrigger($request);
  //   $options = array(
  //   'cluster' => 'us2',
  //   'encrypted' => true
  // );
  // $pusher = new Pusher\Pusher(
  //   '0cbe3c892f6f009260b0',
  //   '90fd1ba645c5220b52dd',
  //   '394671',
  //   $options
  // );

  // $datamessage['message'] = 'Sensor Data Added: '.$request->data;
  // $pusher->trigger('my-channel', 'my-event', $datamessage);
}

/**
* Creates  push message
* @param Request $request
* return boolean
*/
public function PushMessage($message)
{
    $options = array(
        'cluster' => 'us2',
        'encrypted' => true
      );
      $pusher = new Pusher\Pusher(
        '0cbe3c892f6f009260b0',
        '90fd1ba645c5220b52dd',
        '394671',
        $options
      );

      $data['message'] = $message;
      $pusher->trigger('my-channel', 'my-event', $data);
}


/**
* Creates data (test)
* @param Request $request
* return boolean
*/
public function createData(Request $request)
{
    $data = \DB::table('mydata')->insert(
        ['data' => $request->data]
        );
}

/**
* Create Data (IOBroker Test)
* @param Request $request
* return datapoint
*/
public function createNode(Request $request)
{
    $datapoint = new Datapoint($request->all());
    if (!$datapoint->save()) {
        abort(500, 'Could not save datapoint.');
    }
    return $datapoint;
}

/**
* Get all sensor data related to the datapoint.
* @param $type, $address, $ip, $port
* return mixed
*/
public function getSensorData($address, $ip, $port, $type, $unitid){
    $sensordata = \DB::table('sensordata')
    ->select('created_at', 'data')
    ->where([
        ['type', '=', $type],
        ['unitid', '=', $unitid],
        ['address', '=', $address],
        ['ip', '=', $ip],
        ['port', '=', $port],
        ])
    ->take(20)
    //->orderBy('created_at', 'asc')
    ->get();
    return $sensordata;
}

/**
* Get last sensor data related to the datapoint.
* @param $type, $address, $ip, $port
* return mixed
*/
public function getLastSensorData($address, $ip, $port, $type, $unitid){
    $lastsensordata = \DB::table('sensordata')
    ->select('created_at', 'data')
    ->where([
        ['type', '=', $type],
        ['unitid', '=', $unitid],
        ['address', '=', $address],
        ['ip', '=', $ip],
        ['port', '=', $port],
        ])
    ->orderBy('created_at', 'desc')
    ->first();
    return $lastsensordata->data;
}

/**
* Check if trigger
* @param 
* return triggers
*/
public function checkIfTrigger($sensordata){
    //Get Project ID
    $project_id = $this->getProjectIdBySensordata($sensordata);
    //Get Datapoint ID
    $datapoint_id = $this->getDatapointIdBySensordata($sensordata);
    //Get ID of logged user (recipient)
    //$user_id = auth()->guard('api')->user()->id; 
    //Get Triggers by datapoint
    $triggers = app('App\Http\Controllers\TriggerController')->GetTriggersByDatapointId($datapoint_id);
    //Get triggers by Recipient Id
    //return $datapoint_id;

    $triggersarray = json_decode(json_encode($triggers), true);
    //Test Pusher
    $options = array(
    'cluster' => 'us2',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    '0cbe3c892f6f009260b0',
    '90fd1ba645c5220b52dd',
    '394671',
    $options
  );
  //return $datapoint_id;
  // $datamessage['message'] = 'Sensor Data Added: '.$sensordata->data;
  // //$datamessage['message'] = 'Sensor Data Added: '.$sensordata->data;
  // $pusher->trigger('my-channel', 'my-event', $datamessage);
  //Test Pusher
     //$triggers_recipient =  $this->getTriggersByRecipientId($triggers, );
     //return $triggers;
  //$user = Auth::guard()->user();
  
  //return $user_id;
  //return $currentuser;
  $message = "";
     foreach ($triggers as $trigger) {
        foreach ($trigger as $tig) {
            # code...
            //return $tig->operator;
            

            switch ($tig->operator) {
                case '>':
                //First, check if condition applies
                 if ($sensordata->data > $tig->value){
                    /* Check type of message: 
                     * 1- Send email
                     * 2- SMS
                     * 3- System Notification
                     */
                    //$recipient = $this->isRecipient($tig->recipients, $user_id);
                    //1. Send Email
                    if($tig->trigger_action_type_id == 1){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value more than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }                       
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Mail 
                            \Mail::to($user)->send(new TriggerEmail(Trigger::find($tig->id)));                     
                        }
                    }
                    //2. Send SMS
                    if($tig->trigger_action_type_id == 2){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value more than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                        
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);                  
                           //Send to Twillio
                            Twilio::message($user->phone, $message);
                        }                     
                    }
                    //3. Send Push Notification
                    if($tig->trigger_action_type_id == 3){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value more than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                         $datamessage['message'] = $message;
                         $datamessage['recipients'] = $tig->recipients;
                         //Save in triggers_notifiactions table
                          $this->saveTrigger($tig, $message);                  
                         //Send to pusher
                         $pusher->trigger('my-channel', 'my-event', $datamessage);
                    }
                 }
                    break;
                 case '<':
                 if ($sensordata->data < $tig->value){             
                    /* Check type of message: 
                     * 1- Send email
                     * 2- SMS
                     * 3- System Notification
                     */
                    //$recipient = $this->isRecipient($tig->recipients, $user_id);
                    //1. Send Email
                    if($tig->trigger_action_type_id == 1){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value less than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }                       
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Mail 
                            \Mail::to($user)->send(new TriggerEmail(Trigger::find($tig->id)));                     
                        }
                    }
                    //2. Send SMS
                    if($tig->trigger_action_type_id == 2){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value less than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                        
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Twillio 
                            Twilio::message($user->phone, $message);
                        }                     
                    }
                    //3. Send Push Notification
                    if($tig->trigger_action_type_id == 3){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value less than trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                         $datamessage['message'] = $message;
                         $datamessage['recipients'] = $tig->recipients;
                         //Save in triggers_notifiactions table
                          $this->saveTrigger($tig, $message);                  
                         //Send to pusher
                         $pusher->trigger('my-channel', 'my-event', $datamessage);
                    }
                 
                 }
                    break;
                // case '≥':
                //     # code...
                //  if ($tig->value >= $sensordata->data){
                //     $message = "Trigger Activated, trigger value more or equal than sensor value Trigger ID = ".$tig->id."  Trigger operator = ".$tig->operator.",  Trigger value =".$tig->value.", Sensor Value = ".$sensordata->data;
                //  }
                //     break;
                // case '≤':
                //     # code...
                //  if ($tig->value <= $sensordata->data){
                //     $message = "Trigger Activated, trigger value less or equal than sensor value Trigger ID = ".$tig->id."  Trigger operator = ".$tig->operator.",  Trigger value =".$tig->value.", Sensor Value = ".$sensordata->data;
                //  }
                //     break;
                case '=':
                 if ($sensordata->data == $tig->value){
                    /* Check type of message: 
                     * 1- Send email
                     * 2- SMS
                     * 3- System Notification
                     */
                    //$recipient = $this->isRecipient($tig->recipients, $user_id);
                    //1. Send Email
                    if($tig->trigger_action_type_id == 1){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value equals trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }                      
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Mail 
                            \Mail::to($user)->send(new TriggerEmail(Trigger::find($tig->id)));                           
                        }
                    }
                    //1. Send SMS
                    if($tig->trigger_action_type_id == 2){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value equals trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Twillio 
                            Twilio::message($user->phone, $message);
                        }                    
                    }
                    //3. Send Push Notification
                    if($tig->trigger_action_type_id == 3){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value equals trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                         $datamessage['message'] = $message;
                         $datamessage['recipients'] = $tig->recipients;
                         //Save in triggers_notifiactions table
                          $this->saveTrigger($tig, $message);                  
                         //Send to pusher
                         $pusher->trigger('my-channel', 'my-event', $datamessage);
                    }
                    
                 }
                    break;

                case '> <':
                //we need to get the values
                $valuearray = explode(",", $tig->value);
                //First, check if condition applies
                 if (($sensordata->data > $valuearray[0]) && ($sensordata->data < $valuearray[1])){
                    /* Check type of message: 
                     * 1- Send email
                     * 2- SMS
                     * 3- System Notification
                     */
                    //$recipient = $this->isRecipient($tig->recipients, $user_id);
                    //1. Send Email
                    if($tig->trigger_action_type_id == 1){
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Mail 
                            \Mail::to($user)->send(new TriggerEmail(Trigger::find($tig->id)));                        
                        }

                    }
                    //2. Send SMS
                    if($tig->trigger_action_type_id == 2){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value equals trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }      
                        $recipients = $this->getRecipientsArray($tig->recipients);
                        foreach ($recipients as $recipient_id) {
                            $user = User::find($recipient_id);
                            //Save in triggers_notifiactions table
                            $this->saveTrigger($tig, $message);  
                            //Send to Twillio 
                            Twilio::message($user->phone, $message);
                        }                    
                    }
                    //3. Send Push Notification
                    if($tig->trigger_action_type_id == 3){
                      if($tig->custommessage != null){
                        $message = $tig->custommessage;
                      } else {
                        $message = "Trigger Activated, sensor value equals trigger value. Trigger ID: ".$tig->id.", Sensor Value: ".$sensordata->data.", Trigger operator: ".$tig->operator.", Trigger value: ".$tig->value;
                      }
                         $datamessage['message'] = $message;
                         $datamessage['recipients'] = $tig->recipients;
                         //Save in triggers_notifiactions table
                          $this->saveTrigger($tig, $message);                  
                         //Send to pusher
                         $pusher->trigger('my-channel', 'my-event', $datamessage);
                    }

                }

                break; 
                
                default:
                    # code...
                    break;
            }
        }
        
     }
     
}

/**
* get Project by Sensordata
* @param $sensordata
* return info
*/
public function getSensordataInfo($sensordata){
    $project = \DB::table('datapoints')
                    ->where('datapoints.type', '=', $sensordata->type)
                    ->where('datapoints.unitid', '=', $sensordata->unitid)
                    ->where('datapoints.address', '=', $sensordata->address)
                    ->join('datasources', 'datapoints.datasource_id', '=', 'datasources.id')
                    ->where('datasources.ip', '=', $sensordata->ip)
                    ->where('datasources.port', '=', $sensordata->port)
                    ->join('projects', 'datasources.project_id', '=', 'projects.id')
                     ->select('projects.*')
                    //->select('datapoints.*', 'datasources.*')
                    ->get();
    //$info = array('project' => $project);
    return $project;
}

/**
* save Trigger to DB
* @param $trigger
* return info
*/
public function saveTrigger($trigger, $message){
   $trigger_notification = \DB::table('triggers_notifications')->insert(
    ['trigger_action_type_id' => $trigger->trigger_action_type_id, 
      'organization_id' => $trigger->organization_id,
      'project_id'=> $trigger->project_id,
      'datasource_id'=> $trigger->datasource_id,
      'datapoint_id'=> $trigger->datapoint_id,
      'message'=> $message,
      'recipients'=> $trigger->recipients,
      'viewed'=> 0,
      'created_at' => \Carbon\Carbon::now(),
      'updated_at' => \Carbon\Carbon::now(),
    ]
    );
    return $trigger_notification;
}

/**
* get Project by Sensordata
* @param $sensordata
* return info
*/
public function getProjectIdBySensordata($sensordata){
    $project_id = \DB::table('datapoints')
                    ->where('datapoints.type', '=', $sensordata->type)
                    ->where('datapoints.unitid', '=', $sensordata->unitid)
                    ->where('datapoints.address', '=', $sensordata->address)
                    ->join('datasources', 'datapoints.datasource_id', '=', 'datasources.id')
                    ->where('datasources.ip', '=', $sensordata->ip)
                    ->where('datasources.port', '=', $sensordata->port)
                    ->join('projects', 'datasources.project_id', '=', 'projects.id')
                     ->select('projects.id')
                     //->pluck('projects.id')
                    //->select('datapoints.*', 'datasources.*')
                    ->get();
                    //->first();
    //$info = array('project' => $project);
    return $project_id[0]->id;
}

/**
* get boolean if recipient
* @param $sensordata
* return info
*/
public function isRecipient($recipients, $user_id){ 
    $recipients = ltrim($recipients, '[');
    $recipients = ltrim($recipients, ']');
    $recipients_array = explode(',', $recipients);
    if (in_array($user_id, $recipients_array)){
         return true;
    } else {
        return false;
    }
   
}
/**
* get recipients array
* @param $recipients
* return array
*/
public function getRecipientsArray($recipients){
    $recipients = ltrim($recipients, '[');
    $recipients = ltrim($recipients, ']');
    $recipients_array = explode(',', $recipients);
    return $recipients_array;
}

/**
* get Datapoint by Sensordata
* @param $sensordata
* return info
*/
public function getDatapointIdBySensordata($sensordata){
    $datapoint_id = \DB::table('datapoints')
                    ->where('datapoints.type', '=', $sensordata->type)
                    ->where('datapoints.unitid', '=', $sensordata->unitid)
                    ->where('datapoints.address', '=', $sensordata->address)
                    ->join('datasources', 'datapoints.datasource_id', '=', 'datasources.id')
                    ->where('datasources.ip', '=', $sensordata->ip)
                    ->where('datasources.port', '=', $sensordata->port)
                    //->join('projects', 'datasources.project_id', '=', 'projects.id')
                     ->select('datapoints.id')
                     //->pluck('projects.id')
                    //->select('datapoints.*', 'datasources.*')
                    ->get();
                    //->first();
    //$info = array('project' => $project);
    return $datapoint_id[0]->id;
}

}
