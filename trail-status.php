<?php
/* Show Trail Statuses
  Plugin Name: Trail Status
  Plugin URI: http://onthegridwebdesign.com/software/trail-status
  Version: 2.2
  Description: Show the current status of trails.
  Author: On the Grid Web Design LLC
  Author URI: http://onthegridwebdesign.com
  Copyright: (c) 2015-2024, On the Grid Web Design LLC
  Package: com.onthegridwebdesign.trailstatus2
  License: GPLv3
  Updated: 9/10/2024 Created: 11/12/2015
 */

// ****** Table Names *****
global $wpdb;
$table_trails = $wpdb->prefix . 'otgts2_trails';
$table_statuses = $wpdb->prefix . 'otgts2_statuses';
define('OTGTS2_ROOT_PATH',  plugin_dir_path(__FILE__));

// ***** Register Stuff *****
register_activation_hook(__FILE__, 'otgts2_install');
add_action('wp_loaded', 'otgts2_scripts');
add_action('widgets_init', 'otgts2_load_widgets');
if (is_admin()) {
	if (!session_id()) session_start(); // For storing list options
	require_once(OTGTS2_ROOT_PATH . 'admin.php');
	add_action('admin_enqueue_scripts', 'otgts2_admin_load_styles_and_scripts');
	add_action('admin_menu', 'otgts2_admin');
	add_action('wp_ajax_otgts2_update_status', 'otgts2_update_status');
} else {
	require_once(OTGTS2_ROOT_PATH . 'shortcode.php');
	add_shortcode('trail-status', 'otgts2_sc_table');
	add_shortcode('trail-status-blocks', 'otgts2_sc_blocks');
}

/** Register the Widgets
 */
function otgts2_load_widgets() {
	require_once(OTGTS2_ROOT_PATH . 'widgets.php');
	register_widget('otgts2_widget');
}

/** Load CSS and JS Files
 */
function otgts2_scripts () {
	wp_register_style('otgts2_css', plugins_url('trail-status.min.css', __FILE__));
	wp_enqueue_style('otgts2_css');
}

/** Stuff to Do on Activation 
 * Add Tables to Database, Add basic statuses
 * @global type $wpdb
 */
function otgts2_install () {
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$table_trails = $wpdb->prefix . 'otgts2_trails';
	$table_statuses = $wpdb->prefix . 'otgts2_statuses';
	$charset_collate = $wpdb->get_charset_collate();

	// ***** Add Tables *****
	$sql_trails = "CREATE TABLE $table_trails (
		trail_id mediumint(9) NOT NULL AUTO_INCREMENT,
		created timestamp,
		name varchar(50) NOT NULL,
		link varchar(200),
		image_id int,
		sort_order float,
		show_widget tinyint(1) DEFAULT 1,
		show_shortcode tinyint(1) DEFAULT 1,
		status_id int,
		hidden TINYINT(1) NOT NULL DEFAULT '0',
		UNIQUE KEY trail_id (trail_id)
		) $charset_collate;";
	dbDelta($sql_trails);

	$sql_status = "CREATE TABLE $table_statuses (
		status_id mediumint(9) NOT NULL AUTO_INCREMENT,
		name varchar(50) NOT NULL,
		sort_order float,
		color varchar(20),
		UNIQUE KEY status_id (status_id)
		) $charset_collate;";
	dbDelta($sql_status);
	
	// ***** Add Set of Statuses to Start With *****
	$sql_count = "SELECT COUNT(status_id) FROM $table_statuses";
	$status_count = $wpdb->get_var($sql_count);
	if (0 == $status_count) {
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 1, name = 'Unknown', sort_order = 1;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 3, name = 'Dry', sort_order = 3;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 4, name = 'Variable', sort_order=4;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 5, name = 'Wet', sort_order=5;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 6, name = 'Muddy', sort_order=6;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 7, name = 'Snow', sort_order=7;");
		$wpdb->query("INSERT INTO $table_statuses SET status_id = 9, name = 'Icy', sort_order=9;");
	}

}
