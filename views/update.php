<?php
/** List View
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				view/update.php
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2024, On the Grid Web Design LLC
 * @created			11/12/15
*/
?>
<script>
	jQuery(function() {
		jQuery(".otgts2_trail_status").checkboxradio({
			icon: false
		 });
	});
</script>
<div class="wrap otgts2_adminmain">
	<h2>Trail Status | Update Status</h2>
	<?= otgts2_display_messages($message_list) ?>

	<form method="post">
		<input type="submit" class="button-primary" value="<?= 'Save Changes' ?>">
		<?php wp_nonce_field('update'); ?>
<?php if (!empty($trail_list)) foreach ($trail_list as $trail) { ?>
		<p>
			<span class="otgts2_trail_name"><?= htmlspecialchars($trail['name']) ?></span>
	<?php foreach ($status_list as $status) { ?>
			<label for="otgts2_<?= $trail['trail_id'] . '_' . $status['status_id'] ?>" class="otgts2_trail_status_l"><?= htmlspecialchars($status['name']) ?></label>
			<input type="radio" id="otgts2_<?= $trail['trail_id'] . '_' . $status['status_id'] ?>" name="t_<?= $trail['trail_id'] ?>" value="<?= $status['status_id'] ?>" style="margin-left: 7px;"<?php if ($status['status_id'] == $trail['status_id']) echo $checked_text ?> class="otgts2_trail_status">
	<?php } ?>
		</p>
<?php } ?>
		<p class="otgts2_form1">
			<label>Notes for Shortcode<br>(HTML Allowed)</label>
			<textarea name="otgts2_notes_sc"><?= $notes_sc ?></textarea>
		</p>
		<p class="otgts2_form1">
			<label>Notes for Widget<br>(HTML Allowed, 150 Characters or less)</label>
			<textarea name="otgts2_notes_widget" maxlength="350" style="height: 75px;"><?= htmlspecialchars($notes_widget) ?></textarea>
		</p>

		<input type="submit" class="button-primary" value="Save Changes">
	</form>

</div>