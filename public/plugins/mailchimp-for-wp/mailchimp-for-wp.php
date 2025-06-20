<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'n7_golf_club_mailchimp_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_mailchimp_theme_setup9', 9 );
	function n7_golf_club_mailchimp_theme_setup9() {
		if ( n7_golf_club_exists_mailchimp() ) {
			add_action( 'wp_enqueue_scripts', 'n7_golf_club_mailchimp_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'n7_golf_club_mailchimp_frontend_scripts', 10, 1 );
			add_filter( 'n7_golf_club_filter_merge_styles', 'n7_golf_club_mailchimp_merge_styles' );
		}
		if ( is_admin() ) {
			add_filter( 'n7_golf_club_filter_tgmpa_required_plugins', 'n7_golf_club_mailchimp_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'n7_golf_club_mailchimp_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_tgmpa_required_plugins',	'n7_golf_club_mailchimp_tgmpa_required_plugins');
	function n7_golf_club_mailchimp_tgmpa_required_plugins( $list = array() ) {
		if ( n7_golf_club_storage_isset( 'required_plugins', 'mailchimp-for-wp' ) && n7_golf_club_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'install' ) !== false ) {
			$list[] = array(
				'name'     => n7_golf_club_storage_get_array( 'required_plugins', 'mailchimp-for-wp', 'title' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( ! function_exists( 'n7_golf_club_exists_mailchimp' ) ) {
	function n7_golf_club_exists_mailchimp() {
		return function_exists( '__mc4wp_load_plugin' ) || defined( 'MC4WP_VERSION' );
	}
}



// Custom styles and scripts
//------------------------------------------------------------------------

// Enqueue styles for frontend
if ( ! function_exists( 'n7_golf_club_mailchimp_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'n7_golf_club_mailchimp_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_mailchimp', 'n7_golf_club_mailchimp_frontend_scripts', 10, 1 );
	function n7_golf_club_mailchimp_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && n7_golf_club_need_frontend_scripts( 'mailchimp' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_style( 'n7-golf-club-mailchimp-for-wp', $n7_golf_club_url, array(), null );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'n7_golf_club_mailchimp_merge_styles' ) ) {
	//Handler of the add_filter( 'n7_golf_club_filter_merge_styles', 'n7_golf_club_mailchimp_merge_styles');
	function n7_golf_club_mailchimp_merge_styles( $list ) {
		$list[ 'plugins/mailchimp-for-wp/mailchimp-for-wp.css' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( n7_golf_club_exists_mailchimp() ) {
	require_once n7_golf_club_get_file_dir( 'plugins/mailchimp-for-wp/mailchimp-for-wp-style.php' );
}

