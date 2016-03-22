<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Reset passwords for one or more WordPress users.
 *
 * <user>...
 * : Specify one or more user logins or IDs.
 */
$reset_password_command = function( $args ) {
	$fetcher = new \WP_CLI\Fetchers\User;
	$users = $fetcher->get_many( $args );
	foreach( $users as $user ) {
		wp_update_user( array( 'ID' => $user->ID, 'user_pass' => wp_generate_password() ) );
		WP_CLI::log( "Reset password for {$user->user_login}." );
	}
	WP_CLI::success( 'Passwords reset.' );
};
WP_CLI::add_command( 'user reset-passwords', $reset_password_command );
