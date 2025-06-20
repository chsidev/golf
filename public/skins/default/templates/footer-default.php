<?php
/**
 * The template to display default site footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$n7_golf_club_footer_scheme = n7_golf_club_get_theme_option( 'footer_scheme' );
if ( ! empty( $n7_golf_club_footer_scheme ) && ! n7_golf_club_is_inherit( $n7_golf_club_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $n7_golf_club_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'n7_golf_club_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
