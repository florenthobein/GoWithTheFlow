<?php

$queried_object = get_queried_object();
if ($queried_object && $queried_object->post_type == 'attachment') {
	$image = $queried_object->guid;
	$imginfo = getimagesize($image);
	header("Content-type: ".$imginfo['mime']);
	readfile($image);
	die;
}
elseif ($queried_object && $queried_object->post_type == 'post') {
	$article_id = get_queried_object_id();
}
elseif ($queried_object && $queried_object->taxonomy == 'category') {
	$category = get_query_var('cat');
	$body_background = get_field('image', 'category_' . $category);
	$article_id = null;
}

// Get the theme options
$options = get_option('gowiththeflow_theme_options');
$category = !$category && isset($options['display']) && $options['display'] == 'category' && !empty($options['choosen_category']) ? $options['choosen_category'] : $category;

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php bloginfo('stylesheet_url');?>" rel="stylesheet" />

    <?php wp_enqueue_script("jquery"); ?>
    <?php wp_head(); ?>
    
    <script type="text/javascript">
  		var ajax_url = '<?php bloginfo('template_url'); ?>/ajax.php';
    </script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/modernizr.custom.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/site.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.scrollto.min.js"></script>
    <?php if ($_GET['e']): ?>
    <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-ui-1.10.3.custom.min.js"></script>
	<?php endif; ?>

	<link href="http://fonts.googleapis.com/css?family=Arizonia" rel="stylesheet" type="text/css">

    
  </head>
  <body class="custom-background<?php if ($_GET['e']) echo ' editable'; ?>"<?php if ($category) echo ' data-category="'.$category.'"'; if ($article_id) echo ' data-first="'.$article_id.'"'; if ($body_background) echo ' style="background-image: url(\''.$body_background['url'].'\') !important;"' ?>>

	<div id="preloader">
		<div>
			<img src="<?php bloginfo('template_url'); ?>/img/logo.png" alt="Logo" />
			<div class="loadbar"><div></div></div>
		</div>
	</div>

	<div id="disabler">
		<div class="small_device">
			<h3>Oh non !</h3>
			<p>Croyez-moi, ce site vaut le coup d'être vu depuis un plus grand écran.</p>
		</div>
		<div class="old_browser">
			<h3>Oups !</h3>
			<p>Désolé, on dirait que votre navigateur n'est pas assez performant pour afficher ce site.</p>
			<p>Je vous conseille d'utiliser Firefox ou Chrome, ou bien (si vous voulez vraiment) Internet Explorer mais à partir de sa version 9.</p>
		</div>
	</div>
	
	<div id="adminbar">
		<div class="admin-block">
			<strong>Block Shift</strong> &nbsp; h <input data-target-dom=".page.current .to_shift" data-target-css="margin-left" type="text" name="block_shift_h" /> &nbsp; v <input data-target-dom=".page.current .to_shift" data-target-css="margin-top" type="text" name="block_shift_v" />
		</div>
		<div class="admin-block">
			<strong>Block Width</strong> &nbsp; <input data-target-dom=".page.current .block.selected" data-target-css="width" type="text" name="block_size" />
		</div>
		<div class="admin-block-custom">
			<strong>Block Info</strong> &nbsp; <input type="text" name="block_custom" style="width:300px;" />
		</div>
		<div class="admin-map">
			<strong>Map Shift</strong> &nbsp; h <input data-target-dom="#map_container .to_shift" data-target-css="left" type="text" name="map_shift_h" /> &nbsp; v <input data-target-dom="#map_container .to_shift" data-target-css="top" type="text" name="map_shift_v" />
		</div>
	</div>

	<?php
	$nb_active_areas = 0;
	if (function_exists('dynamic_sidebar')): 
		$widgets = wp_get_sidebars_widgets();
		$nb_active_areas = 0;
		foreach ($widgets as $name => $value) {
			if (preg_match('#^wp_#', $name))
				continue;
			if (count($value))
				$nb_active_areas++;
		}
	endif;
	?>
	<?php if ($nb_active_areas): ?>
	<div id="menu">
		<a href="#" onClick="return false;" class="navigation btn_menu close">x</a>
		<div class="container">
			<div class="row-fluid">
				<div class="span4"><?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('header-left') ); ?>&nbsp;</div>
				<div class="span4"><?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('header-main') ); ?>&nbsp;</div>
				<div class="span4"><?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('header-right') ); ?>&nbsp;</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($nb_active_areas): ?><a href="#" onClick="return false;" class="navigation btn_menu"></a><?php endif; ?>
	<a href="#" onClick="return false;" class="navigation btn_display"></a>
	<a href="#" onClick="return false;" class="navigation btn_previousarticle"></a>
	<a href="#" onClick="return false;" class="navigation btn_nextarticle"></a>