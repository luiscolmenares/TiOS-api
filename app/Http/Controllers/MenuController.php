<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Menu;
use App\Http\Requests;

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
	    $content = array('about' => 'TIoS Smart Home gives you control of your smart devices in one place.');
	    return $content;
	}
    

}
