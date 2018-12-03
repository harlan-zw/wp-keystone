<?php
namespace App;

add_action('wp_head', function() {
    if (!is_user_logged_in()) {
        return;
    }
    ?>
    <style type="text/css">
        body {
            margin-bottom: 32px !important;
        }
        #wpadminbar{top:auto;bottom:0}@media screen and (max-width: 600px){#wpadminbar{position:fixed}}#wpadminbar .menupop .ab-sub-wrapper,#wpadminbar .shortlink-input{bottom:32px}@media screen and (max-width: 782px){#wpadminbar .menupop .ab-sub-wrapper,#wpadminbar .shortlink-input{bottom:46px}}@media screen and (min-width: 783px){.admin-bar.masthead-fixed .site-header{top:0}}
    </style>
    <?php
});

add_action('template_redirect', function () {
    remove_action('wp_head', '_admin_bar_bump_cb');
}, 999);
