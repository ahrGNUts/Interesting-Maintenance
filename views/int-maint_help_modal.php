<?php
/*
	Settings page help modal content
	
	@since 0.2
	@author Patrick Strube
*/

defined( 'ABSPATH' ) || exit;


if( strpos( $_SERVER['HTTP_REFERER'], '?page=interesting_maintenance') !== false ): ?>

<div id="help-modal" class="hidden" style="max-width:800px">
	<dl>
		<dt>Static Sketch</dt>
		<dd>This will display the same sketch each time a visitor visits the site. If you don't know what to put here, go check out <a href="https://openprocessing.org/browse">openprocessing.org</a> to find a neat sketch!</dd>
		<dt>Multiple Sketches</dt>
		<dd>This allows you to store the ID, width, and height of up to 15 sketches. When a user visits your site during downtime, one of the sketches you've chosen will be selected at random and rendered for the user.</dd>
		<?php // TODO: more planning for random sketches 
			/*<dt>Popular Random Sketch</dt>
		<dd>The URLs from a maximum of 15 popular sketches on <a href="https://openprocessing.org/browse">openprocessing.org</a> will be stored and shown to visitors. This list will be rebuilt on the 1st of each month, so there will always be new content!</dd>
		<dt>Completely Random Sketch</dt>
		<dd>This will display a totally random sketch from <a href="https://openprocessing.org/browse">openprocessing.org</a>. Use caution with this option, as the displayed sketch may be broken or not work properly.</dd>*/?>
	</dl>
</div>

<?php endif;
