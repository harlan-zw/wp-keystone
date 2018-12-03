<?php
if (is_env_production()) {
    return;
}

/**
 * Register our mailtrap override
 */
add_action( 'phpmailer_init', function( PHPMailer $phpmailer ) {

    $mailtrap_user = env('MAILTRAP_USER');
    $mailtrap_pass = env('MAILTRAP_PASS');
    /**
     * Make sure that they've been defined
     */
    if(empty($mailtrap_user) || empty($mailtrap_pass)) {
        return;
    }

    $phpmailer->Host = 'smtp.mailtrap.io';
    $phpmailer->Port = 2525; // could be different
    $phpmailer->Username = $mailtrap_user; // if required
    $phpmailer->Password = $mailtrap_pass; // if required
    $phpmailer->SMTPAuth = true; // if required
    $phpmailer->IsSMTP();

}, PHP_INT_MAX);

/**
 * In the event of an email failing - we dump the error
 */
add_action('wp_mail_failed', function($error) {
//    dd('Email failed', $error);
});
