<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'n7_golf_club_vc_get_css' ) ) {
	add_filter( 'n7_golf_club_filter_get_css', 'n7_golf_club_vc_get_css', 10, 2 );
	function n7_golf_club_vc_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS
.vc_tta.vc_tta-accordion .vc_tta-panel-title .vc_tta-title-text {
	{$fonts['p_font-family']}
}
.vc_progress_bar.vc_progress_bar_narrow .vc_single_bar .vc_label .vc_label_units {
	{$fonts['info_font-family']}
}

CSS;
		}

		return $css;
	}
}

