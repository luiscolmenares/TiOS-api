<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class NotificationController extends Controller
{
    public function getIndex()
    {
        return view('notification');
    }

    public function postNotify(Request $request)
    {
        $notifyText = e($request->input('notify_text'));

        // TODO: Get Pusher instance from service container
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

			$data['message'] = $notifyText;
			$pusher->trigger('my-channel', 'my-event', $data);

        // TODO: The notification event data should have a property named 'text'

        // TODO: On the 'notifications' channel trigger a 'new-notification' event
    }
}