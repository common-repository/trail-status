<?php
/** Trail Record Edit View
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				view/trail_edit.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			11/12/15
*/
?><br>
<div class="wrap otgts2_adminmain">
	<h2>Trail Status | <?php if (!empty($record['trail_id'])) echo "Edit"; else echo "Add"; ?> Trail</h2>
	<?= otgts2_display_messages($message_list) ?>

	<form name="form1" method="post" class="otgts2_form1" style="display: inline-block; max-width: 550px;">
<?php if (empty($record['trail_id'])) wp_nonce_field('trail_add'); else wp_nonce_field('trail_edit_' . $record['trail_id']); ?>
<?php if (!empty($record['trail_id'])) { ?>
		<input type="hidden" name="trail_id" value="<?= $record['trail_id'] ?>">
<?php } ?>		
		<input type="hidden" name="status_id" value="<?= $record['status_id'] ?>">
		<input type="hidden" id="image_id" name="image_id" value="<?= $record['image_id'] ?>">
		
		<p>
			<label>*Name:</label>
			<input type="text" name="name" maxlength="50" value="<?= htmlspecialchars($record['name']) ?>" required="required">
		</p>
		<p>
			<label>Link:</label>
			<input type="text" name="link" maxlength="200" value="<?= htmlspecialchars($record['link']) ?>">
		</p>
		<p>
			<label>Image:</label>
			<input type="button" name="image_button" id="upload-btn" class="button-secondary" value="Set Image">
		</p>
		<p>
			<label>Sort Order:</label>
			<input type="number" name="sort_order" maxlength="10" value="<?= $record['sort_order']?>">
		</p>
		<p>
			<label for="show_widget">Show on Widgets:</label>
			<input type="checkbox" id="show_widget" name="show_widget" value="1" <?php if ($record['show_widget']) echo $checked_text; ?>>
		</p>
		<p>
			<label for="show_shortcode">Show on Shortcodes:</label>
			<input type="checkbox" name="show_shortcode" value="1" <?php if ($record['show_shortcode']) echo $checked_text; ?>>
		</p>

		<p style="text-align: center;">
			<input type="submit" class="button-primary" value="Save">
			<a href="admin.php?page=trail-status-2-list" class='button-primary' style="margin-left: 17px;">Back to List</a>
		</p>
	</form>	

<?php
if (!empty($record['image_id'])) {
	$image_thumb_url = wp_get_attachment_thumb_url($record['image_id']);
	$image_alt = htmlspecialchars(get_post_meta($record['image_id'], '_wp_attachment_image_alt', true));
?>
	<img id="otgts2_trail_image" src="<?= $image_thumb_url ?>" alt="<?= $image_alt ?>" style="display: inline-block; vertical-align: top;  margin: 31px; width: 150px; height: 150px; box-shadow: 4px 4px 4px #555;">
<?php } ?>	

</div>