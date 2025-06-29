<?php
/**
 * The template to display the widgets area in the header
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

// Header sidebar
$n7_golf_club_header_name    = n7_golf_club_get_theme_option( 'header_widgets' );
$n7_golf_club_header_present = ! n7_golf_club_is_off( $n7_golf_club_header_name ) && is_active_sidebar( $n7_golf_club_header_name );
if ( $n7_golf_club_header_present ) {
	n7_golf_club_storage_set( 'current_sidebar', 'header' );
	$n7_golf_club_header_wide = n7_golf_club_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $n7_golf_club_header_name ) ) {
		dynamic_sidebar( $n7_golf_club_header_name );
	}
	$n7_golf_club_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $n7_golf_club_widgets_output ) ) {
		$n7_golf_club_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $n7_golf_club_widgets_output );
		$n7_golf_club_need_columns   = strpos( $n7_golf_club_widgets_output, 'columns_wrap' ) === false;
		if ( $n7_golf_club_need_columns ) {
			$n7_golf_club_columns = max( 0, (int) n7_golf_club_get_theme_option( 'header_columns' ) );
			if ( 0 == $n7_golf_club_columns ) {
				$n7_golf_club_columns = min( 6, max( 1, n7_golf_club_tags_count( $n7_golf_club_widgets_output, 'aside' ) ) );
			}
			if ( $n7_golf_club_columns > 1 ) {
				$n7_golf_club_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $n7_golf_club_columns ) . ' widget', $n7_golf_club_widgets_output );
			} else {
				$n7_golf_club_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $n7_golf_club_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'n7_golf_club_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $n7_golf_club_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $n7_golf_club_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'n7_golf_club_action_before_sidebar', 'header' );
				n7_golf_club_show_layout( $n7_golf_club_widgets_output );
				do_action( 'n7_golf_club_action_after_sidebar', 'header' );
				if ( $n7_golf_club_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $n7_golf_club_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'n7_golf_club_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
