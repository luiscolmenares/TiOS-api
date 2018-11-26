<?php

namespace App\Mail;

use App\EventDB;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventEmail extends Mailable
{
    use Queueable, SerializesModels;

/**
* The eventdb instance.
*
* @var eventdb
*/
protected $eventdb;
/**
* Create a new message instance.
*
* @return void
*/
public function __construct(EventDB $eventdb)
{
//
    $this->eventdb = $eventdb;
}

/**
* Build the message.
*
* @return $this
*/
public function build()
{
    if($this->eventdb->custommessage != null){
        $message = $this->eventdb->custommessage;                  
    } else {
        $message = "Auto message - Event Activated. Please login into your TiOS account";
    }

    return $this->view('emails.events')
    ->with([
        'eventId' => $this->eventdb->id,
        'eventTitle' => $this->eventdb->title,
        'eventFromDate' => date("m-d-Y h:i", $this->eventdb->valueFrom),
        'eventMessage' => $message,
    ]);
}
}
