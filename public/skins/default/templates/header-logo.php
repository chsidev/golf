<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

$n7_golf_club_args = get_query_var( 'n7_golf_club_logo_args' );

// Site logo
$n7_golf_club_logo_type   = isset( $n7_golf_club_args['type'] ) ? $n7_golf_club_args['type'] : '';
$n7_golf_club_logo_image  = n7_golf_club_get_logo_image( $n7_golf_club_logo_type );
$n7_golf_club_logo_text   = n7_golf_club_is_on( n7_golf_club_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$n7_golf_club_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $n7_golf_club_logo_image['logo'] ) || ! empty( $n7_golf_club_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $n7_golf_club_logo_image['logo'] ) ) {
            if ( empty( $n7_golf_club_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($n7_golf_club_logo_image['logo']) && (int) $n7_golf_club_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$n7_golf_club_attr = n7_golf_club_getimagesize( $n7_golf_club_logo_image['logo'] );
				echo '<img src="' . esc_url( $n7_golf_club_logo_image['logo'] ) . '"'
						. ( ! empty( $n7_golf_club_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $n7_golf_club_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $n7_golf_club_logo_text ) . '"'
						. ( ! empty( $n7_golf_club_attr[3] ) ? ' ' . wp_kses_data( $n7_golf_club_attr[3] ) : '' )
						. '>';
			}
		} else {
			n7_golf_club_show_layout( n7_golf_club_prepare_macros( $n7_golf_club_logo_text ), '<span class="logo_text">', '</span>' );
			n7_golf_club_show_layout( n7_golf_club_prepare_macros( $n7_golf_club_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
