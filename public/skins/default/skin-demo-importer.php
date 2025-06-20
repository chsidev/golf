<?php
/**
 * Skin Demo importer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.76.0
 */


// Theme storage
//-------------------------------------------------------------------------

n7_golf_club_storage_set( 'theme_demo_url', '//golfclub.themerex.net' );


//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'n7_golf_club_skin_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'n7_golf_club_skin_importer_set_options', 9 );
	function n7_golf_club_skin_importer_set_options( $options = array() ) {
		if ( is_array( $options ) ) {
			$demo_type = function_exists( 'n7_golf_club_skins_get_current_skin_name' ) ? n7_golf_club_skins_get_current_skin_name() : 'default';
			if ( 'default' != $demo_type ) {
				$options['demo_type'] = $demo_type;
				$options['files'][ $demo_type ] = $options['files']['default'];
				unset($options['files']['default']);
			}
			$options['files'][ $demo_type ]['title']       = esc_html__( 'N7 Golf Club Demo', 'n7-golf-club' );
			$options['files'][ $demo_type ]['domain_dev']  = '';  // Developers domain
			$options['files'][ $demo_type ]['domain_demo'] = n7_golf_club_storage_get( 'theme_demo_url' ); // Demo-site domain
			if ( substr( $options['files'][ $demo_type ]['domain_demo'], 0, 2 ) === '//' ) {
				$options['files'][ $demo_type ]['domain_demo'] = n7_golf_club_get_protocol() . ':' . $options['files'][ $demo_type ]['domain_demo'];
			}
		}
		return $options;
	}
}


//------------------------------------------------------------------------
// OCDI support
//------------------------------------------------------------------------

// Set theme specific OCDI options
if ( ! function_exists( 'n7_golf_club_skin_ocdi_set_options' ) ) {
	add_filter( 'trx_addons_filter_ocdi_options', 'n7_golf_club_skin_ocdi_set_options', 9 );
	function n7_golf_club_skin_ocdi_set_options( $options = array() ) {
		if ( is_array( $options ) ) {
			// Demo-site domain
			$options['files']['ocdi']['title']       = esc_html__( 'N7 Golf Club OCDI Demo', 'n7-golf-club' );
			$options['files']['ocdi']['domain_demo'] = n7_golf_club_storage_get( 'theme_demo_url' );
			if ( substr( $options['files']['ocdi']['domain_demo'], 0, 2 ) === '//' ) {
				$options['files']['ocdi']['domain_demo'] = n7_golf_club_get_protocol() . ':' . $options['files']['ocdi']['domain_demo'];
			}
			// If theme need more demo - just copy 'default' and change required parameters
		}
		return $options;
	}
}
