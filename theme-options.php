<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'gowiththeflow_options', 'gowiththeflow_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'gowiththeflowtheme' ), __( 'Theme Options', 'gowiththeflowtheme' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create arrays for our select and radio options
 */
/*$select_options = array(
	'0' => array(
		'value' =>	'0',
		'label' => __( 'Zero', 'gowiththeflowtheme' )
	),
	'1' => array(
		'value' =>	'1',
		'label' => __( 'One', 'gowiththeflowtheme' )
	),
	'2' => array(
		'value' => '2',
		'label' => __( 'Two', 'gowiththeflowtheme' )
	),
	'3' => array(
		'value' => '3',
		'label' => __( 'Three', 'gowiththeflowtheme' )
	),
	'4' => array(
		'value' => '4',
		'label' => __( 'Four', 'gowiththeflowtheme' )
	),
	'5' => array(
		'value' => '3',
		'label' => __( 'Five', 'gowiththeflowtheme' )
	)
);*/

$radio_options = array(
	'display' => array(
		'all' => array(
			'value' => 'all',
			'label' => __( 'All the articles', 'gowiththeflowtheme' ),
			'default' => 'all'
		),
		'category' => array(
			'value' => 'category',
			'label' => __( 'Only the articles in the category...', 'gowiththeflowtheme' ),
			'click' => 'document.getElementById(\'display_categories\').style.display = \'block\';'//'$(\'#display_categories\').show();'
		)
	),
	'posts_order' => array(
		'by_date' => array(
			'value' => 'by_date',
			'label' => __( 'By date of publication', 'gowiththeflowtheme' ),
			'default' => 'by_date'
		),
		'shuffled' => array(
			'value' => 'shuffled',
			'label' => __( 'Shuffled', 'gowiththeflowtheme' )
		)
	)
);

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'gowiththeflowtheme' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'gowiththeflowtheme' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'gowiththeflow_options' ); ?>
			<?php $options = get_option( 'gowiththeflow_theme_options' ); ?>

			<table class="form-table">

				<tr valign="top"><th scope="row"><?php _e( 'Posts to display', 'gowiththeflowtheme' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Posts to display', 'gowiththeflowtheme' ); ?></span></legend>
						<?php
						
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $radio_options['display'] as $option ) {
								$radio_setting = $options['display'];

								if ( '' != $radio_setting ) {
									if ( $options['display'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								} elseif ($option['default'] == $option['value']) {
									$checked = "checked=\"checked\"";
								} else {
									$checked = '';
								}
								?>
								<label class="description"<?php if ($option['click']) echo ' onClick="'.$option['click'].'"'; ?>><input type="radio" name="gowiththeflow_theme_options[display]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label><br />
								<?php
							}
						?>
							<p id="display_categories" style="<?php if ($options['display'] != 'category') echo 'display:none;'; ?>color:#666;">
								Catégorie: <select name="gowiththeflow_theme_options[choosen_category]">
									<?php
									$categories = get_categories();
									foreach ($categories as $category): ?>
									<option <?php if ($options['choosen_category'] == $category->term_id) echo 'selected="selected" '; ?>value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
								<?php endforeach; ?>
								</select>
							</p>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Posts order', 'gowiththeflowtheme' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Posts order', 'gowiththeflowtheme' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $radio_options['posts_order'] as $option ) {
								$radio_setting = $options['posts_order'];

								if ( '' != $radio_setting ) {
									if ( $options['posts_order'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								} elseif ($option['default'] == $option['value']) {
									$checked = "checked=\"checked\"";
								} else {
									$checked = '';
								}
								?>
								<label class="description"><input type="radio" name="gowiththeflow_theme_options[posts_order]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label><br />
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Last message', 'gowiththeflowtheme' ); ?></th>
					<td>
						<textarea id="gowiththeflow_theme_options[last_message]" class="large-text" cols="50" rows="10" name="gowiththeflow_theme_options[last_message]"><?php echo esc_textarea( $options['last_message'] ); ?></textarea>
						<label class="description" for="gowiththeflow_theme_options[last_message]"><?php _e( 'gowiththeflow text box', 'gowiththeflowtheme' ); ?></label>
					</td>
				</tr>

				<!--
				<?php
				/**
				 * A gowiththeflow checkbox option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'A checkbox', 'gowiththeflowtheme' ); ?></th>
					<td>
						<input id="gowiththeflow_theme_options[option1]" name="gowiththeflow_theme_options[option1]" type="checkbox" value="1" <?php checked( '1', $options['option1'] ); ?> />
						<label class="description" for="gowiththeflow_theme_options[option1]"><?php _e( 'gowiththeflow checkbox', 'gowiththeflowtheme' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * A gowiththeflow text input option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Some text', 'gowiththeflowtheme' ); ?></th>
					<td>
						<input id="gowiththeflow_theme_options[sometext]" class="regular-text" type="text" name="gowiththeflow_theme_options[sometext]" value="<?php esc_attr_e( $options['sometext'] ); ?>" />
						<label class="description" for="gowiththeflow_theme_options[sometext]"><?php _e( 'gowiththeflow text input', 'gowiththeflowtheme' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * A gowiththeflow select input option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Select input', 'gowiththeflowtheme' ); ?></th>
					<td>
						<select name="gowiththeflow_theme_options[selectinput]">
							<?php
								$selected = $options['selectinput'];
								$p = '';
								$r = '';

								foreach ( $select_options as $option ) {
									$label = $option['label'];
									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="gowiththeflow_theme_options[selectinput]"><?php _e( 'gowiththeflow select input', 'gowiththeflowtheme' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * A gowiththeflow of radio buttons
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Radio buttons', 'gowiththeflowtheme' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Radio buttons', 'gowiththeflowtheme' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $radio_options as $option ) {
								$radio_setting = $options['posts_order'];

								if ( '' != $radio_setting ) {
									if ( $options['posts_order'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<label class="description"><input type="radio" name="gowiththeflow_theme_options[posts_order]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?></label><br />
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<?php
				/**
				 * A gowiththeflow textarea option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'A textbox', 'gowiththeflowtheme' ); ?></th>
					<td>
						<textarea id="gowiththeflow_theme_options[sometextarea]" class="large-text" cols="50" rows="10" name="gowiththeflow_theme_options[sometextarea]"><?php echo esc_textarea( $options['sometextarea'] ); ?></textarea>
						<label class="description" for="gowiththeflow_theme_options[sometextarea]"><?php _e( 'gowiththeflow text box', 'gowiththeflowtheme' ); ?></label>
					</td>
				</tr>-->
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'gowiththeflowtheme' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
	global $select_options, $radio_options;

	if ( ! isset( $input['display'] ) || ! array_key_exists( $input['display'], $radio_options['display'] ))
		$input['display'] = null;
	if ($input['display'] != 'category')
		$input['choosen_category'] = null;

	if ( ! isset( $input['posts_order'] ) || ! array_key_exists( $input['posts_order'], $radio_options['posts_order'] ))
		$input['posts_order'] = null;

	$input['last_message'] = wp_filter_post_kses( $input['last_message'] );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/