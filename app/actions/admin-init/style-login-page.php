<?php

namespace App;

/*
 * Make the admin login screen slightly nicer and branded
 */
add_action('login_enqueue_scripts', function () {
    $field = get_option_page_value(OPTION_PAGE_MAIN_LOGO);
    $src = wp_get_attachment_image_src($field['ID'], ['110', '67'], false); ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?= $src[0] ?>);
            background-size: 80% 80%;
            width: 100%;
            background-position: 25px;
            height: 100px;
        }

        .login #nav a, .login #backtoblog a {
            color: white !important;
        }
    </style>
    <?php
});

