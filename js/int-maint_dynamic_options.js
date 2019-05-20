/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	var $btn_add_row = $('#add_row');
	let row_count = $('#multi_fields_table > tbody tr').length;
	let row_idx = row_count;
	
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
						.attr('title', 'Delete')
					)
				)
			)
		;
		changes_made = true;	
	});
	
	$('#multi_fields_table').on('click', 'span.btn_delete', function(event) {
		// TODO: something more aesthetically pleasing like a tooltip next to the delete button with anchors to confirm or stop the deletion
		
		if($('#multi_fields_table tbody tr').length <= 1) {
			if(confirm("Are you sure you want to delete this row's contents?")){
				$.each($('#multi_fields_table tbody tr td input'), function() {
					$(this).val("");
					changes_made = true;
				});
			}	
		} else {
			if(confirm("Are you sure you want to delete this row?")){
				$(this).parent().parent().remove();
				changes_made = true;
			}
		}
	});
	
	$('input[type="text"], input[type="number"], textarea').on('input', function() {
		changes_made = true;
	});
	
	$('#submit').on('click', function() {
		changes_made = false;
	});
});
