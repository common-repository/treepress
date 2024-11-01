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

if ( isset( $_POST['import'] ) ) {

	$tp_imports_errors = array();

	if ( ! current_user_can( 'administrator' ) ) {
		$tp_imports_errors[] = __( 'You are not authorized to import process.', 'treepress' );
	}

	$nonce_name = isset( $_POST['treepress_import_nonce'] ) ? sanitize_key( wp_unslash( $_POST['treepress_import_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce_name, 'treepress_import_nonce_action' ) ) {
		$tp_imports_errors[] = __( 'Something wrong. Try again.', 'treepress' );
	}

	if ( isset( $_POST['family_group_name'] ) && sanitize_text_field( wp_unslash( $_POST['family_group_name'] ) ) ) {
		$family_group_name = sanitize_text_field( wp_unslash( $_POST['family_group_name'] ) );
		if ( ! $family_group_name ) {
			$tp_imports_errors[] = __( 'Family name required', 'treepress' );
		}
	} else {
		$tp_imports_errors[] = __( 'Family name required', 'treepress' );
	}

	if ( isset( $_FILES['ged'] ) && $_FILES['ged'] && isset( $_FILES['ged']['tmp_name'] ) && $_FILES['ged']['tmp_name'] ) {
		$file = $_FILES['ged']['tmp_name'];

	} else {
		$tp_imports_errors[] = __( 'File required', 'treepress' );
	}

	if ( empty( $tp_imports_errors ) && isset( $family_group_name ) && $family_group_name && isset( $file ) && $file ) {

		$term = term_exists( $family_group_name, 'family' );

		if ( 0 !== $term && null !== $term ) {
			$tp_imports_errors[] = __( 'Exists! Try different family name', 'treepress' );
		} else {

			$family = wp_insert_term(
				$family_group_name,
				'family-group'
			);

			if ( ! is_wp_error( $family ) ) {
				$family_group_id = $family['term_id'];
			} else {
				$tp_imports_errors[] = __( 'Something wrong. Try again.', 'treepress' );
			}
		}

		if ( isset( $family_group_id ) && $family_group_id ) {
			spl_autoload_register(
				function ( $class ) {
					$path_to_php_gedcom = dirname( dirname( __DIR__ ) ) . '/php-gedcom/library/';

					if ( 'PhpGedcom\\' === ! substr( ltrim( $class, '\\' ), 0, 7 ) ) {
						return;
					}

					$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
					if ( file_exists( $path_to_php_gedcom . $class ) ) {
						require_once $path_to_php_gedcom . $class;
					}
				}
			);

			$parser = new \PhpGedcom\Parser();
			$gedcom = $parser->parse( $file );

			foreach ( $gedcom->getIndi() as $indi ) {

				$temp_member = array();

				$temp_member['Id']   = $indi->getId();
				$temp_member['sex']  = $indi->getSex();
				$temp_member['rin']  = $indi->getRin();
				$temp_member['resn'] = $indi->getResn();
				$temp_member['rfn']  = $indi->getRfn();
				$temp_member['afn']  = $indi->getAfn();

				// Names.
				$names = $indi->getName();

				foreach ( $names as $key => $name ) {
					$temp_member['names'][ $key ]['name'] = wp_strip_all_tags( trim( str_replace( array( '/', '\\' ), array( ' ', '' ), $name->getName() ) ) );
					$temp_member['names'][ $key ]['npfx'] = $name->getNpfx();
					$temp_member['names'][ $key ]['givn'] = $name->getGivn();
					$temp_member['names'][ $key ]['nick'] = $name->getNick();
					$temp_member['names'][ $key ]['spfx'] = $name->getSpfx();
					$temp_member['names'][ $key ]['surn'] = $name->getSurn();
					$temp_member['names'][ $key ]['nsfx'] = $name->getNsfx();
				}

				// fams.
				$fams = $indi->getFams();

				foreach ( $fams as $key => $fam ) {
					$temp_member['fams'][ $key ]['fam'] = $fam->getFams();
				}

				// famc.
				$famc = $indi->getFamc();

				foreach ( $famc as $key => $fam ) {
					$temp_member['famc'][ $key ]['fam']  = $fam->getFamc();
					$temp_member['famc'][ $key ]['pedi'] = $fam->getPedi();
				}

				// even.
				$even = $indi->getEven();

				foreach ( $even as $key => $eve ) {
					$typ  = $eve->getType();
					$date = $eve->getDate();
					$plac = $eve->getPlac() ? $eve->getPlac()->getPlac() : '';

					$temp_member['even'][ $key ]['type'] = $typ;
					$temp_member['even'][ $key ]['date'] = $date;
					$temp_member['even'][ $key ]['plac'] = $plac;
				}

				// note.
				$note = $indi->getNote();

				if ( $note ) {
					foreach ( $note as $key => $not ) {
						$ref  = $not->getIsReference();
						$note = $not->getNote();

						$temp_member['note'][ $key ]['ref']  = $ref;
						$temp_member['note'][ $key ]['note'] = $note;
					}
				}

				$args = array(
					'post_type'      => 'member',
					'posts_per_page' => 1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'family-group',
							'field'    => 'id',
							'terms'    => $family_group_id,
						),
					),
					'meta_query'     => array(
						array(
							'key'     => 'Id',
							'value'   => $temp_member['Id'],
							'compare' => '=',
						),
					),
				);

				$query  = new WP_Query( $args );
				$member = ! empty( $query->posts ) ? current( $query->posts ) : null;

				if ( ! $member ) {
					$post_title = wp_strip_all_tags( trim( str_replace( array( '/', '\\' ), array( ' ', '' ), $temp_member['names'][0]['name'] ) ) );
					$my_post    = array(
						'post_title'   => $post_title,
						'post_content' => '',
						'post_status'  => 'publish',
						'post_author'  => 1,
						'post_type'    => 'member',
					);
					$post_id    = wp_insert_post( $my_post );
					wp_set_object_terms( $post_id, $family_group_id, 'family-group' );
					update_post_meta( $post_id, 'Id', $temp_member['Id'] );
				} else {
					$post_id = $member->ID;
				}

				if ( isset( $temp_member['names'] ) && is_array( $temp_member['names'] ) && ! empty( $temp_member['names'] ) ) {
					delete_post_meta( $post_id, 'names' );
					foreach ( $temp_member['names'] as $key => $name ) {
						add_post_meta( $post_id, 'names', $name );
					}
				}

				update_post_meta( $post_id, 'sex', $temp_member['sex'] );

				if ( isset( $temp_member['famc'] ) && is_array( $temp_member['famc'] ) && ! empty( $temp_member['famc'] ) ) {
					delete_post_meta( $post_id, 'famc' );
					foreach ( $temp_member['famc'] as $key => $fac ) {
						add_post_meta( $post_id, 'famc', $fac );
					}
				}


				if ( isset( $temp_member['fams'] ) && is_array( $temp_member['fams'] ) && ! empty( $temp_member['fams'] ) ) {
					delete_post_meta( $post_id, 'fams' );
					foreach ( $temp_member['fams'] as $key => $fas ) {
						add_post_meta( $post_id, 'fams', $fas );
					}
				}

				if ( isset( $temp_member['even'] ) && is_array( $temp_member['even'] ) && ! empty( $temp_member['even'] ) ) {
					delete_post_meta( $post_id, 'even' );
					foreach ( $temp_member['even'] as $key => $ev ) {
						add_post_meta( $post_id, 'even', $ev );
					}
				}

				if ( isset( $temp_member['note'] ) && is_array( $temp_member['note'] ) && ! empty( $temp_member['note'] ) ) {
					delete_post_meta( $post_id, 'note' );
					if ( isset( $temp_member['note'] ) && $temp_member['note'] ) {
						foreach ( $temp_member['note'] as $key => $no ) {
							add_post_meta( $post_id, 'note', $no );
						}
					}
				}
			}

			foreach ( $gedcom->getFam() as $fam ) {

				$temp_fam = array();

				$temp_fam['Id']   = $fam->getId();
				$temp_fam['husb'] = $fam->getHusb();
				$temp_fam['wife'] = $fam->getWife();
				$temp_fam['chil'] = $fam->getChil();

				$even = $fam->getEven();

				foreach ( $even as $key => $eve ) {
					$typ  = $eve->getType();
					$date = $eve->getDate();
					$plac = $eve->getPlac() ? $eve->getPlac()->getPlac() : '';

					$temp_fam['even'][ $key ]['type'] = $typ;
					$temp_fam['even'][ $key ]['date'] = $date;
					$temp_fam['even'][ $key ]['plac'] = $plac;
				}

				$note = $fam->getNote();

				if ( $note ) {
					foreach ( $note as $key => $not ) {
						$ref  = $not->getIsReference();
						$note = $not->getNote();

						$temp_fam['note'][ $key ]['ref']  = $ref;
						$temp_fam['note'][ $key ]['note'] = $note;
					}
				}

				$args = array(
					'post_type'      => 'family',
					'posts_per_page' => 1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'family-group',
							'field'    => 'id',
							'terms'    => $family_group_id,
						),
					),
					'meta_query'     => array(
						array(
							'key'     => 'Id',
							'value'   => $temp_fam['Id'],
							'compare' => '=',
						),
					),
				);

				$query  = new WP_Query( $args );
				$family = ! empty( $query->posts ) ? current( $query->posts ) : null;

				if ( ! $family ) {
					$post_title = $temp_fam['Id'];
					$my_post    = array(
						'post_title'   => $post_title,
						'post_content' => '',
						'post_status'  => 'publish',
						'post_author'  => 1,
						'post_type'    => 'family',
					);
					$family_id  = wp_insert_post( $my_post );
					update_post_meta( $family_id, 'Id', $temp_fam['Id'] );
					wp_set_object_terms( $family_id, $family_group_id, 'family-group' );

				} else {
					$family_id = $family->ID;
				}

				update_post_meta( $family_id, 'husb', $temp_fam['husb'] );
				update_post_meta( $family_id, 'wife', $temp_fam['wife'] );

				$args = array(
					'post_type'      => 'member',
					'posts_per_page' => -1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'family-group',
							'field'    => 'id',
							'terms'    => $family_group_id,
						),
					),
					'meta_query'     => array(
						'relation' => 'OR',
						array(
							'key'     => 'Id',
							'value'   => $temp_fam['husb'],
							'compare' => '=',
						),
						array(
							'key'     => 'Id',
							'value'   => $temp_fam['wife'],
							'compare' => '=',
						),
					),
				);

				$query   = new WP_Query( $args );
				$members = $query->posts;

				if ( $members && is_array( $members ) && ! empty( $members ) ) {
					foreach ( $members as $key => $mem ) {
						$fams = get_post_meta( $mem->ID, 'fams' ) ? get_post_meta( $mem->ID, 'fams' ) : array();
						foreach ( $fams as $key => $value ) {
							if ( $value['fam'] ) {
								$fams[] = $value['fam'];
								unset( $fams[ $key ] );
							}
						}
						if ( ! in_array( $temp_fam['Id'], $fams ) ) {
							add_post_meta( $mem->ID, 'fams', array( 'fam' => $temp_fam['Id'] ) );
						}
					}
				}

				delete_post_meta( $family_id, 'chil' );

				if ( isset( $temp_fam['chil'] ) && $temp_fam['chil'] ) {
					foreach ( $temp_fam['chil'] as $key => $chi ) {
						add_post_meta( $family_id, 'chil', $chi );
						$args = array(
							'post_type'      => 'member',
							'posts_per_page' => 1,
							'tax_query'      => array(
								array(
									'taxonomy' => 'family-group',
									'field'    => 'id',
									'terms'    => $family_group_id,
								),
							),
							'meta_query'     => array(
								array(
									'key'     => 'Id',
									'value'   => $chi,
									'compare' => '=',
								),
							),
						);

						$query  = new WP_Query( $args );
						$chills = $query->posts;

						if ( $chills && is_array( $chills ) && ! empty( $chills ) ) {
							foreach ( $chills as $key => $chill ) {
								$famc = get_post_meta( $chill->ID, 'famc' ) ? get_post_meta( $chill->ID, 'famc' ) : array();
								foreach ( $famc as $key => $value ) {
									if ( $value['fam'] ) {
										$famc[] = $value['fam'];
										unset( $famc[ $key ] );
									}
								}
								if ( ! in_array( $temp_fam['Id'], $famc ) ) {
									add_post_meta( $chill->ID, 'famc', array( 'fam' => $temp_fam['Id'] ) );
								}
							}
						}
					}
				}

				if ( isset( $temp_fam['even'] ) && is_array( $temp_fam['even'] ) && ! empty( $temp_fam['even'] ) ) {
					delete_post_meta( $family_id, 'even' );
					foreach ( $temp_fam['even'] as $key => $ev ) {
						add_post_meta( $family_id, 'even', $ev );
					}
				}

				if ( isset( $temp_fam['note'] ) && is_array( $temp_fam['note'] ) && ! empty( $temp_fam['note'] ) ) {
					delete_post_meta( $family_id, 'note' );
					foreach ( $temp_fam['note'] as $key => $no ) {
						add_post_meta( $family_id, 'note', $no );
					}
				}
			}
		}
	}
}
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'TreePress Import', 'treepress' ); ?>
	</h2>
	<?php if ( ! empty( $tp_imports_errors ) ) { ?>
	<div class="notice notice-error is-dismissible">
		<?php foreach ( $tp_imports_errors as $key => $err ) { ?>
		<p><?php esc_html( $err ); ?> </p>
		<?php } ?>
	</div>
	<?php } ?>
	<p>
		<?php esc_html_e( 'Here you can import a Gedcom (.ged) file which is the standard format for importing and exporting family tree data.', 'treepress' ); ?>
	</p>
	<h3>
		<?php esc_html_e( 'A Note on Dates:', 'treepress' ); ?>
	</h3>
	<p>
		<?php esc_html_e( 'Please note that TreePress requires exact dates so if your family tree data exports with approximate date options (such as \'Before\', \'After\', \'Between\' etc) these dates will not import.  Dates must contain day, month and year (although the format does not matter yyyy-mm-dd or dd-mm-yyyy will import).  You cannot import just a year or month-year.', 'treepress' ); ?>
	</p>
	<p>
		<?php esc_html_e( 'If your tree data does contain people without a full birth date, this will mean you need to use a slightly different shortcode to display a tree - see the \'Shortcodes\' tab in the Options page for a full explanation.', 'treepress' ); ?>
	</p>
	<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'treepress_import_nonce_action', 'treepress_import_nonce' ); ?>
		<table>
			<tr>
				<td><?php esc_html_e( 'Family Name', 'treepress' ); ?></td>
				<td>
					<input type="text" name="family_group_name">
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Select file (.ged)', 'treepress' ); ?></td>
				<td>
					<input type="file" name="ged">
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="import" value="<?php esc_attr_e( 'Import', 'treepress' ); ?>"></td>
			</tr>
		</table>
	</form>

	<?php
	if ( isset( $family_group_id ) && isset( $family_group_name ) ) {
		echo '<p>' . esc_html__( 'Imported successfully', 'treepress' ) . '<p>';
		$my_post = array(
			'post_title'   => wp_strip_all_tags( 'Family Tree - ' . $family_group_name ),
			'post_content' => '[family-tree family=\'' . $family_group_id . '\']',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_type'    => 'page',
		);

		$family_tree_link_id = wp_insert_post( $my_post );
		echo '<p>' . esc_html__( 'Family Tree', 'treepress' ) . ' <a href="' . esc_attr( get_permalink( $family_tree_link_id ) ) . '">' . esc_html__( 'View Page', 'treepress' ) . '</a><p>';
		update_term_meta( $family_group_id, 'family_tree_link', get_permalink( $family_tree_link_id ) );
		$my_post = array(
			'post_title'   => wp_strip_all_tags( 'Family Members - ' . $family_group_name ),
			'post_content' => '[family-members family=\'' . $family_group_id . '\']',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_type'    => 'page',
		);

		$family_members_link_id = wp_insert_post( $my_post );
		echo '<p>' . esc_html__( 'Family Members', 'treepress' );
		echo '<a href="' . esc_attr( get_permalink( $family_members_link_id ) ) . '">';
		echo esc_html__( 'View Page', 'treepress' );
		echo '</a><p>';
		update_term_meta( $family_group_id, 'family_members_link', get_permalink( $family_members_link_id ) );
	}
	?>
</div>
