<?php
/*
	Template for Coming Soon/Maintenance page
	
	@since 0.5
	@author Patrick Strube
*/
defined( 'ABSPATH' ) || exit; 
require( 'int-maint_template_functions.php' ); ?>
<html>
	<head>
		<title><?php intmaint_get_seo_data( 'title' ); ?></title>
		<meta name="description" content="<?php intmaint_get_seo_data( 'desc' );?>">
		<?php intmaint_echo_styles_scripts(); ?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-12 d-flex justify-content-center align-items-center mt-2">
					<img class="mr-3" src="<?php intmaint_get_logo_path(); ?>">
					<h1><?php intmaint_get_message_heading(); ?></h1>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-sm-12">
					<p class="text-center"><?php intmaint_get_message_body(); ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php intmaint_build_sketch_iframe(); ?>
				</div>
			</div>
		</div>
	</body>
</html>
