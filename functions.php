<?php 

define( 'ACF_LITE' , true );
include_once( 'advanced-custom-fields/acf.php' );
require_once ( get_template_directory() . '/custom-fields.php' );
require_once ( get_template_directory() . '/theme-options.php' );

// Add support for custom backgrounds
add_theme_support( 'custom-background', array(
	'default-color' => '#dcdee1'
));

// Add support for thumbnails
add_theme_support('post-thumbnails');

// Sidebars
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Header - left',
		'id' => 'header-left',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name' => 'Header - main',
		'id' => 'header-main',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name' => 'Header - right',
		'id' => 'header-right',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
}

