<?php

namespace App;

/**
 * Checks we have the add-on enabled
 */
if (!function_exists('acf_add_options_page')) {
	return;
}

const OPTION_PAGE_SLUG = 'wp-keystone-settings';

$fields = [
	[
		'key' => 'field_58d33468f20a2',
		'label' => 'Branding',
		'name' => '',
		'type' => 'tab',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'placement' => 'left',
		'endpoint' => 0,
	],
	[
		'key' => 'field_58d331f7fc040',
		'label' => 'Main Logo',
		'name' => OPTION_PAGE_MAIN_LOGO,
		'type' => 'image',
		'instructions' => '',
		'required' => 1,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'return_format' => 'array',
		'preview_size' => 'medium',
		'library' => 'all',
		'min_width' => '',
		'min_height' => '',
		'min_size' => '',
		'max_width' => '',
		'max_height' => '',
		'max_size' => '',
		'mime_types' => '',
	],
	[
		'key' => 'field_58d33231fc041',
		'label' => 'Alt Logo',
		'name' => OPTION_PAGE_ALT_LOGO,
		'type' => 'image',
		'instructions' => '',
		'required' => 1,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'return_format' => 'array',
		'preview_size' => 'medium',
		'library' => 'all',
		'min_width' => '',
		'min_height' => '',
		'min_size' => '',
		'max_width' => '',
		'max_height' => '',
		'max_size' => '',
		'mime_types' => '',
	],
	[
		'key' => 'field_58d33641ab18e',
		'label' => 'Contact',
		'name' => '',
		'type' => 'tab',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'placement' => 'top',
		'endpoint' => 0,
	],
	[
		'key' => 'field_58d33709ab191',
		'label' => 'Email',
		'name' => OPTION_PAGE_CONTACT_EMAIL,
		'type' => 'email',
		'instructions' => '',
		'required' => 1,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'default_value' => '',
		'placeholder' => '',
		'prepend' => '',
		'append' => '',
	],
	[
		'key' => 'field_58e3672642fd4',
		'label' => 'Vendor',
		'name' => '',
		'type' => 'tab',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'placement' => 'top',
		'endpoint' => 0,
	],
	// Tab - Content
	[
		'key' => 'field_58fd6bc464bab',
		'label' => 'Content',
		'name' => '',
		'type' => 'tab',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => [
			'width' => '',
			'class' => '',
			'id' => '',
		],
		'placement' => 'left',
		'endpoint' => 0,
	],
];


// Add in our option pages
acf_add_options_page([
	'page_title' => 'Wordpress Keystone Settings',
	'menu_title' => 'Wordpress Keystone Settings',
	'menu_slug' => OPTION_PAGE_SLUG,
	'position' => 70,
	'icon_url' => 'dashicons-admin-customizer'
]);

/**
 * Allow the fields to be overridden by any registered filters
 */
$fields = apply_filters('wp-keystone/options-page-fields', $fields);

acf_add_local_field_group([
	'key' => 'group_58d331ebd36cc',
	'title' => 'Global Settings',
	'fields' => $fields,
	'location' => [
		[
			[
				'param' => 'options_page',
				'operator' => '==',
				'value' => OPTION_PAGE_SLUG,
			],
		],
	],
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
]);
