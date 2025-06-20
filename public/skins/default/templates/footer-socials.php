<?php
/**
 * The template to display the socials in the footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */


// Socials
if ( n7_golf_club_is_on( n7_golf_club_get_theme_option( 'socials_in_footer' ) ) ) {
	$n7_golf_club_output = n7_golf_club_get_socials_links();
	if ( '' != $n7_golf_club_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php n7_golf_club_show_layout( $n7_golf_club_output ); ?>
			</div>
		</div>
		<?php
	}
}
