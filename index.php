<?php get_header(); ?>
	<div id="container">

		<div id="map_container">
			<div class="to_shift">
				<div class="pointer"></div>
				<img class="map" src="<?php bloginfo('template_url') ?>/img/map.png" />
			</div>	
		</div>

		<div id="background_container">
			<div class="filter"></div>
			<div class="transition"></div>
			<div class="preload"></div>
		</div>
		
		<div class="page home">
			
			<h1><?php bloginfo('name'); ?></h1>
			<?php $description = is_category() ? get_cat_name(get_query_var('cat')) : get_bloginfo('description'); ?>
			<p class="description"<?php if (!$description) echo ' style="display:none;"' ?>><?php echo preg_replace('#__(.+)__#', '<strong>$1</strong>', $description); ?></p>

			<div class="stripe1"></div><div class="stripe2"></div>
			<div class="button_next"></div>

		</div>

		<div class="page next">
			<div class="to_shift">
				<div class="block">
					<article>
						<h2 class="title">Empty article</h2>
						<div class="meta">
							<p class="date">
								<span class="day">01</span>&nbsp;<span class="month">January</span><br />
								<span class="year">2013</span>
							</p>
							<p class="place">
								<span class="city">Somewhere</span><br />
								<span class="country">Somewhere</span>
							</p>
							<p class="image">
								Photo<br />
								<span class="credits">Unknown</span>
							</p>
							<div class="pictures">
								
							</div>
						</div>
						<div class="content">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						</div>
					</article>
					<div class="clr"></div>
				</div>
				<div class="clr"></div>
			</div>
		</div>

	</div>

<?php get_footer(); ?>