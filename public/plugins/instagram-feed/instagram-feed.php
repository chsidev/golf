<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'n7_golf_club_instagram_feed_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_instagram_feed_theme_setup9', 9 );
	function n7_golf_club_instagram_feed_theme_setup9() {
		if ( n7_golf_club_exists_instagram_feed() ) {
			add_action( 'wp_enqueue_scripts', 'n7_golf_club_instagram_feed_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_instagram_feed', 'n7_golf_club_instagram_feed_frontend_scripts_responsive', 10, 1 );
			add_filter( 'n7_golf_club_filter_merge_styles_responsive', 'n7_golf_club_instagram_merge_styles_responsive' );
		}
		if ( is_admin() ) {
			add_filter( 'n7_golf_club_filter_tgmpa_required_plugins', 'n7_golf_club_instagram_feed_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'n7_golf_club_instagram_feed_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_tgmpa_required_plugins',	'n7_golf_club_instagram_feed_tgmpa_required_plugins');
	function n7_golf_club_instagram_feed_tgmpa_required_plugins( $list = array() ) {
		if ( n7_golf_club_storage_isset( 'required_plugins', 'instagram-feed' ) && n7_golf_club_storage_get_array( 'required_plugins', 'instagram-feed', 'install' ) !== false ) {
			$list[] = array(
				'name'     => n7_golf_club_storage_get_array( 'required_plugins', 'instagram-feed', 'title' ),
				'slug'     => 'instagram-feed',
				'required' => false,
			);
		}
		return $list;
	}
}

// Check if Instagram Feed installed and activated
if ( ! function_exists( 'n7_golf_club_exists_instagram_feed' ) ) {
	function n7_golf_club_exists_instagram_feed() {
		return defined( 'SBIVER' );
	}
}

// Enqueue responsive styles for frontend
if ( ! function_exists( 'n7_golf_club_instagram_feed_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'n7_golf_club_instagram_feed_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_instagram_feed', 'n7_golf_club_instagram_feed_frontend_scripts_responsive', 10, 1 );
	function n7_golf_club_instagram_feed_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && n7_golf_club_need_frontend_scripts( 'instagram_feed' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/instagram-feed/instagram-feed-responsive.css' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_style( 'n7-golf-club-instagram-feed-responsive', $n7_golf_club_url, array(), null, n7_golf_club_media_for_load_css_responsive( 'instagram-feed' ) );
			}
		}
	}
}

// Merge responsive styles
if ( ! function_exists( 'n7_golf_club_instagram_merge_styles_responsive' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_styles_responsive', 'n7_golf_club_instagram_merge_styles_responsive');
	function n7_golf_club_instagram_merge_styles_responsive( $list ) {
		$list[ 'plugins/instagram/instagram-responsive.css' ] = false;
		return $list;
	}
}
