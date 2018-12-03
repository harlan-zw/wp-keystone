<?php
$do_lazy_load = true;

function stop_lazy_loading() {
	global $do_lazy_load;
	$do_lazy_load = false;
}
function start_lazy_loading() {
	global $do_lazy_load;
	$do_lazy_load = true;
}

/**
 * Modifies the src and srcset to use data and adds lazyload class. These lets our images be lazy loaded.
 */
add_filter('wp_get_attachment_image_attributes', function($attr) {
	global $do_lazy_load;

	if (!$do_lazy_load) {
		return $attr;
	}
	// avoid conflicts with admin
	if (is_admin()) {
		return $attr;
	}
	if (isset($attr['autoload']) && empty($attr['autoload'])) {
		return $attr;
	}

	$attr['data-src'] = $attr['src'];
	if (isset($attr['srcset'])) {
		$attr['data-srcset'] = $attr['srcset'];
	}
	if (isset($attr['sizes'])) {
		$attr['data-sizes'] = $attr['sizes'];
	}

	unset($attr['src']);
	unset($attr['srcset']);
	unset($attr['sizes']);
	// if they don't already have the class
	if (strpos($attr['class'], 'lazyload') === false) {
		$attr['class'] .= ' lazyload';
	}
	return $attr;
}, PHP_INT_MAX);


function lazy_load_setup_meta($string) {
	return preg_replace_callback('/<(img|iframe|object)(.*?)>/i', function($match) {

		$attr = [];
		// Iterate over each attribute string
		foreach(explode(' ', trim($match[2])) as $att) {
			if (\Illuminate\Support\Str::contains($att, '=')) {
				// Try and rip out the key & value
				preg_match_all('/(.*?)="(.*?)"/', $att, $matches);
				if (isset($matches[1][0]) && $matches[2][0]) {
					$key   = $matches[1][0];
					$value = $matches[2][0];
					// Assign them to our attribute list
					$attr[$key] = $value;
				}
			} else {
				$attr[$att] = null;
			}
		}
		if (!isset($attr['src'])) {
			return $match[0];
		}

		$attr['data-src'] = $attr['src'];
		unset($attr['src']);

		if (isset($attr['srcset'])) {
			$attr['data-srcset'] = $attr['srcset'];
			unset($attr['srcset']);
		}

		if (isset($attr['sizes'])) {
			$attr['data-sizes'] = $attr['sizes'];
			unset($attr['sizes']);
		}

		if (!isset($attr['class'])) {
			$attr['class'] = 'lazyload';
		} else {
			$attr['class'] .= ' lazyload';
		}

		$html = $match[1] . ' ';
		foreach ( $attr as $name => $value ) {
			if (isset($value)) {
				$html .= " $name=" . '"' . trim($value) . '"';
			} else {
				$html .= " $name";
			}
		}

		return "<$html>";
	}, $string);
}

/**
 * Replace img's and iframe's with the lazy loaded versions
 */
add_filter('the_content', function($content) {
	// avoid conflicts with admin
	if (is_admin()) {
		return $content;
	}
	return lazy_load_setup_meta($content);
});
