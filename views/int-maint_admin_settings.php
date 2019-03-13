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
						<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( '_int-maint_site_logo_id' ) ); ?>' height='100'>
					</div>
					<input type="text" name="logo_path" id="logo_path" class="regular-text" value="<?php echo get_option( '_int-maint_site_logo_path' ); ?>">
					<input type="button" name="logo_upload_image_button" id="logo_upload_image_button" class="button-secondary upload-button" value="Media Image Library">
					<input type="hidden" name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( '_int-maint_site_logo_id' ); ?>'>
					<p class="description">You can upload or select your logo using the Media Image Library, or you can enter its path in the input above.
				</td>
			</tr>
			<tr>
				<th>
					<label for="message_heading">Message Heading</label>
				</th>
				<td>
					<input type="text" name="message_heading" class="regular-text" value="<?php echo get_option( '_int-maint_message_heading' ); ?>">
					<p class="description">You can enter your own message heading here or leave it blank to display "Coming Soon!" or "Down For Maintenance" depending on the site status.</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="message_body">Message Body</label>
				</th>
				<td>
					<textarea name="message_body" id="message_body" rows="10" cols="50" maxlength="1200"><?php echo get_option( '_int-maint_message_body' ); ?></textarea>
					<p class="description" id="char_string"><span id="char_count"><?php echo get_option( '_int-maint_message_body' ) !== '' ? strlen( get_option( '_int-maint_message_body' ) ) : 0; ?></span>/1200</p>
				</td>
			</tr>
		</table>
		<table class="form-table">
			<h2>Page Header Info</h2>
			<tr>
				<th>
					<label for="seo_title">SEO Title</label>
				</th>
				<td>
					<input type="text" name="seo_title" id="seo_title" class="regular-text" value="<?php echo get_option( '_int-maint_seo_title' ); ?>">
					<p class="description">Title that will appear in browser tabs and search engines. You can leave this blank for your site's default site title.</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="seo_desc">SEO Description</label>
				</th>
				<td>
					<textarea name="seo_desc" id="seo_desc" rows="4" cols="50" class="regular-text" value="<?php echo get_option( '_int-maint_seo_desc' ); ?>"></textarea>
					<p class="description">Site description that will appear in search engines. You can leave this blank for your site's default site description.</p>
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
					<input type="number" class="regular-text" id="sketch_id" name="sketch_id" minlength="5" value="<?php echo get_option( '_int-maint_sketch_id' ); ?>">
					<p class="description" id="single_desc">Enter the ID of the sketch you want to appear on your Coming Soon/Maintenance page. The ID is the number after /sketch/ in the OpenProcessing URL. <br>Ex: https://openprocessing.org/sketch/<u>19836</u></p>
				</td>
			</tr>
			<tr class="single_dimensions">
				<th>
					<label for="sketch_width">Sketch Width</label>
				</th>
				<td>
					<input type="number" name="sketch_width" class="small-text" min="1" max="5000" value="<?php echo get_option( '_int-maint_sketch_width' ); ?>">
					<p class="description">Sketch width in pixels.</p>
				</td>
			</tr>
			<tr class="single_dimensions">
				<th>
					<label for="sketch_height">Sketch Height</label>
				</th>
				<td>
					<input type="number" name="sketch_height" class="small-text" min="1" max="5000" value="<?php echo get_option( '_int-maint_sketch_height' ); ?>">
					<p class="description">Sketch height in pixels.</p>
				</td>
			</tr>
		</table>
		<button type="submit" name="submit" value="Save Changes" class="button button-primary">Save Changes</button>
	</form>
</div>
	
<?php endif;
