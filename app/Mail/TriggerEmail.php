<?php

namespace App\Mail;

use App\Trigger;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TriggerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The trigger instance.
     *
     * @var Trigger
     */
    protected $trigger;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //public function __construct()
    public function __construct(Trigger $trigger) 
    {
        $this->trigger = $trigger;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    if($this->trigger->custommessage != null){
                        $message = $this->trigger->custommessage;
                      } else {
                        $message = "Auto message - Trigger Activated. Trigger ID: ".$this->trigger->id.", Trigger operator: ".$this->trigger->operator.", Trigger value: ".$this->trigger->value.".";
                      }
        return $this->view('emails.trigger')
                ->with([
                    'triggerId' => $this->trigger->id,
                    'triggerName' => $this->trigger->name,
                    'triggerMessage' => $message,

                ]);
    }
}
