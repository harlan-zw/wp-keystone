<?php

namespace App;

const PAGE_OPTIONS_SHOW_BREADCRUMBS = 'show_breadcrumbs';
const PAGE_OPTIONS_SHOW_BREADCRUMBS_KEY = 'field_58e5d1033196f';

acf_add_local_field_group([
	'key' => 'group_58e5cf0776e98',
	'title' => 'Page Options',
	'fields' => [
		[
			'key' => 'field_5a554e288e53b',
			'label' => 'Show Campaign Pop Up',
			'name' => 'page_pop_up',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => [
				'width' => '',
				'class' => '',
				'id' => '',
			],
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		],
		[
			'key' => 'field_5b0f8defe7f83',
			'label' => 'Show Sub Header',
			'name' => 'show_sub_header',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => [
				'width' => '',
				'class' => '',
				'id' => '',
			],
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		],
		[
			'key' => 'field_5b0f8e08e7f84',
			'label' => 'Sub Menu Links',
			'name' => 'sub_menu_links',
			'type' => 'repeater',
			'instructions' => 'The link should be the id of the widget. For example if you want to link to the first wyiswyg, you should do #widget-wysiwyg-1',
			'required' => 0,
			'conditional_logic' => [
				[
					[
						'field' => 'field_5b0f8defe7f83',
						'operator' => '==',
						'value' => '1',
					],
				],
			],
			'wrapper' => [
				'width' => '',
				'class' => '',
				'id' => '',
			],
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => [
				[
					'key' => 'field_5b0f8e1de7f85',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'text',
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
					'maxlength' => '',
				],
				[
					'key' => 'field_5b0f8e2ce7f86',
					'label' => 'Link',
					'name' => 'link',
					'type' => 'text',
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
					'maxlength' => '',
				],
			],
		],
	],

	'location' => [
		[
			[
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			],
		],
	],
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
]);
