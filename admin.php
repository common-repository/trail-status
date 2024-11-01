<?php
/** Admin Page Controller
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				admin.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			11/12/15
 */

/** Creates the Trail List Page and Handles Bulk Actions and Reordering
 */
function otgts2_trail_list_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGTS2_ROOT_PATH . 'models/trails_model.php');
	$otgts2_Trails_Model = new otgts2_Trails_Model();
	require_once(OTGTS2_ROOT_PATH . 'helpers/view_helper.php');
	
	$message_list = array();
	
	// ***** Run Bulk Actions if Submitted *****
	$bulk_action_list = otgts2_get_request_int_array();
	if (isset($_POST['_wpnonce']) && !empty($bulk_action_list)) {
		// *** Security ***
		check_admin_referer('trails_bulk');
		$action = otgts2_get_request_string('action');

		$trail_list = $otgts2_Trails_Model->get_names_list();
		
		// *** Run The Action ***
		switch ($action) {
			case 'delete':
				foreach ($bulk_action_list as $trail_id) {
					if ($otgts2_Trails_Model->delete($trail_id))
						$message_list[] = [$trail_list[$trail_id] . ' Deleted', 1,5];
					else 
						$message_list[] = ['There was an error deleting ' . $trail_list[$trail_id], 3, 2];
				}
				break;
			case 'hide_on_widget':
				foreach ($bulk_action_list as $trail_id) {
					$result = $otgts2_Trails_Model->set_show_widget($trail_id, 0);
					if ($result)
						$message_list[] = [$trail_list[$trail_id] . ' Hidden on Widget', 1, 5];
					elseif (false === $result)
						$message_list[] = ['There was an error updating ' . $trail_list[$trail_id], 3, 2];
				}
				break;
			case 'show_on_widget':
				foreach ($bulk_action_list as $trail_id) {
					$result = $otgts2_Trails_Model->set_show_widget($trail_id, 1);
					if ($result)
						$message_list[] = [$trail_list[$trail_id] . ' Un-Hidden on Widget', 1, 5];
					elseif (false === $result)
						$message_list[] = ['There was an error updating ' . $trail_list[$trail_id], 3, 2];
				}
				break;
			case 'hide_on_shortcode':
				foreach ($bulk_action_list as $trail_id) {
					$result = $otgts2_Trails_Model->set_show_shortcode($trail_id, 0);
					if ($result)
						$message_list[] = [$trail_list[$trail_id] . ' Hidden on Shortcode', 1, 5];
					elseif (false === $result)
						$message_list[] = ['There was an error updating ' . $trail_list[$trail_id], 3, 2];
				}
				break;
			case 'show_on_shortcode':
				foreach ($bulk_action_list as $trail_id) {
					$result = $otgts2_Trails_Model->set_show_shortcode($trail_id, 1);
					if ($result)
						$message_list[] = [$trail_list[$trail_id] . ' Un-Hidden on Shortcode', 1, 5];
					elseif (false === $result)
						$message_list[] = ['There was an error updating ' . $trail_list[$trail_id], 3, 2];
				}
			 	break;
		}
	}
	
	// ***** Get Data *****
	$table_data = $otgts2_Trails_Model->get_list();

	// ***** Call View *****
	include(OTGTS2_ROOT_PATH . 'views/trail_list.php');
}

/** Add/Edit Trail Info Page
 */
