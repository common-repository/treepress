<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.blackandwhitedigital.eu/
 * @since      1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/admin/partials
 */


 /*
		$data = array(
			array (    
				'id' => '11',
				'name' => 'Style 1 ( A ) Vertical Descendant (Spouses next to each other)'
			),
			array (
				'id' => '12',
				'name' => 'Style 1 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors)'
			),
			array (
				'id' => '13',
				'name' => 'Style 1 ( C ) Vertical (Spouses next to each other) Hourglass (Each Spouse Ancestors)'
			),
			array (
				'id' => '14',
				'name' => 'Style 1 ( D ) Vertical (Spouses next to each other) Hourglass 2 (Person + Spouse Ancestors & Descendants) (coming)'
			),
			array (
				'id' => '21',
				'name' => 'Style 2 ( A ) Vertical Descendant (Inline spouse indicated by ♡) (addon)'
			),
			array (
				'id' => '31',
				'name' => 'Style 3 ( A ) Vertical Descendant (Spouses next to each other) (coming)'
			),
			array (
				'id' => '32',
				'name' => 'Style 3 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors) (coming)'
			),
			array (
				'id' => '33',
				'name' => 'Style 3 ( C ) Vertical Hourglass (Spouses next to each other) (Each Spouse Ancestors) (coming)'
			),
			array (
				'id' => '34',
				'name' => 'Style 3 ( D ) Vertical Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants) (coming)'
			),
			array (
				'id' => '41',
				'name' => 'Style 4 ( A ) Horizontal Descendant (Spouses next to each other) (coming)'
			),
			array (
				'id' => '42',
				'name' => 'Style 4 ( B ) Horizontal Pedigree (Spouses next to each other) (Shows Ancestors) (addon)'
			),
			array (
				'id' => '43',
				'name' => 'Style 4 ( C ) Horizontal Hourglass (Spouses next to each other) (Each Spouse Ancestors) (addon)'
			),
			array (
				'id' => '44',
				'name' => 'Style 4 ( D ) Horizontal Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants) (coming)'
			),
			array (
				'id' => '51',
				'name' => 'Style 5 ( A ) Horizontal Descendant (Inline spouse indicated by ♡) (addon)'
			),
			array (
				'id' => '61',
				'name' => 'Style 6 ( A ) Horizontal Text Descendant (Compact) (coming)'
			),
			array (
				'id' => '62',
				'name' => 'Style 6 ( B ) Horizontal Text Pedigree (Compact) (Shows Ancestors) (addon)'
			),
			array (
				'id' => '63',
				'name' => 'Style 6 ( C ) Horizontal Text Hourglass (Compact) (Each Spouse Ancestors) (coming)'
			),
			array (
				'id' => '64',
				'name' => 'Style 6 ( D ) Horizontal Text Hourglass 2 (Compact) (Person + Spouse Ancestors & Descendants) (coming)'
			)
		);
		

		// foreach ($data as $key => $value) {
		
		// 	$my_post = array(
		// 		'post_title' => $value['name'],
		// 		'post_content' => '',
		// 		'post_status' => 'publish',
		// 		'post_author' => 1,
		// 		'post_type' => 'chart',
		// 	);
		// 	$post_id = wp_insert_post($my_post);
			
		// 	$dd = [];

		// 	$dd['chart_type'] = $value['id'];
		// 	$dd['group_id'] = 30;
		// 	$dd['root_id'] = 2695;
			
		// 	update_post_meta($post_id, 'chart', $dd);
	
		// }
		*/
		
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">
				<?php _e('Account Settings', 'treepress');?>
			</h1>
			<p>
				<strong><?php _e('Thank you for using TreePress.', 'treepress');?> </strong>
			</p>
			<p>
				<?php _e('You can find a comprehensive support guide here.', 'treepress');?>   https://www.treepress.net/docs/
			</p>
			<p>
				<strong> <?php _e('Help us Improve TreePress', 'treepress');?></strong>
			</p>

			<p>
				<?php _e('If you would like to make a small donation to help us further develop the plugin, please check out our PayPal link here:', 'treepress');?>
			</p>

			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="RSXSRDQ7HANFQ" />
				<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
				<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
			</form>

			<br>
			<br>

			<?php
			if (tre_fs()->is_not_paying()) {
				echo '<section>';
				echo '<h1>' . __('Awesome Premium Features', 'treepress') . '</h1>';
					echo '<a href="' . tre_fs()->get_upgrade_url() . '">' . __('Upgrade Now!', 'treepress') . '</a>';
				echo '</section>';
			}
			?>
		</div>
		<?php