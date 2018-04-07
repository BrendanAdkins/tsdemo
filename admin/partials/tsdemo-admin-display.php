<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://brendanadkins.com/
 * @since      1.0.0
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form action='options.php' method='post'>

	<?php
	settings_fields(TS_DEMO_OPTION_PREFIX);
	do_settings_sections(TS_DEMO_OPTION_PREFIX);
	submit_button();
	?>

</form>