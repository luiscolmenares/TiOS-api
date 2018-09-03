<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Menu;
use App\Http\Requests;
use Illuminate\Routing\UrlGenerator;

class MenuController extends Controller
{
    /**
	* Get Menu Items.
	* @param 
	* return items
	*/
	public function getMenuItems($role_id){
	    $menuitems = Menu::where('role_id', $role_id)->orderBy('menu_order', 'asc')->get();
	    $menuitems = array("menuitems" => $menuitems);
	    return $menuitems;
	}

	/**
	* Get about content .
	* @param 
	* return items
	*/
	public function getMobileAbout(){
		$url = url('/');
	    $content = array(
	    	'icon' => $url.'/spaces/icons/about-us.png',
	    	'about' => 'TIoS Smart Home gives you control of your smart devices in one place.');
	    return $content;
	}

	/**
*
* @SWG\Post(
*      path="/mobile/support/create",
*      tags={"Support"},
*      operationId="createMobileSupport",
*      summary="Create new Mobile Support entry",
*      @SWG\Parameter(
*          name="body",
*          in="body",
*          description="JSON Payload",
*          required=true,
*          type="json",
*          format="application/json",
*          @SWG\Schema(
*              type="object",
*              @SWG\Property(property="user_id", description="user id", type="integer", example="1"),
*              @SWG\Property(property="subject", description="Message Subject", type="string", example="RE:Subject"),
*              @SWG\Property(property="message", type="Message", example="Great work!"),
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
public function createMobileSupport(Request $request)
{
    $data = \DB::table('mobile_support')->insert(
        ['user_id'=>$request->user_id,
        'subject'=>$request->subject,
        'message'=>$request->message,
        'timestamp'=>\Carbon\Carbon::now(),
        ]
    );

     if (!$data) {
	        abort(500, 'Could not save space.');
	    }
	    else {
	    	return array(true, "Support message created");
	    }
}
    

}
