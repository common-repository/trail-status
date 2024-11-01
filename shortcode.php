<?php
/** Shortcode Controller
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				shortcode.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 */

/** Creates the table list HTML
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgts2_sc_table ($attributes, $content = null) {
	// ***** Load Models, Helpers and Libraries *****
	require_once('models/trails_model.php');
	if (!isset($otgts2_Trails_Model))
		$otgts2_Trails_Model = new otgts2_Trails_Model();

	// ***** Get Attributes & Data *****
	$attr_defaults = array('show_images' => 'yes', 'color_text' => 'yes', 'box_shadow' => 'yes', 'small_images' => 'no');
	$atts = shortcode_atts($attr_defaults, $attributes);
	if ('yes' == strtolower($atts['show_images'])) $show_images = true; else $show_images = false;
	if ('yes' == strtolower($atts['color_text'])) $color_text = true; else $color_text = false;
	if ('yes' == strtolower($atts['box_shadow'])) $box_shadow = true; else $box_shadow = false;
	if ('yes' == strtolower($atts['small_images'])) $small_images = true; else $small_images = false;
	
	$trail_list = $otgts2_Trails_Model->get_list_for_shortcode();

	// ***** View *****
	$output = '<table class="otgts2_sc">';
	if (!empty($trail_list)) {
		if ($small_images) {
			$img_class = 'otgts2_sc_sm_trail_img';
		} else {
			$img_class = 'otgts2_sc_trail_img';
		}
		foreach ($trail_list as $trail) {
			if ($color_text && !empty($trail['color'])) {
				$color_str = ' style="color: ' . htmlspecialchars($trail['color']) . '"';
			} else {
				$color_str = '';
			}
			$output .= '<tr class="otgts2_sc_trail"' . $color_str . '>';

			// *** Image ***
			if ($show_images) {
				$output .= '<td class="' . $img_class . '">';
				if (!empty($trail['image_id'])) {
					$image_thumb_url = wp_get_attachment_thumb_url($trail['image_id']);
					$image_alt = htmlspecialchars(get_post_meta($trail['image_id'], '_wp_attachment_image_alt', true));
					$image_page_url = get_attachment_link($trail['image_id']);
					$output .= '<a href="' . $image_page_url . '" target="_blank"><img src="' . $image_thumb_url . '" alt="' . $image_alt . '"';
					if ($box_shadow) $output .= ' class="otgts2_box_shadow"';
					$output .= '></a>';
				} else {
					$output .= '<img src="' . plugins_url('images/trail-placeholder.png', __FILE__) . '" alt="">';
				}
				$output .= '</td>';
			}
			
			// *** Name & Link ***
			$output .= '<td class="otgts2_sc_title">';
			if (!empty($trail['link'])) {
				$output .= '<a href="' . htmlspecialchars($trail['link']) . '" target="_blank"' . $color_str . '>';
			}
			$output .= htmlspecialchars($trail['name']) . ':';
			if (!empty($trail['link'])) {
				$output .= '</a>';
			}
			$output .= '</td>';

			// *** Status ***
			$output .= '<td class="otgts2_sc_status">' . htmlspecialchars($trail['status']) . '</td>';
			
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	
	$otgts2_notes_sc = get_option('otgts2_notes_sc');
	if (!empty($otgts2_notes_sc))
		$output .= '<p>' . htmlspecialchars(get_option('otgts2_notes_sc')) . '</p>';

	return $output;
}

/** Creates the block HTML
 * @param array $attributes
 * @param string $content
 * @return string
 */
function otgts2_sc_blocks ($attributes, $content = null) {
	// ***** Load Models, Helpers and Libraries *****
	if (!isset($otgts2_Trails_Model)) {
		require_once('models/trails_model.php');
		$otgts2_Trails_Model = new otgts2_Trails_Model();
	}

	// ***** Get Attributes & Data *****
	$attr_defaults = array('show_images' => 'yes', 'color_text' => 'yes', 'box_shadow' => 'yes', 'small_images' => 'no');
	$atts = shortcode_atts($attr_defaults, $attributes);
	if ('yes' == strtolower($atts['show_images'])) $show_images = true; else $show_images = false;
	if ('yes' == strtolower($atts['color_text'])) $color_text = true; else $color_text = false;
	if ('yes' == strtolower($atts['box_shadow'])) $box_shadow = true; else $box_shadow = false;
	if ('yes' == strtolower($atts['small_images'])) $small_images = true; else $small_images = false;
	
	$trail_list = $otgts2_Trails_Model->get_list_for_shortcode();

	// ***** View *****
	$output = '<div class="otgts2_sc">';

	if (!empty($trail_list)) {
		if ($small_images) {
			$img_class = 'otgts2_sc_sm_trail_img';
		} else {
			$img_class = 'otgts2_sc_trail_img';
		}

		foreach ($trail_list as $trail) {
			if ($color_text && !empty($trail['color'])) {
				$color_str = ' style="color: ' . $trail['color'] . '"';
			} else {
				$color_str = '';
			}
			$output .= '<div class="otgts2_scb"' . $color_str . '>';
			// *** Image ***
			if ($show_images) {
				$output .= '<div class="' . $img_class . '">';
				if (!empty($trail['image_id'])) {
					$image_thumb_url = wp_get_attachment_thumb_url($trail['image_id']);
					$image_alt = htmlspecialchars(get_post_meta($trail['image_id'], '_wp_attachment_image_alt', true));
					$image_page_url = get_attachment_link($trail['image_id']);
					$output .= '<a href="' . $image_page_url . '" target="_blank"><img src="' . $image_thumb_url . '" alt="' . $image_alt . '"';
					if ($box_shadow) $output .= ' class="otgts2_box_shadow"';
					$output .= '></a>';
				} else {
					$output .= '<img src="' . plugins_url('images/trail-placeholder.png', __FILE__) . '">';
				}
				$output .= '</div>';
			}

			// *** Name & Link ***
			if (!empty($trail['link'])) {
				$output .= '<a href="' . esc_url($trail['link']) . '" target="_blank"' . $color_str . '>';
			}
			$output .= htmlspecialchars($trail['name']);
			if (!empty($trail['link'])) {
				$output .= '</a>';
			}

			// *** Status ***
			$output .= '<br>' . $trail['status'];
			$output .= '</div>';
		}
	}
	$output .= '</div>';
	
	$otgts2_notes_sc = get_option('otgts2_notes_sc');
	if (!empty($otgts2_notes_sc))
		$output .= '<p>' . get_option('otgts2_notes_sc') . '</p>';

	return $output;
}