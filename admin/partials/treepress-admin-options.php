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

global $wp_version;

if ( isset( $_POST['update_options'] ) ) {

	if ( function_exists( 'check_admin_referer' ) ) {
		check_admin_referer( 'family-tree-action_options' );
	}

	$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'dyi';

	if ( 'to' === $active_tab ) {
		if ( isset( $_POST['showcreditlink'] ) ) {
			update_option( 'showcreditlink', 'on' );
		} else {
			update_option( 'showcreditlink', '' );
		}
	}

	echo '<div class="updated"><p> ' . esc_html__( 'Options saved.', 'treepress' ) . ' </p></div>';
}

$showcreditlink = get_option( 'showcreditlink', 'on' );

?>
<style type="text/css">
	.treepress-options.wrap .form-table th,
	.treepress-options.wrap .form-table td {
		padding: 15px 0;
	}
	.treepress-options.wrap .form-table th h3,
	.treepress-options.wrap .form-table td h3 {
		margin: 0;
	}
</style>
<div class="wrap treepress-options">
	<h2>
		<?php esc_html_e( 'TreePress Options', 'treepress' ); ?>
	</h2>
	<form name="ft_main" method="post">
		<?php
		if ( function_exists( 'wp_nonce_field' ) ) {
			wp_nonce_field( 'family-tree-action_options' );
		}
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'dyi';
		?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=treepress-options&tab=dyi" class="nav-tab <?php echo 'dyi' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Display Your Information', 'treepress' ); ?>
			</a>
			<a href="?page=treepress-options&tab=to" class="nav-tab <?php echo 'to' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'TreePress Options ', 'treepress' ); ?>
			</a>
		</h2>

		<?php if ( 'dyi' === $active_tab ) { ?>
			<br>
			<h3><?php esc_html_e( 'Managing Your Tree Display', 'treepress' ); ?></h3>

			<p>
				<?php esc_html_e( 'TreePress uses shortcodes to display family trees in pages of posts.  There are two formats.  Which one you use depends on your data and what you want to achieve:', 'treepress' ); ?>
			</p>

			<p>
				<?php echo esc_html__( 'Shortcode format 1 = ', 'treepress') . ' <code>[family-tree family=\'<span style="color:red;">{family_group_id}</span>\']</code> '; ?>
			</p>

			<p>
				<?php esc_html_e( 'If every member of your tree has a full date of birth this shortcode will display the whole family starting with the oldest member. ', 'treepress' ); ?>
			</p>

			<p>
				<?php echo esc_html__( 'Shortcode format 2 = ', 'treepress') . '<code>[family-tree family=\'<span style="color:red;">{family_group_id}</span>\' root=\'<span style="color:red;">{oldest_member_id_of_family}</span>\'] </code>'; ?>
			</p>

			<p>
				<?php esc_html_e( 'If you do not have full birth dates for every family member or simply want to create a tree from a specific person \'root\' within a larger family, use this shortcode.   ', 'treepress' ); ?>
			</p>

			<h3><?php esc_html_e( 'Managing Your Family List Display', 'treepress' ); ?></h3>

			<p>
				<?php echo esc_html__( 'To create a list of all family members, use the shortcode format', 'treepress') . ' <code>[family-members family=\'<span style="color:red;">{family_group_id}</span>\']</code>'; ?>
			</p>

			<p>
				<?php esc_html_e( 'You can control which family tree the [tree icon image] icon links to by adding a page or post link in the individual Family Tree page.', 'treepress' ); ?>
			</p>

			<h3> <?php esc_html_e( 'Finding IDs for your Shortcodes', 'treepress' ); ?> </h3>

			<p>
				<?php
				esc_html_e( 'You can find the family_ID on the Families page - when added, the code', 'treepress' );
				?>
				<span style="color:red;">{family_group_id}</span>
				<?php
				esc_html_e( ' will look something like \'42\'.  ', 'treepress' );
				?>
			</p>

			<p>
				<?php esc_html_e( 'You can find the member ID on the All Members page.', 'treepress' ); ?>
			</p>
		<?php } ?>


		<?php if ( 'to' === $active_tab ) { ?>
			<br>
			<h3><?php esc_html_e( 'Treepress options  (applicable on svg tree)', 'treepress' ); ?></h3>
			<table width="300">
				<tr valign="top">
					<th scope="row" style="text-align: left;">
						<label for="showcreditlink">
							<?php esc_html_e( 'Show Credit Link', 'treepress' ); ?>
						</label>
					</th>
					<td>
						<input name="showcreditlink" type="checkbox" id="showcreditlink" <?php echo checked( $showcreditlink, 'on' ); ?> />
					</td>
				</tr>
			</table>
		<?php } ?>

		<?php if ( 'cr' === $active_tab ) { ?>
			<br>
			<h3>
				<?php esc_html_e( 'Credit link', 'treepress' ); ?>
			</h3>
			<p>
				<?php
				printf(
					'%s<b>%s</b>%s - ',
					esc_html__( 'If you use this plugin then we would be very grateful for some appreciation. ', 'treepress' ),
					esc_html__( 'Appreciation makes us happy.', 'treepress' ),
					esc_html__( 'If you don\'t want to link to us from the bottom of the family tree then please consider these other options', 'treepress' )
				);
				?>
				<?php
				printf(
					'<br> <b>i)</b> %s <a target="_blank" href="#">%s</a> %s',
					esc_html__( 'send us an', 'treepress' ),
					esc_html__( 'email', 'treepress' ),
					esc_html__( 'and let us know about your family tree website (that would inspire us),', 'treepress' )
				);
				?>
				<?php
				printf(
					'<br> <b>ii)</b> %s <a target="_blank" href="http://www.treepress.net">www.treepress.net</a> %s,',
					esc_html__( ' include a link to', 'treepress' ),
					esc_html__( 'from some other location of your site (that would help us feed our children)', 'treepress' )
				);
				?>
				<?php
				printf(
					'<br> <b>iii)</b> %s<a target="_blank" href="#">%s</a>.',
					esc_html__( 'Give us a good rating at the', 'treepress' ),
					esc_html__( 'GWordpress Plugin Site', 'treepress' )
				);
				?>
			</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="showcreditlink">
							<?php esc_html_e( 'Show powered-by link', 'treepress' ); ?>
						</label>
					</th>
					<td>
						<input name="showcreditlink" type="checkbox" id="showcreditlink" <?php echo checked( $showcreditlink, 'on' ); ?> />
					</td>
				</tr>
			</table>
		<?php } ?>

		<p class="submit">
			<input type="hidden" name="action" value="update" />
			<input type="submit" name="update_options" class="button" value="<?php esc_html_e( 'Save Changes', 'treepress' ); ?> &raquo; " />
		</p>
	</form>
</div>
