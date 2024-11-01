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

$families = get_terms(
	array(
		'taxonomy'   => 'family-group',
		'hide_empty' => false,
	)
);
?>
<div class="wrap">
	<h2><?php esc_html_e( 'TreePress Export', 'treepress' ); ?></h2>
	<p><?php esc_html_e( 'This will export a Zip file for download, which contains a Gedcom (.ged) format (version 5.5) file of your data.', 'treepress' ); ?>  </p>
	<form action="" method="post">
		<?php wp_nonce_field( 'export_ged' ); ?>
		<table>
			<tr>
				<td>
					<?php esc_html_e( 'Select Family:', 'treepress' ); ?>
				</td>
				<td>
					<select name="family">
						<?php
						foreach ( $families as $key => $family ) {
							?>
						<option value="<?php echo esc_attr( $family->term_id ); ?>">
							<?php echo esc_html( $family->name ); ?>
						</option>
							<?php
						}
						?>
					</select>
				</td>
				<td>
					<input type="submit" name="submit" value="<?php esc_html_e( 'Export', 'treepress' ); ?>">
				</td>
			</tr>
		</table>
	</form>
</div>
<?php
$export_ged = isset( $_POST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ) : null;

if ( null !== $export_ged && wp_verify_nonce( $export_ged, 'export_ged' ) ) {

	$family = isset( $_POST['family'] ) ? sanitize_text_field( wp_unslash( $_POST['family'] ) ) : '';

	if ( $family ) {

		$query = new \WP_Query(
			array(
				'post_type'      => 'member',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'tax_query'      => array(
					array(
						'taxonomy' => 'family-group',
						'field'    => 'id',
						'terms'    => $family,
					),
				),

			)
		);

		$members = $query->posts;
		$indis   = array();

		foreach ( $members as $key => $member ) {
			$member_names = get_post_meta( $member, 'names' ) ? get_post_meta( $member, 'names' ) : array( 'name' => '' );
			$name         = current( $member_names )['name'];
			$sex          = get_post_meta( $member, 'sex', true );
			$event        = get_post_meta( $member, 'even' );
			foreach ( $event as $keye => $ev ) {
				$event[ $ev['type'] ][] = $ev;
				unset( $event[ $keye ] );
			}
			$birt = null;
			if ( isset( $event['BIRT'] ) && $event['BIRT'] ) {
				if ( null !== current( $event['BIRT'] ) && current( $event['BIRT'] ) ) {
					$birt = current( $event['BIRT'] )['date'];
				}
			}
			$deat = null;
			if ( isset( $event['DEAT'] ) && $event['DEAT'] ) {
				if ( null !== current( $event['DEAT'] ) && current( $event['DEAT'] ) ) {
					$deat = current( $event['DEAT'] )['date'];
				}
			}

			$fams = get_post_meta( $member, 'fams' );
			$famc = get_post_meta( $member, 'famc' );

			$indis[ $member ] = array(
				'id'   => get_post_meta( $member, 'Id', true ),
				'name' => $name,
				'sex'  => $sex,
				'birt' => $birt,
				'deat' => $deat,
				'fams' => $fams,
				'famc' => $famc,
			);
		}

		$query = new \WP_Query(
			array(
				'post_type'      => 'family',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'husb',
						'value'   => $members,
						'compare' => 'IN',
					),
					array(
						'key'     => 'wife',
						'value'   => $members,
						'compare' => 'IN',
					),
					array(
						'key'     => 'chil',
						'value'   => $members,
						'compare' => 'IN',
					),
				),
			)
		);

		$families = $query->posts;
		$fams     = array();

		foreach ( $families as $key => $family ) {
			$husb = get_post_meta( $family->ID, 'husb', true );
			$wife = get_post_meta( $family->ID, 'wife', true );
			$chil = get_post_meta( $family->ID, 'chil' );

			$fams[ $family->ID ] = array(
				'id'   => get_post_meta( $family->ID, 'Id', true ),
				'husb' => $husb,
				'wife' => $wife,
				'chil' => $chil,
			);
		}

		$text = '';
		$text .= '0 HEAD' . "\n";
		$text .= '1 SOUR TreePress' . "\n";
		$text .= '2 NAME TreePress - Family Trees on WordPress' . "\n";
		$text .= '2 VERS 1.0.0' . "\n";
		$text .= '2 CORP Black and White Digital Ltd' . "\n";
		$text .= '3 ADDR Unit F, 44-48 Shepherdess Walk' . "\n";
		$text .= '4 CONT London, N1 7JP' . "\n";
		$text .= '4 CONT UK' . "\n";
		$text .= '1 DATE 18 JAN 2019' . "\n";
		$text .= '2 TIME 20:59:40' . "\n";
		$text .= '1 FILE test.ged' . "\n";
		$text .= '1 SUBM @SUBM@' . "\n";
		$text .= '1 GEDC' . "\n";
		$text .= '2 VERS 5.5' . "\n";
		$text .= '2 FORM LINEAGE-LINKED' . "\n";
		$text .= '1 CHAR UTF-8' . "\n";
		foreach ( $indis as $key => $indi ) {
			$text .= '0 @' . $indi['id'] . '@ INDI' . "\n";
			$text .= '1 NAME /' . $indi['name'] . "/\n";
			$text .= '1 SEX ' . $indi['sex'] . "\n";
			if ( $indi['birt'] ) {
				$text .= '1 BIRT' . "\n";
				$text .= '2 DATE ' . $indi['birt'] . "\n";
			}
			if ( $indi['deat'] ) {
				$text .= '1 DEAT' . "\n";
				$text .= '2 DATE ' . $indi['deat'] . "\n";
			}
			if ( $indi['fams'] ) {
				foreach ( $indi['fams'] as $key => $value ) {
					$text .= '1 FAMS @' . $value['fam'] . '@' . "\n";
				}
			}
			if ( $indi['famc'] ) {
				foreach ( $indi['famc'] as $key => $value ) {
					$text .= '1 FAMC @' . $value['fam'] . '@' . "\n";
				}
			}
		}
		foreach ( $fams as $key => $fam ) {
			if ( ( $fam['husb'] && $fam['chil'] ) || ( $fam['wife'] && $fam['chil'] ) || ( $fam['husb'] && $fam['wife'] ) ) {
				$text .= '0 @F' . $fam['id'] . '@ FAM' . "\n";
				if ( $fam['husb'] ) {
					$text .= '1 HUSB @I' . $fam['husb'] . '@' . "\n";
				} else {
					$text .= '1 HUSB ' . "\n";
				}
				if ( $fam['wife'] ) {
					$text .= '1 WIFE @I' . $fam['wife'] . '@' . "\n";
				} else {
					$text .= '1 WIFE ' . "\n";
				}
				$text .= '1 MARR' . "\n";
				if ( $fam['chil'] ) {
					foreach ( $fam['chil'] as $key => $chil ) {
						$text .= '1 CHIL @I' . $chil . '@' . "\n";
					}
				}
			}
		}
		$text  .= '0 @SUBM@ SUBM' . "\n";
		$text  .= '0 TRLR';
		$family = sanitize_text_field( wp_unslash( $_POST['family'] ) );
		if ( ! file_exists( WP_CONTENT_DIR . '/treepress/temp' ) ) {
			mkdir( WP_CONTENT_DIR . '/treepress/temp', 0777, true );
		}
		$myfile = fopen( WP_CONTENT_DIR . '/treepress/temp/' . $family . '.ged', 'w' );
		fwrite( $myfile, $text );
		fclose( $myfile );
		$zip = new ZipArchive();
		if ( true !== $zip->open( WP_CONTENT_DIR . '/treepress/temp/' . $family . '.zip', ZipArchive::CREATE ) ) {
			die( 'Could not open archive' );
		}
		$zip->addFile( esc_attr( WP_CONTENT_DIR ) . '/treepress/temp/' . esc_attr( $family ) . '.ged', $family . '.ged' );
		$zip->close();
		echo '<script> location.replace("' . esc_attr( WP_CONTENT_URL ) . '/treepress/temp/' . esc_attr( $family ) . '.zip") </script>';
	}
}
