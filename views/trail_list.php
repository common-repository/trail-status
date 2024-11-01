<?php
/** Trail Management List View
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				view/trail_list.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			11/12/15
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($table_data)) foreach ($table_data as $record) { ?> 
		[
			'<input type="checkbox" name="bulk_action_list[]" value="<?= $record['trail_id'] ?>" class="otgts2_list_checkbox">',
			'<a href="admin.php?page=trail-status-2-edit&trail=<?= $record['trail_id'] ?>" class="row-title"><?= htmlspecialchars($record['name']) ?></a>',
			'<?php if (!empty($record['link'])) { ?><a href="<?= esc_url($record['link']) ?>" target="_blank">Visit Website</a><?php } ?>',
			'<?php if (!empty($record['image_id'])) { ?><img src="<?= wp_get_attachment_thumb_url($record['image_id']) ?>" style="width: 33px; height: 33px;"> <?php } ?>',
			'<?= $record['sort_order'] ?>',
			'<?= otgts2_display_yes_no($record['show_widget']) ?>',
			'<?= otgts2_display_yes_no($record['show_shortcode']) ?>'
		],
<?php } ?>
	];
    jQuery("#table").DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [ 
			{orderable: false, targets: [0, 3]}
		],
		order: [[1, "asc"]]
	});
});
</script>
<div class="wrap">
	<h2>Trail Status | List &nbsp; <a href="admin.php?page=trail-status-2-add" class="add-new-h2">Add New</a></h2>
	<?= otgts2_display_messages($message_list) ?>

	<form method="post" action="admin.php?page=trail-status-2-list">
		<?php wp_nonce_field('trails_bulk'); ?>
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-top">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="hide_on_widget">Hide on Widget</option>
				<option value="show_on_widget">Show on Widget</option>
				<option value="hide_on_shortcode">Hide on Shortcode</option>
				<option value="show_on_shortcode">Show on Shortcode</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
		
		<table id="table" class="otgts2_table1">
			<thead><tr>
				<td><input id="cb-select-all-1" type="checkbox"></td>
				<td>Name</td>
				<td>Link</td>
				<td>Image</td>
				<td>Sort Order</td>
				<td>Show on Widgets</td>
				<td>Show on Shortcodes</td>
			</tr></thead>
		</table>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-bottom">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="hide_on_widget">Hide on Widget</option>
				<option value="show_on_widget">Show on Widget</option>
				<option value="hide_on_shortcode">Hide on Shortcode</option>
				<option value="show_on_shortcode">Show on Shortcode</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
	</form>
</div>