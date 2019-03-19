/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	var $btn_add_row = $('#add_row');
	let row_count = $('#multi_fields_table > tbody tr').length;
	let row_idx = row_count;
	
	
	$('#sketch_type').on('change', function() {
		if($(this).val() != 'static'){
			$('.static_fields').hide();
			$('#multiple_fields').show();
		} else {
			$('.static_fields').show();
			$('#multiple_fields').hide();
		}
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
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[' + row_idx + '][width]')
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[' + row_idx + '][height]')
					)	
				)
				.append($('<td>')
					.append($('<span>')
						.attr('class', 'dashicons dashicons-no btn_delete')
					)
				)
			)
		;		
	});
	
	$('#multi_fields_table').on('click', 'span.btn_delete', function(event) {
		// this works to remove the row
		// TODO: implement check to see if it's the only row.
		// if only row, delete content in the inputs instead of removing the row
		// else remove row
		$(this).parent().parent().remove();
	});
});
