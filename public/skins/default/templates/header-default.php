<?php
/**
 * The template to display default site header
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

$n7_golf_club_header_css   = '';
$n7_golf_club_header_image = get_header_image();
$n7_golf_club_header_video = n7_golf_club_get_header_video();
if ( ! empty( $n7_golf_club_header_image ) && n7_golf_club_trx_addons_featured_image_override( is_singular() || n7_golf_club_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$n7_golf_club_header_image = n7_golf_club_get_current_mode_image( $n7_golf_club_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $n7_golf_club_header_image ) || ! empty( $n7_golf_club_header_video ) ? ' with_bg_image' : ' without_bg_image';
	if ( '' != $n7_golf_club_header_video ) {
		echo ' with_bg_video';
	}
	if ( '' != $n7_golf_club_header_image ) {
		echo ' ' . esc_attr( n7_golf_club_add_inline_css_class( 'background-image: url(' . esc_url( $n7_golf_club_header_image ) . ');' ) );
	}
	if ( is_single() && has_post_thumbnail() ) {
		echo ' with_featured_image';
	}
	if ( n7_golf_club_is_on( n7_golf_club_get_theme_option( 'header_fullheight' ) ) ) {
		echo ' header_fullheight n7-golf-club-full-height';
	}
	$n7_golf_club_header_scheme = n7_golf_club_get_theme_option( 'header_scheme' );
	if ( ! empty( $n7_golf_club_header_scheme ) && ! n7_golf_club_is_inherit( $n7_golf_club_header_scheme  ) ) {
		echo ' scheme_' . esc_attr( $n7_golf_club_header_scheme );
	}
	?>
">
	<?php

	// Background video
	if ( ! empty( $n7_golf_club_header_video ) ) {
		get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-video' ) );
	}

	// Main menu
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( n7_golf_club_is_on( n7_golf_club_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
