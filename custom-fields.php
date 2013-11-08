<?php
/**
 *  Install Add-ons
 *  
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *  
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme/plugin as outlined in the terms and conditions.
 *  For more information, please read:
 *  - http://www.advancedcustomfields.com/terms-conditions/
 *  - http://www.advancedcustomfields.com/resources/getting-started/including-lite-mode-in-a-plugin-theme/
 */ 

// Add-ons 
// include_once('add-ons/acf-repeater/acf-repeater.php');
// include_once('add-ons/acf-gallery/acf-gallery.php');
// include_once('add-ons/acf-flexible-content/acf-flexible-content.php');
// include_once( 'add-ons/acf-options-page/acf-options-page.php' );


/**
 * Enregistrez des groupes de champs
 * La fonction register_field_group accepte 1 tableau qui contient les données nécessaire à l‘enregistrement d'un groupe de champs
 * Vous pouvez modifier ce tableau selon vos besoins. Cela peut toutefois provoquer des erreurs dans les cas où le tableau ne serait plus compatible avec ACF
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_background',
		'title' => 'Background',
		'fields' => array (
			array (
				'key' => 'field_51ffbacff8913',
				'label' => 'Background image',
				'name' => 'background_image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_51ffbb23f8914',
				'label' => 'Background color',
				'name' => 'background_color',
				'type' => 'color_picker',
				'default_value' => '#dcdee1',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_categories',
		'title' => 'Categories',
		'fields' => array (
			array (
				'key' => 'field_5201101505d95',
				'label' => 'Image',
				'name' => 'image',
				'type' => 'image',
				'save_format' => 'object',
				'preview_size' => 'thumbnail',
				'library' => 'all',
			),
			array (
				'key' => 'field_5201103205d96',
				'label' => 'Posts order',
				'name' => 'order',
				'type' => 'radio',
				'choices' => array (
					'Shuffled' => 'Shuffled',
					'By date of publication' => 'By date of publication',
				),
				'other_choice' => 0,
				'save_other_choice' => 0,
				'default_value' => 'By date of publication',
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'ef_taxonomy',
					'operator' => '==',
					'value' => 'category',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_display-date',
		'title' => 'Display date',
		'fields' => array (
			array (
				'key' => 'field_52012ad18e90e',
				'label' => 'Display...',
				'name' => 'date_display',
				'type' => 'checkbox',
				'choices' => array (
					'Day' => 'Day',
					'Month' => 'Month',
					'Year' => 'Year',
				),
				'default_value' => '',
				'layout' => 'horizontal',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_map-position',
		'title' => 'Map position',
		'fields' => array (
			array (
				'key' => 'field_52001692b9f04',
				'label' => 'Horizontal shift',
				'name' => 'map_horizontal_shift',
				'type' => 'number',
				'instructions' => 'in pixels',
				'default_value' => 0,
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_520016b2b9f05',
				'label' => 'Vertical shift',
				'name' => 'map_vertical_shift',
				'type' => 'number',
				'instructions' => 'in pixels',
				'default_value' => 0,
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_place',
		'title' => 'Place',
		'fields' => array (
			array (
				'key' => 'field_51ffb9a9cb9ff',
				'label' => 'City',
				'name' => 'city',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'html',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ffb9b8cba00',
				'label' => 'Country',
				'name' => 'country',
				'type' => 'text',
				'default_value' => '',
				'formatting' => 'html',
				'maxlength' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ffb9c4cba01',
				'label' => 'Latitude',
				'name' => 'latitude',
				'type' => 'number',
				'default_value' => '',
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ffb9d9cba02',
				'label' => 'Longitude',
				'name' => 'longitude',
				'type' => 'number',
				'default_value' => '',
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_post-position',
		'title' => 'Post position',
		'fields' => array (
			array (
				'key' => 'field_51ffbbd61f952',
				'label' => 'Horizontal shift',
				'name' => 'horizontal_shift',
				'type' => 'number',
				'instructions' => 'in pixels',
				'default_value' => 0,
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ffbbf71f953',
				'label' => 'Vertical shift',
				'name' => 'vertical_shift',
				'type' => 'number',
				'instructions' => 'in pixels',
				'default_value' => 0,
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array (
				'key' => 'field_51ffdd346d778',
				'label' => 'Block width',
				'name' => 'block_width',
				'type' => 'number',
				'instructions' => 'Size of the article block, in pixels',
				'default_value' => '',
				'min' => '',
				'max' => '',
				'step' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
?>