function otgts2_trail_edit_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGTS2_ROOT_PATH . 'models/trails_model.php');
	$otgts2_Trails_Model = new otgts2_Trails_Model();
	require_once(OTGTS2_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGTS2_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();
	
	// ***** Form Submitted *****
	if (isset($_POST['_wpnonce'])) {
		$failure = false;
		// *** Validate & Check ***
		// * Trail Id and Nonce *
		$trail_id = otgts2_get_request_int('trail_id');
		if (empty($trail_id)) { 
			check_admin_referer('trail_add');
		} else {
			check_admin_referer('trail_edit_' . $trail_id);
		}
		
		// * Name Is Required *
		$name = otgts2_get_request_string('name');
		if (empty($name)) {
			$message_list[] = ['Name is Required', 3, 3];
			$failure = true;
		}
		
		// * Yes/No Fields - Defaults to Yes *
		if (empty($_POST['show_shortcode']))
			$show_shortcode = 0;
		else
			$show_shortcode = 1;
		
		if (empty($_POST['show_widget']))
			$show_widget = 0;
		else
			$show_widget = 1;
		
		// * Sanitize Integer Fields *
		$sort_order = otgts2_get_request_int('sort_order', 99);
		$image_id = otgts2_get_request_int('image_id');
		$status_id = otgts2_get_request_int('status_id');

		// * Link: Sanitize and Add http:// if Missing *
		if (!empty($_POST['link'])) {
			$link = $_POST['link'];			
			if (0 != strncasecmp($link, "http://", 7) && 0 != strncasecmp($link, "https://", 8))
				$link = 'http://' . $link;
			$link = filter_var($link, FILTER_SANITIZE_URL);
		} else {
			$link = null;
		}

		// *** Update or Add ***
		if ($trail_id && !$failure) {
			$update_result = $otgts2_Trails_Model->update($trail_id, $name, $link, $image_id, $sort_order, $show_widget, $show_shortcode, $status_id);
			if ($update_result === false) {
				$message_list[] = ['Error Updating ' . $name . ' (#' . $trail_id . ')', 3, 3];
				$failure = true;
			} else {
				$message_list[] = [$name . ' Updated', 1, 5];
			}
		} elseif (!$failure) {
			$add_result = $otgts2_Trails_Model->add($name, $link, $image_id, $sort_order, $show_widget, $show_shortcode);
			if ($add_result) {
				$message_list[] = [$name . ' Added', 1, 5];
			} else {
				$message_list[] = ['Error Adding Trail ' . $name, 3, 3];
				$failure = true;
			}
		}
		
		// *** Views - Back to Form or Trail List ***
		if ($failure) {
			if (!empty($trail_id)) {
				$record['trail_id'] = $trail_id;
			}
			$record['name'] = $name;
			$record['link'] = $link;
			$record['image_id'] = $image_id;
			$record['sort_order'] = $sort_order;
			$record['show_widget'] = $show_widget;
			$record['show_shortcode'] = $show_shortcode;
			$record['status_id'] = $status_id;
			include(OTGTS2_ROOT_PATH . 'views/trail_edit.php');
		} else {
			// ***** View - Trail List *****
			$table_data = $otgts2_Trails_Model->get_list();
			include(OTGTS2_ROOT_PATH . 'views/trail_list.php');
		}
	} else {
		// ***** No Form Submitted *****
		$trail_id = otgts2_get_request_int('trail');
		
		if (empty($trail_id)) {
			$record = ['name'=>'', 'link'=>'', 'image_id'=>'', 'sort_order'=>'', 'show_widget'=>'1', 'show_shortcode'=>'1', 'hidden'=>0, 'status_id'=>0];
		} else {
			$record = $otgts2_Trails_Model->get($trail_id);
		}
		// ***** Call View *****
		include(OTGTS2_ROOT_PATH . 'views/trail_edit.php');
		include(OTGTS2_ROOT_PATH . 'views/about.php');
	}
}

/** List of Statuses Page with Add Form
 */
