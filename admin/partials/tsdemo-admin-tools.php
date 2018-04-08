<?php

/**
 * Provide a loop for displaying transactions records
 *
 * @since      1.0.0
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/admin/partials
 */
?>

<h1>Donation Form Records</h1>

<table cellpadding="0" cellspacing="5" border="0" id="tsdemoDonationsList">
	<tr class="header">
		<th>Date</th>
		<th>Donor Email</th>
		<th>Amount</th>
		<th>Status</th>
		<th>Stripe Transaction ID</th>
	</tr>
<?php
	
	$args = array( 
                'post_type' => 'tsdemo_donation',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => -1
                );
	$query = new WP_Query($args);
	
	// The Loop
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$postID = get_the_ID();
			echo '<tr>';
			echo '<td>' . get_the_date() . '</td>';
			echo '<td>' . get_post_meta($postID, '_tsdemo_donor_email', true) . '</td>';
			echo '<td class="amount">' . get_post_meta($postID, '_tsdemo_don_amt', true) . '</td>';
			echo '<td>' . get_post_meta($postID, '_tsdemo_don_status', true) . '</td>';
			echo '<td>' . get_post_meta($postID, '_tsdemo_transact_id', true) . '</td>';
			echo '</tr>';
		}
	} else {
		echo '<td colspan="4">No donations yet.</td>';
	}
	
	/* Restore original Post Data */
	wp_reset_postdata();
?>
</table>