<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Reset passwords for one or more WordPress users.
 *
 * ## OPTIONS
 *
 * <user>...
 * : Specify one or more user logins or IDs.
 *
 * [--skip-email]
 * : Don't send an email notification to the affected user(s).
 */
$reset_password_command = function( $args, $assoc_args ) {

	$skip_email = WP_CLI\Utils\get_flag_value( $assoc_args, 'skip-email' );
	if ( $skip_email ) {
		add_filter( 'send_password_change_email', '__return_false' );
	}

	$fetcher = new \WP_CLI\Fetchers\User;
	$users = $fetcher->get_many( $args );
	foreach( $users as $user ) {
		wp_update_user( array( 'ID' => $user->ID, 'user_pass' => wp_generate_password() ) );
		WP_CLI::log( "Reset password for {$user->user_login}." );
	}

	if ( $skip_email ) {
		remove_filter( 'send_password_change_email', '__return_false' );
	}

	WP_CLI::success( 'Passwords reset.' );
};
WP_CLI::add_command( 'user reset-password', $reset_password_command );
