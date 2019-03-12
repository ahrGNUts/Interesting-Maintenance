<?php
/*
	Admin menu settings page
	
	@since 0.1
	@author Patrick Strube
*/
defined( 'ABSPATH' ) || exit; 

if( is_admin() && current_user_can( 'manage_options' ) ): ?>

<div class="wrap">
	<h2>Interesting Maintenance Settings</h2>
	<form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'process_intmaint_options', '_int-maint_settings_nonce' ); ?>
		<input type="hidden" name="action" value="process_intmaint_options">
		<table class="form-table">
			<tr>
				<th>
					<label for="site_status">Site Status</label>
				</th>
				<td>
					<select name="site_status">
						<?php
							$statuses = array(
								'active' => array(
									'code' => '1',
									'text' => 'Active'
								),
								'coming_soon' => array(
									'code' => '2',
									'text' => 'Coming Soon'
								),
								'maintenance_mode' => array (
									'code' => '3',
									'text' => 'Maintenance Mode'
								)
							);
							
							foreach( $statuses as $status ){
								$selected = get_option( '_int-maint_site_status' ) == $status['code'] ? 'selected' : '';
								
								echo '<option value="' . $status['code'] . '"' . $selected . '>' . $status['text'] . '</option>';
							}
						?>
					</select>
					<p class="description">Set the site's current status. When Admin users are logged in, they will see the regular site. If any other visitors come to the site, they will see the "Maintenance Mode" or "Coming Soon" screens.</p>
				</td>
			</tr>
		</table>
		<table class="form-table">
			<h2>Visitor Message</h2>
			<tr>
				<th>
					<label for="logo_path">Logo</label>
				</th>
				<td>
					<div class='image-preview-wrapper'>
						<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
					</div>
					<input type="text" name="logo_path" id="logo_path" class="regular-text">
					<input type="button" name="logo_upload_image_button" id="logo_upload_image_button" class="button-secondary upload-button" value="Media Image Library">
					<input type="hidden" name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
					<p class="description">You can upload or select your logo using the Media Image Library, or you can enter its path in the input above.
				</td>
			</tr>
			<tr>
				<th>
					<label for="message_heading">Message Heading</label>
				</th>
				<td>
					<input type="text" name="message_heading" class="regular-text">
					<p class="description">You can enter your own message heading here or leave it blank to display "Coming Soon!" or "Down For Maintenance" depending on the site status.</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="message_body">Message Body</label>
				</th>
				<td>
					<textarea name="message_body" rows="10" cols="50" maxlength="1200"></textarea>
					<p class="description"><?php echo get_option( '_int-maint_message_body' ) !== '' ? strlen( get_option( '_int-maint_message_body' ) ) : 0; ?>/1200</p>
				</td>
			</tr>
		</table>
		<table class="form-table">
			<h2>OpenProcessing Sketch Settings</h2>
			<tr>
				<th>
					<label for="sketch_type">Sketch Type</label>
				</th>
				<td>
					<select name="sketch_type" id="sketch_type">
						<option value="static">Static Sketch</option>
						<option value="pop_random">Popular Random Sketch</option>
						<option value="random">Completely Random Sketch</option>
					</select>
					<span id="sketch_help" class="dashicons dashicons-editor-help"></span>
					<br>
					<input type="text" class="regular-text" id="sketch_url" name="sketch_url">
					<p class="description">Enter the full URL of the sketch you want to appear on your Coming Soon/Maintenance page.</p>
				</td>
			</tr>
		</table>
		<button type="submit" name="submit" value="Save Changes" class="button button-primary">Save Changes</button>
	</form>
</div>
	
<?php endif;