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
		<?php wp_nonce_field( 'process_int-maint_options', '_int-maint_settings_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th>
					<label for="site_status">Site Status</label>
				</th>
				<td>
					<select name="page_heading">
						<option value="Active">Active</option>
						<option value="Coming Soon">Coming Soon</option>
						<option value="Maintenance Mode">Maintenance Mode</option>
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
					<input type="text" name="logo_path" id="logo_path" class="regular-text">
					<input type="button" name="logo_upload_image_button" id="logo_upload_image_button" class="button-secondary upload-button" value="Media Image Library">
					<p class="description">You can upload or select your logo using the Media Image Library, or you can enter its path in the input above.
				</td>
			</tr>
			<tr>
				<th>
					<label for="heading_message">Message Heading</label>
				</th>
				<td>
					<input type="text" name="heading_message" class="regular-text">
					<p class="description">You can enter your own message heading here or leave it blank to display "Coming Soon!" or "Down For Maintenance" depending on the site status.</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="maintenance_message">Message Body</label>
				</th>
				<td>
					<textarea name="maintenance_message" rows="10" cols="50" maxlength="2500"></textarea>
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