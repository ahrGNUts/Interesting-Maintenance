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
			'pop_random' => array(
				'value' => 'pop_random',
				'text' => 'Popular Random Sketch'
			),
			'random' => array(
				'value' => 'random',
				'text' => 'Completely Random Sketch'
			)
		);
		$current = get_option( '_int-maint_sketch_type' );
	
	} else {
		return '<option>Invalid Select Group</option>';
	}
	
	foreach( $options as $option ) {
		$selected = $current == $option['value'] ? 'selected' : '';// 
		$out .= '<option value="' . $option['value'] . '" ' . $selected . '>' . $option['text'] . '</option>';
	}
	
	return $out;
}

/*
	@param div -- string (div name)
	@return status -- string (hidden or empty)
	
	Checks if div should be hidden or shown based on currently selected option from select element. 
	Currently only helps 
*/
function intmaint_set_visibility( $div ) {
	
}