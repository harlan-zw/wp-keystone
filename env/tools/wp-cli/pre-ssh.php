<?php
WP_CLI::add_hook( 'before_ssh', function() {

    $host = WP_CLI\Utils\parse_ssh_url( WP_CLI::get_runner()->config['ssh'], PHP_URL_HOST );
    switch( $host ) {
        default:
            putenv( 'WP_CLI_SSH_PRE_CMD=export PATH=$HOME/bin:$PATH' );
            break;
    }
});
