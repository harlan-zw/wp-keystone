<?php
namespace App;

/**
 * Get randomized related articles based on current post's categories.
 * @return array|bool
 */
function get_related_articles() {
	global $post;

	// check if post then assign current post
	if (empty($post)) {
		return false;
	}

	$categories = get_the_category();
	// Get category IDs for query arguments
	$category_ids = array_map(function ($category) {
		return $category->term_id;
	}, $categories);

	// Prepare arguments for posts in same categories.
	$args = [
	  'posts_per_page'         => 2,
		'post_type'              => [ 'post' ],
		'post_status'            => [ 'published' ],
		'orderby'                => 'rand',
		'category__in'           => $category_ids,
		'post__not_in'           => [$post->ID]
	];

	return get_posts($args);
}

/**
 * Get Primary category for a post.
 * @param $post
 *
 * @return \WP_Term|false
 */
function get_primary_category($post) {
	$categories = get_the_category($post);
	$primary_category = false;
	foreach ($categories as $term) {
		if ((int) get_post_meta($post->ID, '_yoast_wpseo_primary_category', true) === $term->term_id) {
			$primary_category = $term;
		}
	}
	return $primary_category;
}

/**
 * Add filter to get excerpt from widget based post.
 */
add_filter('get_the_excerpt', function($excerpt, $post, $length = 35) {
	$widget = \App\get_widgets_type('wysiwyg', $post->ID);
	$content = $excerpt;
	if (!empty($widget)) {
		$content = strip_tags($widget[0]->wysiwyg_content);
	}
	return wp_trim_words($content, $length, '  &hellip;');
}, PHP_INT_MAX, 3);
