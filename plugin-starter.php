<?php
/**
 * Plugin Name: Cron Starter Demo
 * Plugin URI: http://wpguru.co.uk
 * Description: Demo for recurring functions in WordPress
 * Version: 1.0
 * Author: Jay Versluis
 * Author URI: http://wpguru.co.uk
 * License: GPL2
 */
 
/*  Copyright 2013  Jay Versluis (email support@wpguru.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Add a new submenu under DASHBOARD
function wpguru_plugin_starter_menu() {
	
	// using a wrapper function (easy, but not good for adding JS later - hence not used)
	// add_dashboard_page('Plugin Starter', 'Plugin Starter', 'administrator', 'pluginStarter', 'pluginStarter');
	
	// using array - same outcome, and can call JS with it
	// explained here: http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// and here: http://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/
	global $starter_plugin_admin_page;
	$starter_plugin_admin_page = add_submenu_page ('index.php', __('Plugin Starter', 'plugin-starter'), __('Plugin Starter', 'plugin-starter'), 'manage_options', 'pluginStarter', 'pluginStarter');
}
add_action('admin_menu', 'wpguru_plugin_starter_menu');

// register our JS file
function starter_plugin_admin_init () {
	wp_register_script ('custom-starter-script', plugins_url( '/starter-script.js', __FILE__ ));
}
add_action ('admin_init', 'starter_plugin_admin_init');

// now load the scripts we need
function starter_plugin_admin_scripts ($hook) {
	
	global $starter_plugin_admin_page;
	if ($hook != $starter_plugin_admin_page) {
		return;	
	}
	wp_enqueue_script ('jquery-ui-tabs');
	wp_enqueue_script ('custom-starter-script');
}
// and make sure it loads with our custom script
add_action('admin_enqueue_scripts', 'starter_plugin_admin_scripts');

// link some styles to the admin page
$starterstyles = plugins_url ('starter-styles.css', __FILE__);
wp_enqueue_style ('starterstyles', $starterstyles );

////////////////////////////////////////////
/*         CRON DEMO STARTS HERE           */

// first we create a scheduled event (if it does not exist already)
if( !wp_next_scheduled( 'mycronjob' ) ) {  
   wp_schedule_event( time(), 'daily', 'mycronjob' );  
}

// this is unscheduled in our uninstall.php file 
// to manually unschedule use this:
/*
if( false !== ( $time = wp_next_scheduled( 'mycronjob' ) ) ) {  
   wp_unschedule_event( $time, 'mycronjob' );  
} 
*/

// here's the function we'd like to call with our cron job
function my_repeat_function() {
	
	// do here what needs to be done automatically once a day	
}

// and finally hook that function onto our scheduled event:
add_action( 'mycronjob', 'my_repeat_function' );  

// CUSTOM INTERVALS
// by default we only have hourly, twicedaily and daily as intervals 
// to add your own, use something like this - the example adds 'weekly'
// http://codex.wordpress.org/Function_Reference/wp_get_schedules
 
function cron_add_weekly( $schedules ) {
	// Adds once weekly to the existing schedules.
    $schedules['weekly'] = array(
	    'interval' => 604800,
	    'display' => __( 'Once Weekly' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_weekly' );

/*
more info here:
http://wp.tutsplus.com/tutorials/theme-development/do-it-yourself-wordpress-scheduling-mastering-wp-cron/

THE REST OF THE CODE IS NOT USED FOR THE CRON FUNCTION
*/




////////////////////////////////////////////
// here's the code for the actual admin page
function pluginStarter () {
	
// check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient privileges to access this page. Sorry!') );
    }	
	
	///////////////////////////////////////
	// MAIN AMDIN CONTENT SECTION
	///////////////////////////////////////
	
	// display heading with icon WP style
	?>
    <div class="wrap">
    <div id="icon-index" class="icon32"><br></div>
    <h2>Plugin Starter Options</h2>
    <?php
	
	// let's create jQuery UI Tabs, as demonstrated in the standalone version 
	// or at http://jqueryui.com/tabs/#default
	
	echo '<p>Here are some tabs</p>';
	
	?>
    
      <div id="tabs">
    <ul>
      <li><a href="#tabs-1">Nunc tincidunt</a></li>
      <li><a href="#tabs-2">Proin dolor</a></li>
      <li><a href="#tabs-3">Aenean lacinia</a></li>
    </ul>
    <div id="tabs-1">
    <h3>This is Tab 1</h3>
      <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
    </div>
    <div id="tabs-2">
    <h3>This is Tab 2</h3>
      <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
    </div>
    <div id="tabs-3">
    <h3>This is Tab 3</h3>
      <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
      <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
    </div>
  </div> <!-- end of tabs wrap -->
  
    </div> <!-- end of main wrap -->
    <?php
} // end of main function



?>
