<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$n7_golf_club_copyright_scheme = n7_golf_club_get_theme_option( 'copyright_scheme' );
if ( ! empty( $n7_golf_club_copyright_scheme ) && ! n7_golf_club_is_inherit( $n7_golf_club_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $n7_golf_club_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$n7_golf_club_copyright = n7_golf_club_get_theme_option( 'copyright' );
			if ( ! empty( $n7_golf_club_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$n7_golf_club_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $n7_golf_club_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$n7_golf_club_copyright = n7_golf_club_prepare_macros( $n7_golf_club_copyright );
				// Display copyright
				echo wp_kses( nl2br( $n7_golf_club_copyright ), 'n7_golf_club_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
