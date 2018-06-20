<?php

namespace App;

if (!class_exists('ACFWidgets\Loader')) {
	return;
}

/**
 * Only if our plugin is registered
 */

use ACFWidgets\Helpers\WidgetHelper;

/**
 * Load in our registeres widgets
 */
add_filter('acf-widgets/register', function() {
	//load in classes
	return [
		'call-to-action',
		'wysiwyg',
		'form',
		'card',
	];
});

add_filter('acf-widgets/widgets-dir', function() {
	return ROOT_DIR . '/resources/views/widgets/';
});

add_filter('acf-widgets/supported-post-types', function() {
	return [
		'post',
		'page'
	];
});

/**
 * When creating a new page it should auto-populate widgets for us to improve the accuracy and efficiency
 * in creating content.
 */
add_action('wp_insert_post', function($post_id, $post) {
	// Auto-draft is the status for new empty pages
	if ($post->post_status != 'auto-draft') {
		return;
	}
	if (!WidgetHelper::is_post_type_supported($post->post_type)) {
		// Only if the post type is supported
		return;
	}

	// The list of widgets to have shown by default
	$enabled_widgets = [
		'call-to-action',
		'wysiwyg',
	];
	// Sets the widgets as shown
	update_post_meta($post_id, '_acf_widgets', \ACFWidgets\acf\WidgetACF::FIELD_KEY);
	update_post_meta($post_id, 'acf_widgets', serialize($enabled_widgets));

	// Extra explicit configuration
	update_post_meta($post_id, '_acf_widgets_1_wysiwyg_content', 'field_583ba277c225c');
	update_post_meta(
		$post_id,
		'acf_widgets_1_wysiwyg_content',
		'<h2>Change Me</h2><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                   Alias, amet corporis cupiditate dolor illum magni repudiandae. Aliquam amet consectetur 
                   dolor ex, facere inventore, odit quae quasi qui quos reiciendis rerum?</p>'
	);

}, 10, 2);


/**
 * Helper function for getting a list of active widgets for a post
 * @param bool $post_id
 * @return \ACFWidgets\model\Widget[]
 */
function get_widget_list($post_id = false) {
	if (empty($post_id)) {
		$post_id = get_the_ID();
	}
	return WidgetHelper::get_widgets_for_post($post_id);
}

function get_widgets_type($type, $post_id = false) {
	return collect(get_widget_list($post_id))->filter(function($w) use ($type) {
		return $w->slug == $type;
	})->values()->all();
}

/**
 * Checks if the first widget is call-to-action
 * @return bool
 */
function is_widgets_blocking_title() {
	// Render shop page widgets
	foreach (get_widget_list(get_the_ID()) as $widget) {
		if (!$widget->isFirst()) {
			continue;
		}
		if ($widget->slug === 'call-to-action' || $widget->slug === 'slider') {
			return true;
		}
	}
	return false;
}


/**
 * Checks if the first widget is call-to-action
 * @return bool
 */
function has_widgets() {
	// Render shop page widgets
	return !empty(get_widget_list(get_the_ID()));
}
