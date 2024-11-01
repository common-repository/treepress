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

$ref_id  = get_post_meta( $post->ID, 'Id', true ) ? get_post_meta( $post->ID, 'Id', true ) : 'F' . $post->ID;
$husb    = get_post_meta( $post->ID, 'husb', true );
$wife    = get_post_meta( $post->ID, 'wife', true );
$chil    = get_post_meta( $post->ID, 'chil' );
$even    = get_post_meta( $post->ID, 'even' );
$args    = array(
	'post_type'      => 'member',
	'posts_per_page' => -1,
	'post_status'    => array( 'publish' ),
);
$query   = new WP_Query( $args );
$members = $query->posts;

if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ), '_new_family' ) ) ) {
	if ( isset( $_GET['spouse'] ) ) {
		$spouse = sanitize_text_field( wp_unslash( $_GET['spouse'] ) );
		$sex    = get_post_meta( $spouse, 'sex', true );
		if ( 'M' === $sex ) {
			$husb = get_post_meta( $spouse, 'Id', true );
		}
		if ( 'F' === $sex ) {
			$wife = get_post_meta( $spouse, 'Id', true );
		}
	}
	if ( isset( $_GET['chil'] ) ) {
		$chil   = sanitize_text_field( wp_unslash( $_GET['spouse'] ) );
		$chil[] = get_post_meta( $chil, 'Id', true );
	}
}
?>
<?php wp_nonce_field( 'treepress_family_info_nonce_action', 'treepress_family_info_nonce' ); ?>
<div style="display:none;" id="dialog-chil" title="<?php echo esc_attr__( 'Add Children', 'treepress' ); ?>">
	<table width="100%">
		<tr>
			<td width="125">
				<label style="width:125px;display:block;"> 
					<strong><?php echo esc_html__( 'Children', 'treepress' ); ?></strong>
				</label>
			</td>
			<td>
				<select class="select_children tp-select2" style="width: 100%">
					<option value="">
						<?php echo esc_html__( 'Select Children', 'treepress' ); ?>
					</option>
					<?php
					foreach ( $members as $key => $member ) {
						$name = get_post_meta( $member->ID, 'names' ) ? get_post_meta( $member->ID, 'names' )[0] : array(
							'name' => '',
							'npfx' => '',
							'givn' => '',
							'nick' => '',
							'spfx' => '',
							'surn' => '',
							'nsfx' => '',
						);
						?>
					<option data-name="<?php echo esc_attr( $name['name'] ); ?>" data-url="<?php echo esc_attr( get_edit_post_link( $member->ID ) ); ?>" value="<?php echo esc_attr( get_post_meta( $member->ID, 'Id', true ) ); ?>">
						<?php $this->short_details_name( $member->ID ); ?>
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</table>
</div>

<table border="0">
	<tr>
		<td>
			<table border="0">
				<tr>
					<td>
						<label style="width:125px;display:block;">
							<strong><?php echo esc_html__( 'Id', 'treepress' ); ?></strong>
						</label>
					</td>
					<td colspan="2">
						<input readonly name="treepress[family][Id]" type="text" value="<?php echo esc_attr( $ref_id ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label style="width:125px;display:block;">
							<strong><?php echo esc_html__( 'Husband', 'treepress' ); ?> </strong>
						</label>
					</td>
					<td colspan="2">
						<select class="tp-select2" name="treepress[family][husb]">
							<option><?php echo esc_html__( 'Select Husband', 'treepress' ); ?></option>
							<?php
							foreach ( $members as $key => $member ) {
								$name = get_post_meta( $member->ID, 'names' ) ? get_post_meta( $member->ID, 'names' )[0] : array(
									'name' => '',
									'npfx' => '',
									'givn' => '',
									'nick' => '',
									'spfx' => '',
									'surn' => '',
									'nsfx' => '',
								);
								?>
							<option <?php echo selected( $husb, get_post_meta( $member->ID, 'Id', true ) ); ?> value="<?php echo esc_attr( get_post_meta( $member->ID, 'Id', true ) ); ?>">
								<?php $this->short_details_name( $member->ID ); ?>
							</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>
							<strong><?php echo esc_html__( 'Wife', 'treepress' ); ?> </strong>
						</label>
					</td>
					<td colspan="2">
						<select class="tp-select2" name="treepress[family][wife]">
							<option>
								<?php echo esc_html__( 'Select Wife', 'treepress' ); ?>
							</option>
							<?php
							foreach ( $members as $key => $member ) {
								$name = get_post_meta( $member->ID, 'names' ) ? get_post_meta( $member->ID, 'names' )[0] : array(
									'name' => '',
									'npfx' => '',
									'givn' => '',
									'nick' => '',
									'spfx' => '',
									'surn' => '',
									'nsfx' => '',
								);
								?>
							<option <?php echo selected( $wife, get_post_meta( $member->ID, 'Id', true ) ); ?> value="<?php echo esc_attr( get_post_meta( $member->ID, 'Id', true ) ); ?>">
								<?php $this->short_details_name( $member->ID ); ?>
							</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<label> <strong><?php echo esc_html__( 'Childrens', 'treepress' ); ?> </strong> </label>
					</td>
					<td class="chils">
						<?php foreach ( $chil as $key => $value ) { ?>
						<input type="hidden" name="treepress[family][chil][]" value="<?php echo esc_attr( $value ); ?>">
							<?php
							$args  = array(
								'post_type'      => 'member',
								'posts_per_page' => '1',
								'post_status'    => array( 'publish' ),
								'meta_query'     => array(
									array(
										'key'     => 'Id',
										'value'   => $value,
										'compare' => '=',
									),
								),
							);
							$query = new WP_Query( $args );
							if ( $query->posts ) {
								$member_id   = current( $query->posts )->ID;
								$member_inid = get_post_meta( $member_id, 'Id', true );
								$name        = get_post_meta( $member_id, 'names' ) ? get_post_meta( $member_id, 'names' )[0] : array(
									'name' => '',
									'npfx' => '',
									'givn' => '',
									'nick' => '',
									'spfx' => '',
									'surn' => '',
									'nsfx' => '',
								);
								?>
							<a href="<?php echo esc_attr( get_edit_post_link( $member_id ) ); ?>">
								<?php echo esc_html( $name['name'] ); ?>
							</a> |
							<a class="unlink-member" href="" data-key="chil" data-post_id="<?php echo esc_attr( $member_id ); ?>" data-family_id="<?php echo esc_attr( $ref_id ); ?>" data-member_id="<?php echo esc_attr( $member_inid ); ?>"><?php echo esc_html__( 'Unlink', 'treepress' ); ?></a> ';
							<br/>
								<?php
							}
						}
						if ( empty( $chil ) ) {
							echo esc_html__( 'No data.', 'treepress' );
						}
						?>
					</td>
					<td valign="top">
						<a id="opener-chil-add" href="#"><?php echo esc_html__( 'Add Children', 'treepress' ); ?></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br/>
<?php
$fe = array(
	'ANUL' => __( 'annulment', 'treepress' ),
	'CENS' => __( 'census', 'treepress' ),
	'DIV'  => __( 'divorce', 'treepress' ),
	'DIVF' => __( 'divorce filed', 'treepress' ),
	'ENGA' => __( 'engagement', 'treepress' ),
	'MARB' => __( 'marriage bann', 'treepress' ),
	'MARC' => __( 'marriage contract', 'treepress' ),
	'MARL' => __( 'marriage license', 'treepress' ),
	'MARR' => __( 'marriage', 'treepress' ),
	'MARS' => __( 'marriage settlement', 'treepress' ),
	'EVEN' => __( 'generic event', 'treepress' ),
);

?>
<div style="display:none;" id="dialog-family-event" title="<?php echo esc_html__( 'Add Family Event', 'treepress' ); ?>">
	<table>
	<tbody>
		<tr class="hide-treepress">
			<td colspan="2">
				<h4 style="margin-top:0;margin-bottom:5px;"> <b></b> <small> <a class="dtbody" href=""><?php echo esc_html__( 'Delete', 'treepress' ); ?></a></small></h4>
			</td>
		</tr>
		<tr class="hide-treepress-on-list">
			<td>
				<label style="width:125px;display:block;"> <strong><?php echo esc_html__( 'Type', 'treepress' ); ?> </strong> </label>
			</td>
			<td>
				<select class="event_type" name="treepress[family][even][xxx][type]">
				<?php foreach ( $fe as $fekey => $fe ) { ?>
					<option value="<?php echo esc_attr( $fekey ); ?>">
						(<?php echo esc_html( $fekey ); ?>) <?php echo esc_html( ucwords( $fe ) ); ?>
					</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label style="width:125px;display:block;"> <strong><?php echo esc_html__( 'Date', 'treepress' ); ?> </strong> </label>
			</td>
			<td>
				<input name="treepress[family][even][xxx][date]" type="text" value="">
			</td>
		</tr>
		<tr>
			<td>
				<label style="width:125px;display:block;"> <strong><?php echo esc_html__( 'Place', 'treepress' ); ?> </strong> </label>
			</td>
			<td>
				<input name="treepress[family][even][xxx][plac]" type="text" value="">
			</td>
		</tr>
		</tbody>
	</table>
</div>

<table border="0">
	<tr>
		<td>
			<table border="0" width="100%">
				<tr>
					<td>
						<h3 style="margin-top:0;margin-bottom:5px;"><?php echo esc_html__( 'Events', 'treepress' ); ?> <small><a id="opener-family-event-add" href="#"><?php echo esc_html__( 'Add', 'treepress' ); ?></a></small></h3>
					</td>
				</tr>
			</table>
			<table id="table-family-events" border="0" width="100%">
				<?php
				if ( ! empty( $even ) ) {
					foreach ( $even as $key => $event ) {
						?>
				<tbody>
					<tr>
						<td colspan="2">
							<h4  style="margin-top:0;margin-bottom:5px;">
								<?php echo esc_html( $event['type'] ); ?> <small> <a class="dtbody" href=""><?php echo esc_html__( 'Delete', 'treepress' ); ?></a></small>
							</h4>
						</td>
					</tr>
					<tr>
						<td>
							<label style="width:125px;display:block;"> <strong><?php echo esc_html__( 'Date', 'treepress' ); ?> </strong> </label>
						</td>
						<td>
							<input name="treepress[family][even][<?php echo esc_attr( $key ); ?>][type]" type="hidden" value="<?php echo esc_attr( $event['type'] ); ?>">
							<input name="treepress[family][even][<?php echo esc_attr( $key ); ?>][date]" type="text" value="<?php echo esc_attr( $event['date'] ); ?>">
						</td>
					</tr>
					<tr>
						<td>
							<label style="width:125px;display:block;"> <strong><?php echo esc_html__( 'Place', 'treepress' ); ?> </strong> </label>
						</td>
						<td>
							<input name="treepress[family][even][<?php echo esc_attr( $key ); ?>][plac]" type="text" value="<?php echo esc_attr( $event['plac'] ); ?>">
						</td>
					</tr>
				</tbody>
						<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
</table>

<div style="display:none;" id="dialog-family-note" title="<?php echo esc_html__( 'Add New Note', 'treepress' ); ?>">
	<table border="0" width="100%">
		<tbody>
			<tr>
				<td class="hide-treepress" valign="top">#</td>
				<td>
					<textarea name="treepress[family][note][][note]" style="width:100%;"></textarea>
				</td>
				<td class="hide-treepress" valign="top">
					<a class="dtbody" href=""><?php echo esc_html__( 'Delete', 'treepress' ); ?></a>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<br/>

<table border="0">
	<tr>
		<td>
			<table border="0" width="100%">
				<tr>
					<td>
						<h3 style="margin-top:0;margin-bottom:5px;">
							<?php echo esc_html__( 'Notes', 'treepress' ); ?>
							<small>
								<a id="opener-family-note-add"  href="">
									<?php echo esc_html__( 'Add', 'treepress' ); ?>
								</a>
							</small>
						</h3>
					</td>
				</tr>
			</table>
			<?php $notes = get_post_meta( $post->ID, 'note' ) ? get_post_meta( $post->ID, 'note' ) : array( array( 'note' => '' ) ); ?>
			<table border="0" width="100%" id="table-family-notes">
			<?php
			foreach ( $notes as $key => $note ) {
				if ( $note['note'] ) {
					?>
					<tbody>
						<tr>
							<td valign="top">
								<label style="width:125px;display:block;"> <strong>#<?php echo esc_html( $key + 1 ); ?> </strong> </label>
							</td>
							<td>
								<textarea name="treepress[family][note][<?php echo esc_attr( $key ); ?>][note]"><?php echo esc_html( $note['note'] ); ?></textarea>
							</td>
							<td valign="top">
								<a class="dtbody" href="">
									<?php echo esc_html__( 'Delete', 'treepress' ); ?>
								</a>
							</td>
						</tr>
					</tbody>
					<?php
				}
			}
			?>
			</table>
		</td>
	</tr>
</table>

<?php if ( defined( 'TP_DEV' ) && TP_DEV ) { ?>
	<pre>
	<?php print_r( get_post_meta( $post->ID ) ); ?>
	</pre>
<?php } ?>
