<?php
/*
	Admin menu view helper functions
	
	@since 0.6
	@author Patrick Strube
*/
defined( 'ABSPATH' ) || exit;

/*
	@param type -- string ('site_status' or 'sketch_type' expected)
	@return out -- string
	
	Function will build options for select element based on type and then return them. Echo should be used to output return value of function.
	If new options need to be added to either of these or new select elements are used on the admin form, this is where they should be added.
*/
function intmaint_build_select_options( $type ) {
	$out = "";
	
	if( $type == 'site_status' ){
		$options = array(
			'active' => array(
				'value' => '1',
				'text' => 'Active'
			),
			'coming_soon' => array(
				'value' => '2',
				'text' => 'Coming Soon'
			),
			'maintenance_mode' => array (
				'value' => '3',
				'text' => 'Maintenance Mode'
			)
		);
		$current = get_option( '_int-maint_site_status' );
	
	} else if( $type == 'sketch_type' ) {
		$options = array(
			'static' => array(
				'value' => 'static',
				'text' => 'Static Sketch'
			),
			'multiple' => array(
				'value' => 'multiple',
				'text' => 'Multiple Sketches'
			),
			/*'pop_random' => array( // TODO: more planning for random sketches
				'value' => 'pop_random',
				'text' => 'Popular Random Sketch'
			),
			'random' => array(
				'value' => 'random',
				'text' => 'Completely Random Sketch'
			)*/
		);
		$current = get_option( '_int-maint_sketch_type' );
	
	} else {
		return '<option>Invalid Select Group</option>';
	}
	
	foreach( $options as $option ) {
		$selected = $current == $option['value'] ? 'selected' : ''; // is current option selected?
		$out .= '<option value="' . $option['value'] . '" ' . $selected . '>' . $option['text'] . '</option>';
	}
	
	return $out;
}

/*
	@param group -- string (grouping name)
	@return status -- string or null
	
	Checks if grouping element should be hidden or shown based on currently selected option from select element.
	Currently only works with sketch_type select.
*/
function intmaint_set_visibility( $group ) {
	if( get_option( '_int-maint_sketch_type' ) != $group ){
		return 'hidden';
	}
	return null;
}

/*
	@return out -- string
	
	Generates as many rows in the multi table as there are associated OpenProcessing sketches.
	Each row of the table will be serialized into its own record in the db.
*/
function intmaint_init_multi_fields() {
	$multi_count = get_option( '_int-maint_multi_count' );
	$out = '';
	
	if( $multi_count == 0 ) {
		$out .= intmaint_output_multi_row( null, 1 );
	} else {
		for( $i = 0; $i < $multi_count; $i++ ) {
			$data = unserialize( get_option( '_int-maint_multi_data_' . $i ) );
			$out .= intmaint_output_multi_row( $data, $i );
		}
	}
	
	return $out;
}

/*
	@param row_data -- array or null
	
	row_data expected to be null or an array of data built from unserializing multi sketch table row records from the database.
	function will return markup for one row in the multi sketch table populated with data from the database (if any exists)
*/ 
function intmaint_output_multi_row( $row_data, $idx ) {
	$out = '';
	
	$out .= '<tr>';
	$out .= 	'<td>';
	$out .= 		'<input type="number" class="full_cell" name="multi[' . $idx . '][id]" minlength="5" value="' . $row_data['id'] . '">';
	$out .= 	'</td>';
	$out .= 	'<td>';
	$out .= 		'<input type="number" class="full_cell" name="multi[' . $idx . '][width]" value="' . $row_data['width'] . '">';
	$out .= 	'</td>';
	$out .= 	'<td>';
	$out .= 		'<input type="number" class="full_cell" name="multi[' . $idx . '][height]" value="' . $row_data['height'] . '">';
	$out .= 	'</td>';
	$out .= 	'<td>';
	$out .= 		'<span class="dashicons dashicons-no btn_delete"></span>';
	$out .= 	'</td>';
	$out .= '</tr>';
	
	return $out;
}
