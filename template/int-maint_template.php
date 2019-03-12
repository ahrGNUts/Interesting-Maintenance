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
		<title>works</title>
		<?php intmaint_echo_styles_scripts(); ?>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<img src="">
					<h1 class="text-center"><?php intmaint_get_message_heading(); ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<p class="text-center"><?php intmaint_get_message_body(); ?></p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<iframe id="op_frame" src="<?php echo intmaint_build_sketch_url(); ?>" width="615" height="550">
				</div>
			</div>
		</div>
	</body>
</html>
