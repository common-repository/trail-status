/** Javascripts
 * @Package			com.onthegridwebdesign.trailstatus2
 * @File				admin.js
 * @Author			Chris Hood (http://onthegridwebdesign.com)
 * @Link				http://onthegridwebdesign.com/software/trail-status
 * @copyright		(c) 2015-2022, On the Grid Web Design LLC
 * @created			11/12/15
 */

var image_selector;
jQuery(document).ready(function ($) {
	$("#upload-btn").click(function (e) {
		e.preventDefault();

		image_selector = wp.media({title: "Trail Image", multiple: false}).open()
			.on("select", function (e2) {
				var uploaded_image = image_selector.state().get("selection").first();
				$("#image_id").val(uploaded_image.toJSON().id);
				$("#otgts2_trail_image").attr("src", uploaded_image.attributes.sizes.thumbnail.url);
				$(".media-modal-close").click();
			});
	});

	// *** Keep the Bulk Action Selects Synced ***
	$("#bulk-action-selector-top").change(function (e) {
		$("#bulk-action-selector-bottom").val($("#bulk-action-selector-top").val());
	});
	$("#bulk-action-selector-bottom").change(function (e) {
		$("#bulk-action-selector-top").val($("#bulk-action-selector-bottom").val());
	});

	// *** Check All Box ***
	$("#cb-select-all-1").change(function (e) {
		if ($("#cb-select-all-1").prop("checked")) {
			$(".otgts2_list_checkbox").prop("checked", true);
		} else {
			$(".otgts2_list_checkbox").prop("checked", false);
		}
	});

	$(".otgts2_colorpicker").wpColorPicker();
});

/** Shows elements for making inline edits and hides non-editable fields they replace
 * @param {int} group
 */
function showInlineEditableFields (group) {
	jQuery("[id*='otgts2_inline_no_edit_" + group + "_']").hide();
	jQuery("[id*='otgts2_inline_edit_" + group + "_']").show(350);
}

/** Hides elements for making inline edits and shows non-editable fields they replace
 * @param {int} group
 */
function hideInlineEditableFields (group) {
	jQuery("[id*='otgts2_inline_no_edit_" + group + "_']").show(200);
	jQuery("[id*='otgts2_inline_edit_" + group + "_']").hide();
}

/** Saves the changes to the status and updates the non-editable fields
 * @param {int} group
 */
function saveInlineEditableFields (group) {
	hideInlineEditableFields(group);
	jQuery(document).ready(function ($) {
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				action: "otgts2_update_status",
				status_id: group,
				name: $("#otgts2_inline_edit_" + group + "_name").val(),
				sort_order: $("#otgts2_inline_edit_" + group + "_sort_order").val(),
				color: $("#otgts2_" + group + "_color").val(),
				wp_nonce: $("#_wpnonce").val()
			},
			success: function (data) {
				if (data == "true") {
					$("#otgts2_inline_no_edit_" + group + "_name").html($("#otgts2_inline_edit_" + group + "_name").val());
					$("#otgts2_inline_no_edit_" + group + "_sort_order").html($("#otgts2_inline_edit_" + group + "_sort_order").val());
					$("#otgts2_inline_no_edit_" + group + "_color").html($("#otgts2_" + group + "_color").val());
					$("#otgts2_inline_no_edit_" + group + "_color").css("color", $("#otgts2_" + group + "_color").val());
				} else {
					alert('Save failed');
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert("Save failed");
//				console.log("XMLHttpRequest: " + XMLHttpRequest);
//				console.log("textStatus: " + textStatus);
//				console.log("errorThrown: " + errorThrown);
			}
		});
	});
}