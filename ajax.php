<?php

require_once ('../../../wp-config.php');

$filters = '';
$junctions = '';

// Get the theme options
$options = get_option('gowiththeflow_theme_options');
$order = !isset($options['posts_order']) || empty($options['posts_order']) || $options['posts_order'] == 'by_date' ? " ORDER BY $wpdb->posts.post_date" : ' ORDER BY rand()';

// Create the list of already-seen IDs
$avoid = $_GET['avoid'];
$avoid = is_array($avoid) ? array_map(intval, $avoid) : array();
$filters = count($avoid) ? " AND $wpdb->posts.ID NOT IN (".implode(", ", $avoid).")" : "";

// If a specific article is displayed
$first = intval($_GET['first']);
if ($first && !in_array($first, $avoid))
	$filters = " AND $wpdb->posts.ID = ".$first;

// If a specific category is displayed
$category = intval($_GET['category']);
if ($category) {
	$order = get_field('order', 'category_' . $category);
	$order = !$order || $order == "By date of publication" ? " ORDER BY $wpdb->posts.post_date" : ' ORDER BY rand()';
	$junctions = " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";
	$filters .= " AND $wpdb->term_taxonomy.term_id = " . $category;
}
// The query
$wp_query = new WP_Query();
$results = $wpdb->get_results(
	"SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_content, $wpdb->posts.post_date
	FROM $wpdb->posts
	$junctions
	WHERE $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_type = 'post'
	$filters
	$order
	LIMIT 1");

// If no more articles
if (!count($results))
	die(json_encode(array('end' => (string)$options['last_message'])));

// Render the result
foreach ($results as $post):
	$fields = get_fields($post->ID);
	$fields['id'] = $post->ID;
	$fields['title'] = $post->post_title;
	$fields['content'] = parseContent($post->post_content);
	list($date, $dummy) = explode(' ', str_replace('-', '', $post->post_date));
	$fields['day'] = is_array($fields['date_display']) && in_array('Day', $fields['date_display']) ? substr($date, 6, 2) : '&nbsp;';
	$fields['month'] = is_array($fields['date_display']) && in_array('Month', $fields['date_display']) ? date_i18n("F", mktime(0, 0, 0, substr($date, 4, 2), 1)) : '&nbsp;';
	$fields['year'] = is_array($fields['date_display']) && in_array('Year', $fields['date_display']) ? substr($date, 0, 4) : '&nbsp;';
	echo json_encode($fields);
endforeach;

function parseContent ($content) {
	// Activate shortcodes
	$content = do_shortcode($content);
	
	// Transform the quotes
	$content = preg_replace(array(
			'#\<p\>\"#',
			'#\"\<\/p\>#'
		), array(
			'<p class="quotation"><span class="quote first">‟ </span>',
			'<span class="quote last"> ‟</span></p>'
		), $content);

	// Check for NEXT buttons
	// ex: [suite h=200 w=400 v=-10]
	$code_name = 'next';
	$code_params = 'whv';
	if (preg_match_all('#\['.$code_name.'( ['.$code_params.']\=[\-\.0-9]+)?( ['.$code_params.']\=[\-\.0-9]+)?( ['.$code_params.']\=[\-\.0-9]+)?\]?#', $content, $params)) {
		$texts = preg_split('#\['.$code_name.'( ['.$code_params.']\=[\-\.0-9]+)?( ['.$code_params.']\=[\-\.0-9]+)?( ['.$code_params.']\=[\-\.0-9]+)?\]?#', $content);
		$content = array();
		$params_ordered = array();
		foreach ($params as $i => $values) {
			if ($i == 0)
				continue;
			foreach ($values as $j => $value)
				$params_ordered[$j][$i] = trim($value);
		}
		foreach ($texts as $i => $text) {
			$content[$i] = array('text' => trim($text));
			if ($i == 0)
				continue;
			foreach ($params_ordered[$i-1] as $value) {
				if (empty($value))
					continue;
				$content[$i][$value[0]] = floatval(substr($value, 2));
			}
		}
	}
	return $content;
}

?>