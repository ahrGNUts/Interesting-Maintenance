/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	var $btn_add_row = $('#add_row');
	let row_count = $('#multi_fields_table > tbody tr').length;
	let row_idx = row_count;
	let $tooltip_context;
	let tooltip_visible = false;
	
	const template = '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>';
	
	let options = {
		template: template
	}
	
	$('[data-toggle="tooltip"]').tooltip(options);
	
	changes_made = false;
	$(window).on('beforeunload', function() {
		if(changes_made)
			return true;
	});
	
	$('#sketch_type').on('change', function() {
		if($(this).val() != 'static'){
			$('.static_fields').hide();
			$('.multiple_fields').show();
			$('.multiple_fields tr td input').prop('disabled', false);
			$('#add_row').prop('disabled', false);
		} else {
			$('.static_fields').show();
			$('.multiple_fields').hide();
			$('.multiple_fields tr td input').prop('disabled', true);
			$('#add_row').prop('disabled', true);
		}
		changes_made = true;
	});
	
	$btn_add_row.on('click', function() {
		// count number of rows
		// if rows < 15 add new row
		// else display some sort of tooltip error that you can only add 15 rows or however many
		row_count = $('#multi_fields_table > tbody tr').length;
		
		if(row_count == 15){
			alert('table too long! [real text later]');
			return;
		}
		
		row_count++;
		row_idx++;
		
		$('#multi_fields_table').find('tbody')
			.append($('<tr>')
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[' + row_idx + '][id]')
						.attr('minlength', '5')
						.prop('required', true)
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[' + row_idx + '][width]')
						.prop('required', true)
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[' + row_idx + '][height]')
						.prop('required', true)
					)	
				)
				.append($('<td>')
					.append($('<span>')
						.attr('class', 'dashicons dashicons-no btn_delete')
						.attr('title', 'Delete row? <a class=\'row-link delete-row-confirm\'>Confirm</a> | <a class=\'row-link delete-row-cancel\'>Cancel</a>')
						.attr('data-index', row_idx)
						.attr('data-toggle', 'tooltip')
						.attr('data-trigger', 'manual')
						.attr('data-placement', 'right')
						.attr('data-html', 'true')
					)
				)
			)
		;
		changes_made = true;	
	});
	
	$('body').on('click', function(e) {
		if(!e.target.className.includes("dashicons-no btn_delete")){
			hide_tooltips();
		}
	});
	
	$('body').on('click', '.tooltip-inner .delete-row-confirm', function() {
		hide_tooltips();
		
		if($('#multi_fields_table tbody tr').length <= 1) {
			$.each($('#multi_fields_table tbody tr td input'), function() {
				$(this).val("");
			});
		} else {
			$tooltip_context.remove();
		}
		changes_made = true;
	});
	
	$('#multi_fields_table').on('click', 'span.btn_delete', function(event) {
		if(tooltip_visible){
			hide_tooltips();
		}
		$(this).tooltip('show');
		$tooltip_context = $(this).parent().parent();
		tooltip_visible = true;
	});
	
	$('input[type="text"], input[type="number"], textarea').on('input', function() {
		changes_made = true;
	});
	
	$('#submit').on('click', function() {
		changes_made = false;
	});
	
	function hide_tooltips() {
		$('[data-toggle="tooltip"]').tooltip('hide');
		tooltip_visible = false;
	}
});
