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

	$default_chart = $this->default_tree_meta();
	$saved_chart   = get_post_meta( $post->ID, 'chart', true ) ? get_post_meta( $post->ID, 'chart', true ) : array();
	$default       = empty( $saved_chart ) ? true : false;
	$chart         = $this->merge_chart( $default_chart, $saved_chart, $default );

	$show_living_dates    = (string) isset( $chart['privacy']['show_living_dates'] ) ? $chart['privacy']['show_living_dates'] : '';
	$show_spouse          = (string) isset( $chart['privacy']['show_spouse'] ) ? $chart['privacy']['show_spouse'] : '';
	$show_only_one_spouse = (string) isset( $chart['privacy']['show_only_one_spouse'] ) ? $chart['privacy']['show_only_one_spouse'] : '';
	$show_gender          = (string) isset( $chart['privacy']['show_gender'] ) ? $chart['privacy']['show_gender'] : '';
	$align                = (string) isset( $chart['align'] ) ? $chart['align'] : '';

	$node_opacity       = $chart['node_opacity'];
	$node_minimum_width = $chart['node_minimum_width'];
	$height_generations = $chart['height_generations'];
	$chart_type         = $chart['chart_type'];
	$group_id           = $chart['group_id'];
	$root_id            = $chart['root_id'];

	$bg_color = $chart['bg_color'];

	$image_bg_show   = isset( $chart['image_bg']['show'] ) ? $chart['image_bg']['show'] : '';
	$image_bg_size   = isset( $chart['image_bg']['size'] ) ? $chart['image_bg']['size'] : '';
	$image_bg_repeat = isset( $chart['image_bg']['repeat'] ) ? $chart['image_bg']['repeat'] : '';

	$box_show = $chart['box']['show'];

	$box_border_style  = $chart['box']['border']['style'];
	$box_border_weight = $chart['box']['border']['weight'];
	$box_border_radius = $chart['box']['border']['radius'];

	$box_border_color_male   = $chart['box']['border']['color']['male'];
	$box_border_color_female = $chart['box']['border']['color']['female'];
	$box_border_color_other  = $chart['box']['border']['color']['other'];

	$box_bg_color_male   = $chart['box']['bg_color']['male'];
	$box_bg_color_female = $chart['box']['bg_color']['female'];
	$box_bg_color_other  = $chart['box']['bg_color']['other'];

	$box_enable_toolbar   = $chart['box']['enable_toolbar'];
	$box_single_page_link = $chart['box']['single_page_link'];
	$box_tree_nav_link    = $chart['box']['tree_nav_link'];
	$box_padding          = $chart['box']['padding'];

	$line_show   = $chart['line']['show'];
	$line_style  = $chart['line']['style'];
	$line_weight = $chart['line']['weight'];
	$line_color  = $chart['line']['color'];

	$thumb_show          = $chart['thumb']['show'];
	$thumb_border_style  = $chart['thumb']['border_style'];
	$thumb_border_weight = $chart['thumb']['border_weight'];
	$thumb_border_radius = $chart['thumb']['border_radius'];
	$thumb_margin        = $chart['thumb']['margin'];

	$name_font       = $chart['name']['font'];
	$name_font_size  = $chart['name']['font_size'];
	$name_font_color = $chart['name']['font_color'];

	$name_font_other       = $chart['name']['font_other'];
	$name_font_other_size  = $chart['name']['font_other_size'];
	$name_font_other_color = $chart['name']['font_other_color'];

	$name_format = $chart['name_format'];
	$dob_format  = $chart['dob_format'];
	$dod_format  = $chart['dod_format'];

	$wrap_names        = $chart['wrap_names'];
	$date_birth_prefix = $chart['date_birth_prefix'];
	$death_date_prefix = $chart['death_date_prefix'];

	$terms = get_terms(
		array(
			'taxonomy'   => 'family-group',
			'hide_empty' => false,
		)
	);

	$args = array(
		'numberposts' => -1,
		'post_type'   => 'member',
	);

	$members = get_posts( $args );

	$bd_style = array(
		'dotted',
		'dashed',
		'solid',
		'double',
		'groove',
		'ridge',
		'inset',
		'outset',
		'none',
		'hidden',
	);
	?>

	<?php wp_nonce_field( 'nonce_chart', '_nonce_chart' ); ?>

	<table class="table" border="0" width="100%">
		<tbody>
			<!-- <tr>
				<th colspan="2" align="left">
					<h3><strong><?php echo esc_html__( 'Privacy', 'treepress' ); ?></strong></h3>
				</th>
			</tr> -->
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Chart Type', 'treepress' ); ?>
				</th>
				<td>
					<select name="chart[chart_type]">
						<optgroup label="Style 1">
							<option <?php selected( $chart_type, 11 ); ?> value="11"> A - Individual Descendents Vertical <!-- Style 1 ( A ) Vertical Descendant (Spouses next to each other) --> </option>
							<option <?php selected( $chart_type, 12 ); ?> value="12"> B - Individual Pedigree Vertical <!-- Style 1 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors)--></option>
						<?php if ( class_exists( 'Treepress_Charts' ) ) { ?>
							<option <?php selected( $chart_type, 13 ); ?> value="13"> C - Couple Ancestors Hourglass Vertical <!-- Style 1 ( C ) Vertical (Spouses next to each other) Hourglass (Each Spouse Ancestors) --></option>
							<option <?php selected( $chart_type, 114 ); ?> value="14"> D - Individual\'s Family Hourglass Vertical <!-- Style 1 ( D ) Vertical (Spouses next to each other) Hourglass 2 (Person + Spouse Ancestors & Descendants) --></option>
						</optgroup>
						<optgroup label="Style 2">
							<option <?php selected( $chart_type, 21 ); ?>  value="21"> A - Individual Descendents Vertical ♥ <!-- Style 2 ( A ) Vertical Descendant (Inline spouse indicated by ♡) (addon) --></option>
						</optgroup>
						<optgroup label="Style 3">
							<option <?php selected( $chart_type, 31 ); ?>  value="31"> A - Individual Descendent Vertical Text <!-- Style 3 ( A ) Vertical Descendant (Spouses next to each other)  --></option>
							<option <?php selected( $chart_type, 32 ); ?>  value="32"> B - Individual Pedigree Vertical Text <!-- Style 3 ( B ) Vertical Pedigree (Spouses next to each other) (Shows Ancestors)  --></option>
							<option <?php selected( $chart_type, 33 ); ?>  value="33"> C - Couple Ancestors Hourglass Vertical Text <!-- Style 3 ( C ) Vertical Hourglass (Spouses next to each other) (Each Spouse Ancestors)  --></option>
							<option <?php selected( $chart_type, 34 ); ?>  value="34"> D - Individual\'s Family Hourglass Text <!-- Style 3 ( D ) Vertical Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants)  --></option>
						</optgroup>
						<optgroup label="Style 4">
							<option <?php selected( $chart_type, 41 ); ?>  value="41"> A - Individual Descendent Horizontal <!-- Style 4 ( A ) Horizontal Descendant (Spouses next to each other)  --></option>
							<option <?php selected( $chart_type, 42 ); ?>  value="42"> B - Individual Pedigree Horizontal <!-- Style 4 ( B ) Horizontal Pedigree (Spouses next to each other) (Shows Ancestors) (addon) --></option>
							<option <?php selected( $chart_type, 43 ); ?>  value="43"> C - Couple Ancestors Hourglass Horizontal <!-- Style 4 ( C ) Horizontal Hourglass (Spouses next to each other) (Each Spouse Ancestors) (addon) --></option>
							<option <?php selected( $chart_type, 44 ); ?>  value="44"> D - Individual\'s Family Hourglass Horizontal <!-- Style 4 ( D ) Horizontal Hourglass 2 (Spouses next to each other) (Person + Spouse Ancestors & Descendants) --></option>
						</optgroup>
						<optgroup label="Style 5">
							<option <?php selected( $chart_type, 51 ); ?>  value="51"> A - Individual Descendent Horizontal ♥ <!-- Style 5 ( A ) Horizontal Descendant (Inline spouse indicated by ♡) (addon) --></option>
						</optgroup>
						<optgroup label="Style 6">
							<option <?php selected( $chart_type, 61 ); ?>  value="61"> A - Individual Descendent Horizontal Text <!-- Style 6 ( A ) Horizontal Text Descendant (Compact)  --></option>
							<option <?php selected( $chart_type, 62 ); ?>  value="62"> B - Individual Pedigree Horizontal Text <!-- Style 6 ( B ) Horizontal Text Pedigree (Compact) (Shows Ancestors) (addon) --></option>
							<option <?php selected( $chart_type, 63 ); ?>  value="63"> C - Couple Ancestors Hourglass Horizontal Text <!-- Style 6 ( C ) Horizontal Text Hourglass (Compact) (Each Spouse Ancestors)  --></option>
							<option <?php selected( $chart_type, 64 ); ?>  value="64"> D - Individual\'s Family Hourglass Horizontal Text <!-- Style 6 ( D ) Horizontal Text Hourglass 2 (Compact) (Person + Spouse Ancestors & Descendants)  --></option>
						<?php } ?>
						</optgroup>
					</select>
				</td>
			</tr>
			<tr>
				<th width="150" align="left"><?php echo esc_html__( 'Family Group', 'treepress' ); ?></th>
				<td>
					<select name="chart[group_id]">
						<?php
						if ( $terms ) {
							foreach ( $terms as $key => $ter ) {
								?>
							<option <?php selected( $group_id, $ter->term_id ); ?> value="<?php echo esc_attr( $ter->term_id ); ?>">
								<?php echo esc_html( $ter->term_id ); ?> - <?php echo esc_html( $ter->name ); ?>
							</option>
								<?php
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th width="150" align="left"><?php echo esc_html__( 'Root Person', 'treepress' ); ?></th>
				<td>
					<select class="tp-select2" name="chart[root_id]">
						<?php
						if ( $members ) {
							foreach ( $members as $key => $member ) {
								?>
							<option <?php selected( $root_id, $member->ID ); ?> value="<?php echo esc_attr( $member->ID ); ?>">
								<?php $this->short_details_name( $member->ID ); ?>
							</option>
								<?php
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Show Living Dates', 'treepress' ); ?>
				</th>
				<td align="left">
					<input type="checkbox" name="chart[privacy][show_living_dates]" <?php echo ( 'on' === $show_living_dates ? 'checked' : '' ); ?>>
				</td>
			</tr>
			<tr>
				<th align="left">
					<?php echo esc_html__( 'Show Spouse', 'treepress' ); ?>
				</th>
				<td align="left">
					<input type="checkbox" name="chart[privacy][show_spouse]" <?php echo ( 'on' === $show_spouse ? 'checked' : '' ); ?>>
				</td>
			</tr>
			<tr>
				<th align="left">
					<?php echo esc_html__( 'Show Only One Spouse', 'treepress' ); ?>
				</th>
				<td align="left">
					<input type="checkbox" name="chart[privacy][show_only_one_spouse]" <?php echo ( 'on' === $show_only_one_spouse ? 'checked' : '' ); ?>> 
					<small><?php echo esc_html__( 'Requered Checked "Show Spouse"', 'treepress' ); ?></small>
				</td>
			</tr>

			<tr>
				<th align="left"><?php echo esc_html__( 'Show Gender', 'treepress' ); ?></th>
				<td align="left">
					<input type="checkbox" name="chart[privacy][show_gender]" <?php echo ( 'on' === $show_gender ? 'checked' : '' ); ?>>
				</td>
			</tr>
			<tr>
				<th align="left"><?php echo esc_html__( 'Left Align', 'treepress' ); ?></th>
				<td align="left">
					<input type="checkbox" name="chart[align]" <?php echo ( 'on' === $align ? 'checked' : '' ); ?>
				</td>
			</tr>
		<tbody>
	</table>

	<br>

	<table class="table" border="0" width="100%">
		<tbody>
			<tr>
				<td colspan="2" class="higlighted">
					<h3><strong><?php echo esc_html__( 'Chart Background', 'treepress' ); ?></strong></h3>
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Background Color', 'treepress' ); ?>
				</th>
				<td>
					<label>
						<input type="text" value="<?php echo esc_attr( $bg_color ); ?>" name="chart[bg_color]" class="treechart-color-field">
					</label>
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Background Image', 'treepress' ); ?>
				</th>
				<td>
					<label>
						<input <?php checked( $image_bg_show, 'on' ); ?> type="checkbox" name="chart[image_bg][show]">
						<?php echo esc_html__( 'Show featured Image as Background', 'treepress' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Background Size', 'treepress' ); ?>
				</th>
				<td>
					<label>
						<input <?php checked( $image_bg_size, 'contain' ); ?> type="radio" value="contain" name="chart[image_bg][size]">
						<?php echo esc_html__( 'Tile', 'treepress' ); ?>
					</label>
					<label>
						<input <?php checked( $image_bg_size, 'cover' ); ?> type="radio" value="cover" name="chart[image_bg][size]">
						<?php echo esc_html__( 'Stretch to fit ', 'treepress' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Background Repeat', 'treepress' ); ?>
				</th>
				<td>
					<label>
						<input <?php checked( $image_bg_repeat, 'on' ); ?> type="checkbox" name="chart[image_bg][repeat]">
						<?php echo esc_html__( 'Repeat ', 'treepress' ); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>

	<br>

	<table class="table" border="0" width="100%">
		<tbody>
			<tr>
				<td colspan="2" class="higlighted">
					<h3><strong><?php echo esc_html__( 'Family Member Box', 'treepress' ); ?></strong></h3>
				</td>
			</tr>
			<tr>
				<th align="left">
					<?php echo esc_html__( 'Show Content in a Box ', 'treepress' ); ?>
				</th>
				<td>
					<input <?php checked( $box_show, 'on' ); ?> type="checkbox" name="chart[box][show]">
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Box Opacity (0.0 to 1.0)', 'treepress' ); ?>
				</th>
				<td>
					<input type="text" value="<?php echo esc_attr( $node_opacity ); ?>"  name="chart[node_opacity]">
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Box Minimum Width (Pixels)', 'treepress' ); ?> <small>ex. 250px</small>
				</th>
				<td>
					<input type="text" value="<?php echo esc_attr( $node_minimum_width ); ?>" name="chart[node_minimum_width]">
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
					<?php echo esc_html__( 'Height of Generations (Pixels)', 'treepress' ); ?>
				</th>
				<td>
					<input type="text"  value="<?php echo esc_attr( $height_generations ); ?>"  name="chart[height_generations]">
				</td>
			</tr>
			<tr>
				<th width="150" align="left">
				<?php echo esc_html__( 'Border Style', 'treepress' ); ?>
				</th>
				<td>
					<select name="chart[box][border][style]">
						<?php foreach ( $bd_style as $key => $value ) { ?>
						<option <?php selected( $box_border_style, $value ); ?> value="<?php echo esc_attr( $value ); ?>">
							<?php echo esc_html( ucfirst( $value ) ); ?>
						</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th align="left">
					<?php echo esc_html__( 'Border Weight', 'treepress' ); ?>
				</th>
				<td>
					<select name="chart[box][border][weight]">
						<?php for ( $i = 1; $i < 20; $i++ ) { ?>
						<option <?php selected( $box_border_weight, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
							<?php echo esc_html( $i ) . 'px'; ?>
						</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th align="left">
				<?php echo esc_html__( 'Corner Radius', 'treepress' ); ?>
				</th>
				<td>
					<select name="chart[box][border][radius]">
						<?php for ( $i = 1; $i < 20; $i++ ) { ?>
						<option <?php selected( $box_border_radius, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
							<?php echo esc_html( $i ) . 'px'; ?>
						</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th align="left" valign="top">
				<?php echo esc_html__( 'Gender Specific Elements', 'treepress' ); ?>
				</th>
				<td>
					<table>
						<thead>
							<tr>
								<th>
								</th>
								<th>
								<?php echo esc_html__( 'Male', 'treepress' ); ?>
								</th>
								<th>
								<?php echo esc_html__( 'Female', 'treepress' ); ?>
								</th>
								<th>
								<?php echo esc_html__( 'Other', 'treepress' ); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
								<?php echo esc_html__( 'Box Color', 'treepress' ); ?>
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_bg_color_male ); ?>" name="chart[box][bg_color][male]" class="treechart-color-field">
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_bg_color_female ); ?>" name="chart[box][bg_color][female]" class="treechart-color-field">
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_bg_color_other ); ?>" name="chart[box][bg_color][other]" class="treechart-color-field">
								</td>
							</tr>
							<tr>
								<td>
								<?php echo esc_html__( 'Border Color', 'treepress' ); ?>
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_border_color_male ); ?>" name="chart[box][border][color][male]" class="treechart-color-field">
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_border_color_female ); ?>" name="chart[box][border][color][female]" class="treechart-color-field">
								</td>
								<td>
									<input type="text" value="<?php echo esc_attr( $box_border_color_other ); ?>" name="chart[box][border][color][other]" class="treechart-color-field">
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<th align="left">
				<?php echo esc_html__( 'Enable Toolbar', 'treepress' ); ?>
				</th>
				<td>
					<input <?php checked( $box_enable_toolbar, 'on' ); ?> type="checkbox" name="chart[box][enable_toolbar]">
				</td>
			</tr>
			<tr>
				<th align="left">
				<?php echo esc_html__( 'Enable Single Page Link', 'treepress' ); ?>
				</th>
				<td>
					<input <?php checked( $box_single_page_link, 'on' ); ?> type="checkbox"  name="chart[box][single_page_link]">
				</td>
			</tr>
			<tr>
				<th align="left">
				<?php echo esc_html__( 'Enable Tree Nav Link', 'treepress' ); ?>
				</th>
				<td>
					<input <?php checked( $box_tree_nav_link, 'on' ); ?> type="checkbox"  name="chart[box][tree_nav_link]">
				</td>
			</tr>
			<tr>
				<th align="left">
				<?php echo esc_html__( 'Padding', 'treepress' ); ?>
				</th>
				<td>
				<select name="chart[box][padding]">
					<?php for ( $i = 1; $i < 20; $i++ ) { ?>
					<option <?php selected( $box_padding, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ) . 'px'; ?>
					</option>
					<?php } ?>
				</select>
				</td>
			</tr>
		</tbody>
	</table>

	<br>

	<table class="table" border="0" width="100%">
		<tbody>
			<tr>
				<td colspan="2" class="higlighted">
					<h3><strong><?php echo esc_html__( 'Connecting Lines', 'treepress' ); ?></strong></h3>
				</td>
			</tr>
			<tr>
			<td colspan="2">
				<label>
					<input <?php checked( $line_show, 'on' ); ?> type="checkbox" name="chart[line][show]"> 
					<?php echo esc_html__( 'Connect Boxes with Lines ', 'treepress' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Line Style', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[line][style]">
					<?php foreach ( $bd_style as $key => $value ) { ?>
					<option <?php selected( $line_style, $value ); ?> value="<?php echo esc_attr( $value ); ?>">
						<?php echo esc_html( ucfirst( $value ) ); ?>
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Line Weight', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[line][weight]">
					<?php for ( $i = 1; $i < 20; $i++ ) { ?>
					<option <?php selected( $line_weight, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Line Color', 'treepress' ); ?>
			</th>
			<td>
				<input value="<?php echo esc_attr( $line_color ); ?>" type="text" name="chart[line][color]" class="treechart-color-field">
			</td>
			</tr>
		</tbody>
	</table>

<br>

<table class="table" border="0" width="100%">
	<tbody>
		<tr>
			<td colspan="2" class="higlighted">
				<h3><strong><?php echo esc_html__( 'Picture', 'treepress' ); ?></strong></h3>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<label>
					<input <?php checked( $thumb_show, 'on' ); ?> type="checkbox" name="chart[thumb][show]">
					<?php echo esc_html__( 'Show Picture in a Box ', 'treepress' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Border Style', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[thumb][border_style]">
					<?php foreach ( $bd_style as $key => $value ) { ?>
					<option <?php selected( $thumb_border_style, $value ); ?> value="<?php echo esc_attr( $value ); ?>">
						<?php echo esc_html( ucfirst( $value ) ); ?>
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Border Weight', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[thumb][border_weight]">
					<?php for ( $i = 1; $i < 20; $i++ ) { ?>
					<option <?php selected( $thumb_border_weight, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Corner Radius', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[thumb][border_radius]">
					<?php for ( $i = 1; $i < 20; $i++ ) { ?>
					<option <?php selected( $thumb_border_radius, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Margin', 'treepress' ); ?>
			</th>
			<td>
				<select name="chart[thumb][margin]">
					<?php for ( $i = 1; $i < 20; $i++ ) { ?>
					<option <?php selected( $thumb_margin, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
						<?php echo esc_html( $i ); ?>px
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<br>

<table class="table" border="0" width="100%">
	<tbody>
		<tr>
			<td colspan="2" class="higlighted">
				<h3><strong><?php echo esc_html__( 'Text Options', 'treepress' ); ?></strong></h3>
			</td>
		</tr>

		<tr>
			<td style="padding: 0px;" width="400">
				<table  class="table" border="0" width="400">
					<tbody>
						<tr>
							<th width="150" align="left">
								<?php echo esc_html__( 'Name Font', 'treepress' ); ?>
							</th>
							<td>
								<select name="chart[name][font]">
									<option <?php selected( $name_font, 'Arial' ); ?> value="Arial">Arial</option>
								</select>
							</td>
						</tr>
						<tr>
							<th align="left">
								<?php echo esc_html__( 'Name Font Size', 'treepress' ); ?>
							</th>
							<td>
								<select name="chart[name][font_size]">
									<?php for ( $i = 10; $i < 25; $i++ ) { ?>
										<option <?php selected( $name_font_size, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
											<?php echo esc_html( $i ); ?>px
										</option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th align="left">
								<?php echo esc_html__( 'Name Font Color', 'treepress' ); ?>
							</th>
							<td>
								<input value="<?php echo esc_attr( $name_font_color ); ?>"  type="text" name="chart[name][font_color]" class="treechart-color-field">
							</td>
						</tr>
					</tbody>
				</table>
			</td>
			<td style="padding: 0px;">
				<table  class="table" border="0" width="400">
					<tbody>
						<tr>
							<th width="150" align="left">
								<?php echo esc_html__( 'Other Text Font', 'treepress' ); ?>
							</th>
							<td>
								<select name="chart[name][font_other]">
									<option <?php selected( $name_font_other, 'Arial' ); ?> value="Arial">Arial</option>
								</select>
							</td>
						</tr>
						<tr>
							<th align="left">
								<?php echo esc_html__( 'Other Text Font Size', 'treepress' ); ?>
							</th>
							<td>
								<select name="chart[name][font_other_size]">
									<?php for ( $i = 8; $i < 20; $i++ ) { ?>
									<option <?php selected( $name_font_other_size, $i . 'px' ); ?> value="<?php echo esc_attr( $i ); ?>px">
										<?php echo esc_html( $i ); ?>px
									</option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th align="left">
								<?php echo esc_html__( 'Other Text Font Color', 'treepress' ); ?>
							</th>
							<td>
								<input value="<?php echo esc_attr( $name_font_other_color ); ?>" type="text" name="chart[name][font_other_color]" class="treechart-color-field">
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

<br>

<table class="table" border="0" width="100%">
	<tbody>
		<tr>
			<td colspan="3" class="higlighted">
				<h3><strong><?php echo esc_html__( 'Text to Display', 'treepress' ); ?></strong></h3>
			</td>
		</tr>
		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Name Format', 'treepress' ); ?>
			</th>
			<td width="150">
				<label>
					<input <?php checked( $name_format, 'full' ); ?> type="radio"  value="full" name="chart[name_format]">
					<?php echo esc_html__( 'Full Name', 'treepress' ); ?>
				</label>
			</td>
			<td>
				<label>
					<input <?php checked( $name_format, 'first' ); ?> type="radio" value="first" name="chart[name_format]">
					<?php echo esc_html__( 'First Name Only', 'treepress' ); ?>
				</label>
			</td>
		</tr>
		<!--
		// <tr>';
		// <th width="150" align="left">';
		// echo 'Show gender';
		// </th>';
		// <td width="150">';
		// <label><input type="checkbox" '.($show_gender=='on' ? 'checked' : '') .'  name="chart[show_gender]"></label>';
		// </td>';
		// </tr>
		-->
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Date of Birth', 'treepress' ); ?>
			</th>
			<td>
				<label>
					<input <?php checked( $dob_format, 'full' ); ?> type="radio" value="full" name="chart[dob_format]">
					<?php echo esc_html__( ' Full Date', 'treepress' ); ?>
				</label>
			</td>
			<td>
				<label>
					<input <?php checked( $dob_format, 'year' ); ?> type="radio" value="year" name="chart[dob_format]">
					<?php echo esc_html__( 'Year Only', 'treepress' ); ?>
				</label>
			</td>
		</tr>
		<tr>
			<th align="left">
				<?php echo esc_html__( 'Date of Death', 'treepress' ); ?>
			</th>
			<td>
				<label>
					<input <?php checked( $dod_format, 'full' ); ?> type="radio" value="full" name="chart[dod_format]">
					<?php echo esc_html__( 'Full Date', 'treepress' ); ?>
				</label>
			</td>
			<td>
				<label>
					<input <?php checked( $dod_format, 'year' ); ?> type="radio" value="year" name="chart[dod_format]">
					<?php echo esc_html__( 'Year Only', 'treepress' ); ?>
				</label>
			</td>
		</tr>
		<!--
		// <tr>
		// <th align="left">
		// echo 'Date of Marriage';
		// </th>
		// <td>
		// <label><input type="radio" '.($dom_format=='full' ? 'checked' : '') .' value="full" name="chart[dom_format]"> Full Date</label>';
		// </td>
		// <td>
		// <label><input type="radio" '.($dom_format=='year' ? 'checked' : '') .' value="year" name="chart[dom_format]">Year Only</label>';
		// </td>
		// </tr>
		-->
		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Wrap Names', 'treepress' ); ?>
			</th>
			<td align="left">
				<label>
					<input <?php checked( $wrap_names, 'on' ); ?> type="checkbox" name="chart[wrap_names]">
				</label>
			</td>
		</tr>

		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Date Birth Prefix', 'treepress' ); ?>
			</th>
			<td align="left">
				<input value="<?php echo esc_attr( $date_birth_prefix ); ?>" type="text" name="chart[date_birth_prefix]">
			</td>
		</tr>

		<tr>
			<th width="150" align="left">
				<?php echo esc_html__( 'Death Date Prefix', 'treepress' ); ?>
			</th>
			<td align="left">
				<input value="<?php echo esc_attr( $death_date_prefix ); ?>" type="text" name="chart[death_date_prefix]">
			</td>
		</tr>
		<?php if ( defined( 'TP_DEV' ) && TP_DEV ) { ?>
		<tr>
			<th colspan="2" align="left">
				<pre>
					<?php print_r( get_post_meta( $post->ID, 'chart' ) ); ?>
				</pre>
			</th>
		</tr>
		<?php } ?>
		</tbody>
	</table>
