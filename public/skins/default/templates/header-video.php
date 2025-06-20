<?php
/**
 * The template to display the background video in the header
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.14
 */
$n7_golf_club_header_video = n7_golf_club_get_header_video();
$n7_golf_club_embed_video  = '';
if ( ! empty( $n7_golf_club_header_video ) && ! n7_golf_club_is_from_uploads( $n7_golf_club_header_video ) ) {
	if ( n7_golf_club_is_youtube_url( $n7_golf_club_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $n7_golf_club_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php n7_golf_club_show_layout( n7_golf_club_get_embed_video( $n7_golf_club_header_video ) ); ?></div>
		<?php
	}
}
