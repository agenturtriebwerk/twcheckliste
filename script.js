/**
 * script.js for Plugin twcheckliste
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Matthias Wild <m.wild@agentur-triebwerk.de
 *
 */

jQuery.fn.toggleCheckbox = function() {
	if (this.prop('checked') == true){
		this.prop('checked', false);
	} else {
		this.prop('checked', true);
	}
	// this.prop('checked', !this.prop('checked'));
};

jQuery(function() {
	generateForm();
	jQuery(".checkliste ol").addClass("hideit");
});

function generateForm() {
	
	var x = 0;
	var y = 0;
	var h3level = 0;
	var h4level = 0;
	var z = 0;
	var lplevel = 0;
	var scripte = '';
	
	jQuery(".checkliste h2").each(function(indexh2, value) {
		var tmpElement = jQuery(document.createElement("input")).prop({
			id : 'GRUPPE' + indexh2,
			class : '',
			name : 'checklist_data[' + indexh2 + '][HL]',
			value : jQuery(this).html(),
			type : 'checkbox',
			checked : true
		});
		
		jQuery(this).prepend(tmpElement);
		
		jQuery("#GRUPPE" + indexh2).click(function() {
			if (jQuery(this).prop("checked") == true) {
				jQuery("." + jQuery(this).prop("id")).prop("checked", "true");
			} else {
				jQuery("." + jQuery(this).prop("id")).removeAttr("checked");

			}

		});

		jQuery(this).nextUntil("h2").each(function() {
			if (jQuery(this).hasClass("level2")) {
				lplevel = 0;
				jQuery(this).find("li").each(function() {
					lplevel++;
					var tmpElement = jQuery(document.createElement("input")).prop({
						id : '',
						class : 'GRUPPE' + indexh2,
						name : 'checklist_data[' + indexh2 + '][LP][' + lplevel + ']',
						value : jQuery(this).find("div").html(),
						type : 'checkbox',
						checked : true
					});

					jQuery(this).prepend(tmpElement);

				});
			} else if (jQuery(this).get(0).tagName == "H3") {
				h3level++;
				var tmpElement = jQuery(document.createElement("input")).prop({
					id : 'LEVEL2_' + h3level,
					class : 'GRUPPE' + indexh2,
					name : 'checklist_data[' + indexh2 + '][H3][' + h3level + '][HL]',
					value : jQuery(this).html(),
					type : 'checkbox',
					checked : true
				});
				
				jQuery(this).prepend(tmpElement);

			} else if (jQuery(this).hasClass("level3")) {
				lplevel = 0;
				jQuery(this).find("li").each(function() {
					lplevel++;
					var tmpElement = jQuery(document.createElement("input")).prop({
						id : '',
						class : 'GRUPPE' + indexh2 + ' LEVEL2_' + h3level,
						name : 'checklist_data[' + indexh2 + '][H3][' + h3level + '][LP][' + lplevel + ']',
						value : jQuery(this).find("div").html(),
						type : 'checkbox',
						checked : true
					});

					jQuery(this).prepend(tmpElement);

				});

			} else if (jQuery(this).get(0).tagName == "H4") {
				h4level++;
				var tmpElement = jQuery(document.createElement("input")).prop({
					id : 'LEVEL3_' + h4level,
					class : 'GRUPPE' + indexh2 + ' LEVEL2_' + h3level,
					name : 'checklist_data[' + indexh2 + '][H3][' + h3level + '][H4][' + h4level + '][HL]',
					value : jQuery(this).html(),
					type : 'checkbox',
					checked : true
				});
				jQuery(this).prepend(tmpElement);
			} else if (jQuery(this).hasClass("level4")) {
				lplevel = 0;
				jQuery(this).find("li").each(function() {
					lplevel++;
					var tmpElement = jQuery(document.createElement("input")).prop({
						id : '',
						class : 'GRUPPE' + indexh2 + ' LEVEL2_' + h3level + ' LEVEL3_' + h4level,
						name : 'checklist_data[' + indexh2 + '][H3][' + h3level + '][H4][' + h4level + '][LP][' + lplevel + ']',
						value : jQuery(this).find("div").html(),
						type : 'checkbox',
						checked : true
					});

					jQuery(this).prepend(tmpElement);
					
				});

			}

		});

	});
	
	for (var i = 0; i <= h3level; i++) {
		jQuery("#LEVEL2_" + i).click(function() {
			if (jQuery(this).prop("checked") == true) {
				jQuery("." + jQuery(this).prop("id")).prop("checked", "true");
			} else {
				jQuery("." + jQuery(this).prop("id")).removeAttr("checked");
			}
		});
	}
	
	for (var i = 0; i <= h4level; i++) {
		jQuery("#LEVEL3_" + i).click(function() {
			if (jQuery(this).prop("checked") == true) {
				jQuery("." + jQuery(this).prop("id")).prop("checked", "true");
			} else {
				jQuery("." + jQuery(this).prop("id")).removeAttr("checked");
			}

		});
	}

}
/*
function generateForm2() {

	var x = 0;
	var y = 0;

	jQuery.each(jQuery(".checkliste h1"), function(index, value) {

		var tmpElement = jQuery(document.createElement("input")).prop({
			id : 'input-' + x,
			class : 'single-input',
			name : 'checklist_data[]',
			value : 'h1_' + jQuery.trim(jQuery(this).html()),
			type : 'hidden'
		});

		jQuery(this).prepend(tmpElement);
	});

	jQuery.each(jQuery(".checkliste h2"), function(index, value) {

		x++;
		var item = jQuery(this).prop("class");
		var groupId = item.replace(/\D/g, '');

		var tmpElement = jQuery(document.createElement("input")).prop({
			id : 'group-' + groupId,
			class : 'group-input',
			name : 'checklist_data[]',
			value : 'h2_' + jQuery(this).find("a").html(),
			type : 'checkbox',
			checked : false
		});

		jQuery(this).prepend(tmpElement);

		jQuery('#group-' + groupId).click(function() {
			if (!jQuery(this).prop('checked')) {
				jQuery(this).parent().next().find(':checkbox').prop('checked', false);
			} else {
				jQuery(this).parent().next().find(':checkbox').prop('checked', true);
			}
		});

		// BEARBEITUNG VON P ELEMENTE
		jQuery.each(jQuery(this).next().find("p"), function(index, value) {

			y++;
			var tmpElement = jQuery(document.createElement("input")).prop({
				id : 'input-' + y + groupId,
				class : 'single-input single-input-p' + y + groupId,
				name : 'checklist_data[]',
				value : 'p_' + jQuery.trim(getEingabefeld(jQuery(this).html())),
				type : 'checkbox',
				checked : false
			});

			jQuery(this).html(getEingabefeld(jQuery(this).html()));
			jQuery(this).prepend(tmpElement);

			jQuery('.single-input-p' + y + groupId).change(function() {
				if (!jQuery(this).prop('checked')) {
					jQuery(this).parent().next().find(':checkbox').prop('checked', false);
					jQuery(this).parent().next().find(':checkbox').removeAttr('checked');
				} else {
					jQuery(this).parent().next().find(':checkbox').prop('checked', true);
				}
			});

			// BEARBEITUNG VON LISTENELEMENTE
			jQuery.each(jQuery(this).next().find("li"), function(index, value) {

				var tmpElement = jQuery(document.createElement("input")).prop({
					id : 'input-' + y + groupId,
					class : 'single-input single-input' + y,
					name : 'checklist_data[]',
					value : 'li_' + jQuery.trim(jQuery(this).find("div").html()),
					type : 'checkbox',
					checked : false
				});
				jQuery(this).html(getEingabefeld(jQuery(this).html()));
				jQuery(this).prepend(tmpElement);
			});
		});

	});
}
*/
function getEingabefeld(tmpHtml) {
	return tmpHtml.replace('EINGABEFELD', '...................................');
}
