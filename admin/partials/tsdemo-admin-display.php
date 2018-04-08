<?php

/**
 * Provide framework for listing registered settings in admin settings menu
 *
 * @since      1.0.0
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/admin/partials
 */
?>

<form action='options.php' method='post'>

	<?php
	settings_fields(TS_DEMO_OPTION_PREFIX);
	do_settings_sections(TS_DEMO_OPTION_PREFIX);
	submit_button();
	?>

</form>