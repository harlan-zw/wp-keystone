<?php
/*
Plugin Name:  Force Mailtrap
Plugin URI:   http://www.4mation.com.au/
Description:  Forces mailtrap on staging and local environments
Version:      1.0.0
Author:       Harlan Wilton
Author URI:   http://www.4mation.com.au/
License:      MIT License
*/
if (is_env_production()) {
    return;
}
/**
 * Make sure that they've been defined
 */
if(!defined('MAILTRAP_USER') || !defined('MAILTRAP_USER')) {
	return;
}
/**
 * Register our mailtrap override
 */
add_action( 'phpmailer_init', function( PHPMailer $phpmailer ) {
    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->Port = 2525; // could be different
    $phpmailer->Username = env('MAILTRAP_USER'); // if required
    $phpmailer->Password = env('MAILTRAP_PASS'); // if required
    $phpmailer->SMTPAuth = true; // if required
    $phpmailer->IsSMTP();
}, PHP_INT_MAX);
