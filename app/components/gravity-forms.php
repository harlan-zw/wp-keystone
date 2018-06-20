<?php
/**
 * Gravity Forms Bootstrap Styles
 *
 * Applies bootstrap classes to various common field types.
 * Requires Bootstrap to be in use by the theme.
 *
 * Using this function allows use of Gravity Forms default CSS
 * in conjuction with Bootstrap (benefit for fields types such as Address).
 *
 * @see  gform_field_content
 * @link http://www.gravityhelp.com/documentation/page/Gform_field_content
 *
 * @return string Modified field content
 */

use PHPHtmlParser\Dom;

add_filter('gform_field_content', function($content, $field, $value, $lead_id, $form_id) {

    // Currently only applies to most common field types, but could be expanded.
    if ($field['type'] != 'hidden' &&
        $field['type'] != 'list' &&
        $field['type'] != 'multiselect' &&
        $field['type'] != 'select' &&
        $field['type'] != 'checkbox' &&
        $field['type'] != 'fileupload' &&
        $field['type'] != 'date' &&
        $field['type'] != 'html' &&
        $field['type'] != 'address') {
        $content = str_replace('class=\'medium', 'class=\'form-control medium', $content);
    }

    if ($field['type'] == 'multiselect' || $field["type"] == 'select') {
        $content = str_replace('class=\'medium', 'class=\'form-control', $content);
    }

    if ($field['type'] == 'name' || $field["type"] == 'address') {
        $content = str_replace('<input ', '<input class=\'form-control\' ', $content);
    }

    if ($field['type'] == 'textarea') {
        $content = str_replace('class=\'textarea', 'class=\'form-control textarea', $content);
    }

    if ($field['type'] == 'checkbox') {
	    $dom = new Dom();
	    $dom->load($content);

	    $lis = $dom->find('li');
	    /** @var Dom\Tag $li */
	    foreach($lis as $li) {
	    	$li->setAttribute('class', 'checkbox');

		    $li->find('input')[0]->setAttribute('class', 'checkbox__control');
		    $li->find('label')[0]->setAttribute('class', 'checkbox__label');
	    }
		$content = $dom->outerHtml;
    }

    if ($field['type'] == 'radio') {
        $content = str_replace('li class=\'', 'li class=\'radio ', $content);
        $content = str_replace('<input ', '<input style=\'margin-left:1px;\' ', $content);
    }

    return $content;
}, 10, 5);

// End bootstrap_styles_for_gravityforms_fields()
add_filter('gform_submit_button', function($button, $form) {
    return "<button class='button btn btn-default' id='gform_submit_button_{$form["id"]}'><span>Submit</span></button>";
}, 10, 2);


/**
 * Add bootstrap form classes to gravity form inputs
 */
add_filter('gform_field_css_class', function($classes, $field, $form) {
    $classes .= ' form-group';
    return $classes;
}, 10, 6);
