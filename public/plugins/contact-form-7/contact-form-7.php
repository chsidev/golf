<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'n7_golf_club_cf7_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_cf7_theme_setup9', 9 );
	function n7_golf_club_cf7_theme_setup9() {
		if ( n7_golf_club_exists_cf7() ) {
			add_action( 'wp_enqueue_scripts', 'n7_golf_club_cf7_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_cf7', 'n7_golf_club_cf7_frontend_scripts', 10, 1 );
			add_filter( 'n7_golf_club_filter_merge_styles', 'n7_golf_club_cf7_merge_styles' );
			add_filter( 'n7_golf_club_filter_merge_scripts', 'n7_golf_club_cf7_merge_scripts' );
		}
		if ( is_admin() ) {
			add_filter( 'n7_golf_club_filter_tgmpa_required_plugins', 'n7_golf_club_cf7_tgmpa_required_plugins' );
			add_filter( 'n7_golf_club_filter_theme_plugins', 'n7_golf_club_cf7_theme_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( ! function_exists( 'n7_golf_club_cf7_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_tgmpa_required_plugins',	'n7_golf_club_cf7_tgmpa_required_plugins');
	function n7_golf_club_cf7_tgmpa_required_plugins( $list = array() ) {
		if ( n7_golf_club_storage_isset( 'required_plugins', 'contact-form-7' ) && n7_golf_club_storage_get_array( 'required_plugins', 'contact-form-7', 'install' ) !== false ) {
			// CF7 plugin
			$list[] = array(
				'name'     => n7_golf_club_storage_get_array( 'required_plugins', 'contact-form-7', 'title' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			);
		}
		return $list;
	}
}

// Filter theme-supported plugins list
if ( ! function_exists( 'n7_golf_club_cf7_theme_plugins' ) ) {
	//Handler of the add_filter( 'n7_golf_club_filter_theme_plugins', 'n7_golf_club_cf7_theme_plugins' );
	function n7_golf_club_cf7_theme_plugins( $list = array() ) {
		return n7_golf_club_add_group_and_logo_to_slave( $list, 'contact-form-7', 'contact-form-7-' );
	}
}



// Check if cf7 installed and activated
if ( ! function_exists( 'n7_golf_club_exists_cf7' ) ) {
	function n7_golf_club_exists_cf7() {
		return class_exists( 'WPCF7' );
	}
}

// Enqueue custom scripts
if ( ! function_exists( 'n7_golf_club_cf7_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'n7_golf_club_cf7_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_cf7', 'n7_golf_club_cf7_frontend_scripts', 10, 1 );
	function n7_golf_club_cf7_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && n7_golf_club_need_frontend_scripts( 'cf7' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/contact-form-7/contact-form-7.css' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_style( 'n7-golf-club-contact-form-7', $n7_golf_club_url, array(), null );
			}
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/contact-form-7/contact-form-7.js' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_script( 'n7-golf-club-contact-form-7', $n7_golf_club_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'n7_golf_club_cf7_merge_styles' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_styles', 'n7_golf_club_cf7_merge_styles');
	function n7_golf_club_cf7_merge_styles( $list ) {
		$list[ 'plugins/contact-form-7/contact-form-7.css' ] = false;
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'n7_golf_club_cf7_merge_scripts' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_scripts', 'n7_golf_club_cf7_merge_scripts');
	function n7_golf_club_cf7_merge_scripts( $list ) {
		$list[ 'plugins/contact-form-7/contact-form-7.js' ] = false;
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if ( n7_golf_club_exists_cf7() ) {
	require_once n7_golf_club_get_file_dir( 'plugins/contact-form-7/contact-form-7-style.php' );
}
