/*
	@since 0.4
	@author Patrick Strube
*/
jQuery( document ).ready( function( $ ) {
	$btn_add_row = $('#add_row');
	
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
		let row_count = $('#multi_fields_table > tbody tr').length;
		
		if(row_count == 15){
			alert('table too long! [real text later]');
			return;
		}
		
		row_count++;
		console.log(row_count);
		
		$('#multi_fields_table').find('tbody')
			.append($('<tr>')
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[][id]')
						.attr('minlength', '5')
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[][width]')
					)
				)
				.append($('<td>')
					.append($('<input>')
						.attr('type', 'number')
						.attr('class', 'full_cell')
						.attr('name', 'multi[][height]')
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
});