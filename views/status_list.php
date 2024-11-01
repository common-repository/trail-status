<?php
/** List View
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				view/status_list.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			04/26/2017
*/
?>
<script>
jQuery(document).ready(function () {
	var tableData = [
<?php if (!empty($status_list)) foreach ($status_list as $record) {
	$style = '';
	if (!empty($record['color'])) $style = ' style="color: ' . $record['color'] . '"';?> 
		[
			'<input type="checkbox" name="bulk_action_list[]" value="<?= $record['status_id'] ?>" class="otgts2_list_checkbox">',
			'<span id="otgts2_inline_no_edit_<?= $record['status_id'] ?>_name"><?= htmlspecialchars($record['name']) ?></span>'
				+ '<input id="otgts2_inline_edit_<?= $record['status_id'] ?>_name" value="<?= htmlspecialchars($record['name']) ?>" maxlength="50">',
			'<span id="otgts2_inline_no_edit_<?= $record['status_id'] ?>_sort_order"><?= $record['sort_order'] ?></span>'
				+	'<?= otgts2_number_select('otgts2_inline_edit_' . $record['status_id'] . '_sort_order', 1, 10, $record['sort_order']) ?>',
			'<span id="otgts2_inline_no_edit_<?= $record['status_id'] ?>_color"<?= $style ?>><?= $record['color'] ?></span>'
				+ '	<div id="otgts2_inline_edit_<?= $record['status_id'] ?>_cbox"><input id="otgts2_<?= $record['status_id'] ?>_color" value="<?= $record['color'] ?>" class="otgts2_colorpicker"></div>',
			'<a href="javascript:void(0)" onclick="showInlineEditableFields(<?= $record['status_id'] ?>)" id="otgts2_inline_no_edit_<?= $record['status_id'] ?>_edit" class="otgts2_intable_button">Edit</a>'
				+	'<a href="javascript:void(0)" onclick="saveInlineEditableFields(<?= $record['status_id'] ?>)" id="otgts2_inline_edit_<?= $record['status_id'] ?>_save" class="otgts2_intable_button">Save</a>'
				+	'<a href="javascript:void(0)" onclick="hideInlineEditableFields(<?= $record['status_id'] ?>)" id="otgts2_inline_edit_<?= $record['status_id'] ?>_cancel" class="otgts2_intable_button">Cancel</a>'
		],
<?php } ?>
	];
    jQuery("#table").DataTable( {
		data: tableData,
		autoWidth: false,
		pageLength: 25,
		stateSave: true,
		columnDefs: [ 
			{orderable: false, targets: [0, 4]}
		],
		order: [[1, "asc"]]
	});
});
</script>
<div class="wrap otgts2_adminmain">
	<h2>Trail Status | Status List &nbsp; <a href="#otgts2_add" class="add-new-h2">Add New</a></h2>
	<?= otgts2_display_messages($message_list) ?>

<?php // ***** Current Status List ***** ?>	
	<form method="post" action="admin.php?page=trail-status-2-statuses" style="max-width: 600px;">
		<?php wp_nonce_field('status_bulk'); ?>

		<table id="table" class="otgts2_table1">
			<thead><tr>
				<td><input id="cb-select-all-1" type="checkbox"></td>
				<td>Name</td>
				<td>Sort Order</td>
				<td>Color</td>
				<td></td>
			</tr></thead>
		</table>
		
		<div class="tablenav">
			<select name="action" id="bulk-action-selector-bottom">
				<option value="-1" selected="selected">Bulk Actions</option>
				<option value="delete">Delete</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
	</form>
		
<?php
// ***** Add New Status Form if Under 10 *****
if (10 > sizeof($status_list)) {
?>
	<form id="otgts2_add" method="post" action="admin.php?page=trail-status-2-statuses" class="otgts2_form1">
		<?php wp_nonce_field('status_add'); ?>
		<input type="hidden" name="action" value="add">

		<h3>Add a New Status</h3>
		<p>There is a limit of 10.</p>
		<p>
			<label for="otgts2_name">Name</label>
			<input id="otgts2_name" name="otgts2_name" type="text" maxlength="50">
		</p>
		<p>
			<label for="otgts2_sort_order">Sort Order</label>
			<?= otgts2_select_input_ten('otgts2_sort_order') ?>
		</p>
		<p>
			<label for="otgts2_color">Color (hex code)</label>
			<input id="otgts2_color" name="otgts2_color" type="text" maxlength="20">
		</p>
		<p>
			<input type="submit" value="Add It" class="button-primary">
		</p>
	</form>
<?php } else { ?>
	<h3>Max of 10 Statuses Added</h3>
<?php } ?>	
</div>