function otgts2_status_list_page () {
	// ***** Security Check *****
	if (!current_user_can('publish_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGTS2_ROOT_PATH . 'models/status_model.php');
	$otgts2_Status_Model = new otgts2_Status_Model();
	require_once(OTGTS2_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGTS2_ROOT_PATH . 'helpers/filter_helper.php');
	
	$message_list = array();
	
	// ***** Run Bulk Actions if Submitted *****
	$bulk_action_list = otgts2_get_request_int_array();
	if (isset($_POST['_wpnonce']) && !empty($bulk_action_list)) {
		// *** Security ***
		check_admin_referer('status_bulk');
		$action = otgts2_get_request_string('action');
		
		// *** Run The Action ***
		switch ($action) {
			case 'delete':
				$statuses_deleted = 0;
				foreach ($bulk_action_list as $status_id) {
					if ($otgts2_Status_Model->delete($status_id)) {
						$statuses_deleted++;
					} else {
						$message_list[] = ["Could not delete status #$status_id.", 3, 3];
					}
				}
				if (1 == $statuses_deleted)
					$message_list[] = ["Status deleted.", 1, 5];
				else 
					$message_list[] = ["$statuses_deleted statuses deleted.", 1, 5];
				break;
		} 
	}
	
	// ***** Add New Status if Submitted *****
	if (isset($_POST['action']) && 'add' == $_POST['action']) {
		check_admin_referer('status_add');

		// *** Check for Name ***
		$name = otgts2_get_request_string('otgts2_name');
		if (empty($name)) {
			$message_list[] = ['The status needs a name.', 3, 3];
		} else {
			// *** Set Default Values if Not Submitted ***
			$new_status_sort_order = otgts2_get_request_int('otgts2_sort_order', 1);
			$new_status_color = otgts2_get_request_string('otgts2_color', 'black');

			$add_result = $otgts2_Status_Model->add($name, $new_status_sort_order, $new_status_color);
			if ($add_result) $message_list[] = ['Status Added', 1, 5];
			else $message_list[] = ['Problem Adding Status', 3, 3];
		}
	}
	
	// ***** Get Data *****
	$status_list = $otgts2_Status_Model->get_list();

	// ***** Call View *****
	include(OTGTS2_ROOT_PATH . 'views/status_list.php');
	include(OTGTS2_ROOT_PATH . 'views/about.php');
}

/** Ajax Request Handler for Inline Status Updates
 */
function otgts2_update_status () {	
	// ***** Security Check *****
	if (!current_user_can('publish_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	check_ajax_referer('status_bulk', 'wp_nonce');

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGTS2_ROOT_PATH . 'helpers/filter_helper.php');

	// ***** Post Security *****
	$status_id = otgts2_get_request_int('status_id');
	$name = otgts2_get_request_string('name');
	$sort_order = otgts2_get_request_int('sort_order', 99);
	$color = otgts2_get_request_string('color', 'black');
	
	// ***** Update Database if Status Id is Set *****
	if (empty($status_id)) {
		$result = false;
	} else {
		require_once(OTGTS2_ROOT_PATH . 'models/status_model.php');
		$otgts2_Status_Model = new otgts2_Status_Model();
		$result = $otgts2_Status_Model->update($status_id, $name, $sort_order, $color);
	}
	
	// ***** Output to Browser *****
	if ($result)
		echo 'true';
	else
		echo 'false';
	wp_die();
}

/** Update the Current Status of Trails Page
 */
function otgts2_update () {
	// ***** Security Check *****
	if (!current_user_can('publish_posts')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}

	// ***** Load Models, Helpers and Libraries *****
	require_once(OTGTS2_ROOT_PATH . 'models/trails_model.php');
	$otgts2_Trails_Model = new otgts2_Trails_Model();
	require_once(OTGTS2_ROOT_PATH . 'models/status_model.php');
	$otgts2_Status_Model = new otgts2_Status_Model();
	require_once(OTGTS2_ROOT_PATH . 'helpers/view_helper.php');
	require_once(OTGTS2_ROOT_PATH . 'helpers/filter_helper.php');

	$message_list = array();
	
	// ***** Update Status if Form Submitted *****
	$trail_list = $otgts2_Trails_Model->get_trail_names();
	
	if (isset($_POST['_wpnonce'])) {
		check_admin_referer('update');
		foreach ($trail_list as $trail) {
			$status_id = otgts2_get_request_int('t_' . $trail['trail_id']);
			if (isset($status_id))
				$otgts2_Trails_Model->set_status($trail['trail_id'], $status_id);
		}
		update_option('otgts2_notes_sc', filter_var(wp_unslash(trim($_POST['otgts2_notes_sc']), FILTER_SANITIZE_STRING)));
		update_option('otgts2_notes_widget', filter_var(wp_unslash(trim($_POST['otgts2_notes_widget']), FILTER_SANITIZE_STRING)));
		$message_list[] = ['Statuses Updated', 1, 3];
	}
		
	// ***** Get Fresh Data *****
	$trail_list = $otgts2_Trails_Model->get_trail_names();
	$status_list = $otgts2_Status_Model->get_list();
	$notes_sc = get_option('otgts2_notes_sc');
	$notes_widget = get_option('otgts2_notes_widget');

	// ***** Call View *****
	include(OTGTS2_ROOT_PATH . 'views/update.php');
	include(OTGTS2_ROOT_PATH . 'views/about.php');
}

/** Registers the Admin Pages with WordPress
 */
function otgts2_admin () {
	add_menu_page('Trail Status', 'Trail Status', 'publish_posts', 'trail-status-2', 'otgts2_update', '', 3.4);
	add_submenu_page('trail-status-2', 'Trail Status Update', 'Update Status', 'publish_posts', 'trail-status-2', 'otgts2_update');
	add_submenu_page('trail-status-2', 'Trail Status List', 'Trail List', 'publish_pages', 'trail-status-2-list', 'otgts2_trail_list_page');
	add_submenu_page('trail-status-2', 'Trail Status Add', 'Add Trail', 'publish_pages', 'trail-status-2-add', 'otgts2_trail_edit_page');
	add_submenu_page('trail-status-2', 'Trail Status Statuses', 'Status List', 'publish_pages', 'trail-status-2-statuses', 'otgts2_status_list_page');
	add_submenu_page('trail-status-2', 'Trail Status Edit', '', 'publish_pages', 'trail-status-2-edit', 'otgts2_trail_edit_page');
}

/** Loads Scripts and Style Sheets Used in the Admin
 * wp_enqueue_media Long Form to Go Around Bugs 
 */
function otgts2_admin_load_styles_and_scripts () {
	require_once(OTGTS2_ROOT_PATH . 'helpers/filter_helper.php');
	$mode = get_user_option('media_library_mode', get_current_user_id()) ? get_user_option('media_library_mode', get_current_user_id()) : 'grid';
	$modes_list = ['grid', 'list'];
	$new_mode = otgts2_get_request_string('mode');
	if (!empty($new_mode) && in_array($new_mode, $modes_list)) {
		update_user_option(get_current_user_id(), 'media_library_mode', $new_mode);
		$mode = $new_mode;
	}
	if (!empty($_SERVER['PHP_SELF']) && 'upload.php' === basename($_SERVER['PHP_SELF']) && 'grid' !== $mode) {
		wp_dequeue_script('media');
	}
	wp_enqueue_media();

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-checkboxradio');
	wp_register_style('jquery-ui', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
	wp_enqueue_style('jquery-ui');
	wp_register_style('otgts2_datatables_css', plugins_url('datatables.min.css', __FILE__));
	wp_enqueue_style('otgts2_datatables_css');
	wp_enqueue_script('otgts2_datatables', plugins_url('datatables.min.js', __FILE__));
		
	wp_enqueue_style('wp-color-picker'); 
	wp_enqueue_script('otgts2_script', plugins_url('admin.min.js', __FILE__), ['wp-color-picker'], false, true);
}
