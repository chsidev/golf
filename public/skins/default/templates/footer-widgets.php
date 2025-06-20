<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.10
 */

// Footer sidebar
$n7_golf_club_footer_name    = n7_golf_club_get_theme_option( 'footer_widgets' );
$n7_golf_club_footer_present = ! n7_golf_club_is_off( $n7_golf_club_footer_name ) && is_active_sidebar( $n7_golf_club_footer_name );
if ( $n7_golf_club_footer_present ) {
	n7_golf_club_storage_set( 'current_sidebar', 'footer' );
	$n7_golf_club_footer_wide = n7_golf_club_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $n7_golf_club_footer_name ) ) {
		dynamic_sidebar( $n7_golf_club_footer_name );
	}
	$n7_golf_club_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $n7_golf_club_out ) ) {
		$n7_golf_club_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $n7_golf_club_out );
		$n7_golf_club_need_columns = true;   //or check: strpos($n7_golf_club_out, 'columns_wrap')===false;
		if ( $n7_golf_club_need_columns ) {
			$n7_golf_club_columns = max( 0, (int) n7_golf_club_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $n7_golf_club_columns ) {
				$n7_golf_club_columns = min( 4, max( 1, n7_golf_club_tags_count( $n7_golf_club_out, 'aside' ) ) );
			}
			if ( $n7_golf_club_columns > 1 ) {
				$n7_golf_club_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $n7_golf_club_columns ) . ' widget', $n7_golf_club_out );
			} else {
				$n7_golf_club_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $n7_golf_club_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'n7_golf_club_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $n7_golf_club_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $n7_golf_club_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'n7_golf_club_action_before_sidebar', 'footer' );
				n7_golf_club_show_layout( $n7_golf_club_out );
				do_action( 'n7_golf_club_action_after_sidebar', 'footer' );
				if ( $n7_golf_club_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $n7_golf_club_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'n7_golf_club_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
