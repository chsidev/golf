<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.06
 */

$n7_golf_club_header_css   = '';
$n7_golf_club_header_image = get_header_image();
$n7_golf_club_header_video = n7_golf_club_get_header_video();
if ( ! empty( $n7_golf_club_header_image ) && n7_golf_club_trx_addons_featured_image_override( is_singular() || n7_golf_club_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$n7_golf_club_header_image = n7_golf_club_get_current_mode_image( $n7_golf_club_header_image );
}

$n7_golf_club_header_id = n7_golf_club_get_custom_header_id();
$n7_golf_club_header_meta = get_post_meta( $n7_golf_club_header_id, 'trx_addons_options', true );
if ( ! empty( $n7_golf_club_header_meta['margin'] ) ) {
	n7_golf_club_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( n7_golf_club_prepare_css_value( $n7_golf_club_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $n7_golf_club_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $n7_golf_club_header_id ) ) ); ?>
				<?php
				echo ! empty( $n7_golf_club_header_image ) || ! empty( $n7_golf_club_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
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

	// Custom header's layout
	do_action( 'n7_golf_club_action_show_layout', $n7_golf_club_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
