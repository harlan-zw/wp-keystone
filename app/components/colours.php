<?php

namespace App;

const COLOUR_HOT_PINK = 'F8517A';

const COLOURS_CLASSES = [
	'white' => 'White',
	'grey' => 'Grey',
	'aubergine' => 'Aubergine',
	'purple' => 'Purple',
	'hot-pink' => 'Hot Pink',
	'soft-pink' => 'Soft Pink',
	'soft-blue' => 'Soft Blue',
];

const DARK_COLOUR_CLASSES = [
	'aubergine',
	'purple',
	'hot-pink',
];


function is_colour_class_dark($class) {
	return \in_array($class, DARK_COLOUR_CLASSES, true);
}
