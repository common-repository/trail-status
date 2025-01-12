<?php
/** Database Functions
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				models/trails_model.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			11/12/15
 */
class otgts2_Trails_Model {

	/** Returns the list of trails for the shortcode ordered by the order # then name.
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @global string $table_statuses
	 * @return array
	 */
	function get_list_for_shortcode () {
		global $wpdb, $table_trails, $table_statuses;
		$sql = "SELECT trail_id, $table_trails.name, link, image_id, $table_statuses.name AS status, $table_statuses.color AS color FROM $table_trails"
				. " JOIN $table_statuses ON $table_statuses.status_id = $table_trails.status_id"
				. " WHERE show_shortcode = 1 ORDER BY $table_trails.sort_order ASC, $table_trails.name ASC LIMIT 50;";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}

	/** Returns the list of trails for the shortcode ordered by the order # then name.
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @global string $table_statuses
	 * @return array
	 */
	function get_list_for_widget () {
		global $wpdb, $table_trails, $table_statuses;
		$sql = "SELECT trail_id, $table_trails.name, link, $table_statuses.name AS status, $table_statuses.color AS color FROM $table_trails"
				. " JOIN $table_statuses ON $table_statuses.status_id = $table_trails.status_id"
				. " WHERE show_widget = 1 ORDER BY $table_trails.sort_order ASC, $table_trails.name ASC LIMIT 50;";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}

	/** Gets all the trail names and ids for non-hidden trails
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @return array
	 */
	function get_trail_names () {
		global $wpdb, $table_trails;
		$sql = "SELECT trail_id, name, status_id FROM $table_trails WHERE hidden = 0";
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}

	/** Gets all the trail names and ids for non-hidden trails
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @return array
	 */
	function get_names_list () {
		global $wpdb, $table_trails;
		$sql = "SELECT trail_id, name FROM $table_trails";
		$result = stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
		if (empty($result))
			return false;
		foreach ($result as $row) {
			$return[$row['trail_id']] = $row['name'];
		}
		return $return;
	}	
	
	/** Gets all a set of records for the list table
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $hidden
	 * @return type
	 */
	function get_list ($hidden = null) {
		global $wpdb, $table_trails;

		// ***** Build Where Statement *****
		$where = '';
		if (1 == $hidden)
			$where = ' WHERE hidden = 1';
		if (2 == $hidden)
			$where = ' WHERE hidden = 0';

		$sql = "SELECT * FROM $table_trails " . $where;
		return stripslashes_deep($wpdb->get_results($sql, ARRAY_A));
	}

	/** Returns All Fields in a Listing Record
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $trail_id
	 * @return array
	 */
	function get ($trail_id) {
		global $wpdb, $table_trails;
		$sql = "SELECT * FROM $table_trails WHERE trail_id = " . (int)$trail_id;
		return stripslashes_deep($wpdb->get_row($sql, ARRAY_A));
	}

	/** Creates a New Listing
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param string $name
	 * @param string $link
	 * @param int $image_id
	 * @param float $sort_order
	 * @param int $show_widget
	 * @param int $show_shortcode
	 * @param int $status_id
	 * @return boolean
	 */
	function add ($name, $link, $image_id, $sort_order, $show_widget = 1, $show_shortcode = 1, $status_id = 0) {
		global $wpdb, $table_trails;
		$result = $wpdb->insert(
				$table_trails,
				[
					'created' => current_time('mysql'),
					'name' => $name,
					'link' => $link,
					'image_id' => $image_id,
					'sort_order' => $sort_order,
					'status_id' => $status_id,
					'show_widget' => $show_widget,
					'show_shortcode' => $show_shortcode
				],
				['%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d']
		);
		if (false === $result)
			error_log('otgts2_Trails_Model->add: ' . $wpdb->last_error . ' | ' . $wpdb->last_query);
		return $result;
	}

	/** Updates a Listing
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $trail_id
	 * @param string $name
	 * @param string $link
	 * @param int $image_id
	 * @param float $sort_order
	 * @param int $show_widget
	 * @param int $show_shortcode
	 * @param int $status_id
	 * @return boolean
	 */
	function update ($trail_id, $name, $link, $image_id, $sort_order, $show_widget, $show_shortcode, $status_id) {
		global $wpdb, $table_trails;
		$result = $wpdb->update(
				$table_trails, 
				[
					'name' => $name,
					'link' => $link,
					'image_id' => $image_id,
					'sort_order' => $sort_order,
					'status_id' => $status_id,
					'show_widget' => $show_widget,
					'show_shortcode' => $show_shortcode
				],
				['trail_id' => $trail_id],
				['%s', '%s', '%d', '%d', '%d', '%d', '%d'],	
				['%d']
		);
		if (false === $result)
			error_log('otgts2_Trails_Model->add: ' . $wpdb->last_error . ' | ' . $wpdb->last_query);		
		return $result;
	}

	/** Delete a Listing
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $id
	 */
	function delete ($id) {
		global $wpdb, $table_trails;
		return $wpdb->delete($table_trails, ['trail_id' => $id], ['%d']);
	}

	/** Updates the Status of a Trail
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $trail_id
	 * @param int $status_id
	 * @return boolean
	 */
	function set_status ($trail_id, $status_id) {
		// ***** Query *****
		global $wpdb, $table_trails;
		$result = $wpdb->update(
				$table_trails,
				['status_id' => $status_id],
				['trail_id' => $trail_id],
				['%d'],
				['%d']
		);
		if (false === $result)
			error_log('otgts2_Trails_Model->set_status: ' . $wpdb->last_error . ' | ' . $wpdb->last_query);		
		return $result;
	}
	
	/** Set a listing as shown on the widget
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $trail_id
	 * @param int $show_widget
	 * @return boolean
	 */
	function set_show_widget ($trail_id, $show_widget = 1) {
		// ***** Query *****
		global $wpdb, $table_trails;
		$result = $wpdb->update(
				$table_trails,
				['show_widget' => $show_widget],
				['trail_id' => $trail_id],
				['%d'],
				['%d']
		);
		if (false === $result)
			error_log('otgts2_Trails_Model->set_status: ' . $wpdb->last_error . ' | ' . $wpdb->last_query);		
		return $result;
	}

	/** Set a listing as shown on the shortcode
	 * @global wpdb $wpdb
	 * @global string $table_trails
	 * @param int $trail_id
	 * @param int $show_shortcode
	 * @return boolean
	 */
	function set_show_shortcode ($trail_id, $show_shortcode = 1) {
		// ***** Query *****
		global $wpdb, $table_trails;
		$result = $wpdb->update(
				$table_trails,
				['show_shortcode' => $show_shortcode],
				['trail_id' => $trail_id],
				['%d'],
				['%d']
		);
		if (false === $result)
			error_log('otgts2_Trails_Model->set_status: ' . $wpdb->last_error . ' | ' . $wpdb->last_query);		
		return $result;
	}

}
