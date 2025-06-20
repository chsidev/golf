<?php
/**
 * The template to display default site footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */

$n7_golf_club_footer_id = n7_golf_club_get_custom_footer_id();
$n7_golf_club_footer_meta = get_post_meta( $n7_golf_club_footer_id, 'trx_addons_options', true );
if ( ! empty( $n7_golf_club_footer_meta['margin'] ) ) {
	n7_golf_club_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( n7_golf_club_prepare_css_value( $n7_golf_club_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $n7_golf_club_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $n7_golf_club_footer_id ) ) ); ?>
						<?php
						$n7_golf_club_footer_scheme = n7_golf_club_get_theme_option( 'footer_scheme' );
						if ( ! empty( $n7_golf_club_footer_scheme ) && ! n7_golf_club_is_inherit( $n7_golf_club_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $n7_golf_club_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'n7_golf_club_action_show_layout', $n7_golf_club_footer_id );
	?>
</footer><!-- /.footer_wrap -->
