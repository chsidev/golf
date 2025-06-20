<?php
/**
 * The template to display the site logo in the footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */

// Logo
if ( n7_golf_club_is_on( n7_golf_club_get_theme_option( 'logo_in_footer' ) ) ) {
	$n7_golf_club_logo_image = n7_golf_club_get_logo_image( 'footer' );
	$n7_golf_club_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $n7_golf_club_logo_image['logo'] ) || ! empty( $n7_golf_club_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $n7_golf_club_logo_image['logo'] ) ) {
					$n7_golf_club_attr = n7_golf_club_getimagesize( $n7_golf_club_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $n7_golf_club_logo_image['logo'] ) . '"'
								. ( ! empty( $n7_golf_club_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $n7_golf_club_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'n7-golf-club' ) . '"'
								. ( ! empty( $n7_golf_club_attr[3] ) ? ' ' . wp_kses_data( $n7_golf_club_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $n7_golf_club_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $n7_golf_club_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
