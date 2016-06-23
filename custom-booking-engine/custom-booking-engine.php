<?php
/*
Plugin Name: Custom Booking Engine
Plugin URI: http://luxuryboatsyndicates.com.au/
Description: A custom Booking Engine for booking boats and services
Author: Luxury Boat Syndicates
Author URI: http://luxuryboatsyndicates.com.au/
Version: 1.0
*/

function custom_booking_engine_scripts() {
	
}

add_action( 'wp_enqueue_scripts', 'custom_booking_engine_scripts' );

function custom_booking_engine_scripts_admin() {
	wp_register_script( 'custom_js', plugin_dir_url( __FILE__ ) .'admin/admin-js.js' );
    wp_enqueue_script( 'custom_js' );
    wp_localize_script( 'custom_js', 'customAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
}
add_action( 'admin_enqueue_scripts', 'custom_booking_engine_scripts_admin' );

include('includes/templates/syndicate-list.php');
include('includes/templates/owner-list.php');
include('includes/templates/fullview-booking.php');
include('includes/templates/fullview-defect.php');
include('includes/templates/management-area.php');


include_once('includes/tableInstall.php');
include('includes/PageTemplater.php');

register_activation_hook(__FILE__,'InstallTable');

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
	add_menu_page('Booking Engine', 'Custom Booking Engine', 'manage_options', 'bookingengine-handle', 'custom_function');	
}

function custom_function(){
	echo "<style>";
	echo ".scode { background: #fff!important; width: 60%; } ";
	echo ".lbl { width: 140px; display: inline-block; } ";
	echo ".notes { font-style: italic; color: red; }";
	echo ".btn { border: 2px solid #000;background: #fff;padding: 5px 30px; margin-right: 10px; }";
	echo ".btn:hover { background: #000; color: #fff; } ";
	echo ".txtemail { height: 28px;border: 1px solid #000!important;border-radius: 0px!important;width: 75%;margin-right: 5px; }";
	echo "</style>";
	echo "<h1>Custom Booking Engine</h1>";
	echo "<hr/>";
	echo "<section style='width:45%;background:#eee;padding:10px 15px;float: left;'>";
	echo "<h3>List of ShortCode : </h3>";
	echo "<p class='notes'>Note * : \"function_page_no\" is the Post ID of the Function Page.</p>";
	echo "<hr/>";
	echo "<label class='lbl'>List of Syndicates : </label><input type='text' class='scode' value='[syndicate-list function_page_no=\"509\"]' readonly>";
	echo "<br/>";
	echo "<label class='lbl'>List of Owners : </label><input type='text' class='scode' value='[owner-list function_page_no=\"509\"]' readonly>";
	echo "<br/>";
	echo "<label class='lbl'>Full view Booking : </label><input type='text' class='scode' value='[fullview-booking function_page_no=\"509\"]' readonly>";
	echo "<br/>";
	echo "<label class='lbl'>Full view Defect : </label><input type='text' class='scode' value='[fullview-defect function_page_no=\"509\"]' readonly>";
	echo "</section>";
	echo "<section style='width: 45%;float: left;background: #eee;margin-left: 2%;padding: 10px 15px;'>";
	echo "<h3>List of Admins : </h3>";
	echo "<button class='btn btn-add'>Add</button>";
	echo "<button class='btn btn-save'>Save</button>";
	echo "<hr/>";
	echo 	"<section >";
	echo 		"<ul class='admin-list'>";
	$records = get_option("custom_admin_list");
	for($i=0; $i<count($records); $i++){
		echo 		"<li><input type='text' class='txtemail' value='".$records[$i]."' /><button class='btn btn-remove'>Remove</button></li>";
	}
	echo 		"</ul>";
	echo 	"</section>";
	echo "</section>";

}

add_action("wp_ajax_save_custom", "custom_save_admin");
add_action("wp_ajax_nopriv_save_custom", "custom_save_admin");

function custom_save_admin(){
	$data = $_POST['records'];
	$res;
	if(get_option("custom_admin_list")){
		$res=update_option('custom_admin_list',$data);
	}else{
		$res=add_option('custom_admin_list',$data);
	}
	echo $res;
	die();
}
?>