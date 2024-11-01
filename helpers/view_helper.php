<?php
/** Helper Functions and Variables for Views
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				helpers/view_helper.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
* @created			7/21/2016
*/

$checked_text = ' checked="checked"';
$selected_text = ' selected="selected"';

/** Builds a On/Off Select
 * @param string $name
 * @param int $default
 */
function otgts2_on_off_select ($name, $default) {
	$selected_text = ' selected="selected"';
	echo "<select name='$name'>";
	echo '<option value="1"';
	if (1 == $default) echo $selected_text;
	echo ">On</option>";
	echo '<option value="0"';
	if (0 == $default) echo $selected_text;
	echo ">Off</option>";
	echo "</select>";
}

/** Builds a On/Off Select
 * @param string $name
 * @param int $default
 */
function otgts2_yes_no_select ($name, $default) {
	$selected_text = ' selected="selected"';
	echo "<select name='$name'>";
	echo '<option value="1"';
	if (1 == $default) echo $selected_text;
	echo ">Yes</option>";
	echo '<option value="0"';
	if (0 == $default) echo $selected_text;
	echo '>No</option>';
	echo '</select>';
}

/** Returns a True/False as a Yes/nNo in Color
 * @param boolean $value
 * @return string
 */
function otgts2_display_yes_no ($value) {
	if ($value)
		return '<span style="color: green">Yes</span>';
	else
		return '<span style="color: red">No</span>';
}

/** Inserts a 1-10 Select
 * @param type $name
 * @return type
 */
function otgts2_select_input_ten ($name) {
	return '<select id="' . $name . '" name="' . $name . '"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>';
}

/** Builds a Number Select
 * @param string $name
 * @param int $min
 * @param int $max
 * @param int $selected
 * @param string $class
 * @return string
 */
function otgts2_number_select ($name, $min, $max, $selected, $class = '') {
	$out = '<select id="' . $name . '" name="' . $name . '" class="' . $class . '">';
	for ($i = $min; $i < $max + 1; $i++) {
		if ($selected == $i)
			$out .= "<option value=\"$i\" selected=\"selected\">$i</option>";
		else
			$out .= "<option value=\"$i\">$i</option>";
	}
	$out .= '</select>';
	return $out;
}

/** Takes Messages and Displays Them
 * @param array $message_list
 * @return boolean (false if empty)
 */
function otgts2_display_messages ($message_list) {
	// ***** End if Empty *****
	if (empty($message_list)) return false;

	// ***** Order by Third Field *****
	usort($message_list, function($a, $b) {
		 return $a[2] - $b[2];
	});
	foreach ($message_list as $message) {
		// ***** Set Class Second Field *****
		switch ($message[1]) {
			case 1:
				$class = 'otgts2_success';
				break;
			case 2:
				$class = 'otgts2_warning';
				break;
			case 3:
				$class = 'otgts2_error';
				break;
			default:
				$class = 'otgts2_message';
		}
		// ***** Print It *****
		echo "<p class='$class'>" . $message[0] . '</p>';
	}
}
