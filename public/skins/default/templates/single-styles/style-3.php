<?php
/**
 * The "Style 3" template to display the post header of the single post or attachment:
 * featured image and title placed in the post header
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.75.0
 */

if ( apply_filters( 'n7_golf_club_filter_single_post_header', is_singular( 'post' ) || is_singular( 'attachment' ) ) ) {
    $n7_golf_club_post_format = str_replace( 'post-format-', '', get_post_format() );
    $post_meta = in_array( $n7_golf_club_post_format, array( 'video' ) ) ? get_post_meta( get_the_ID(), 'trx_addons_options', true ) : false;
    $video_autoplay = ! empty( $post_meta['video_autoplay'] )
        && ! empty( $post_meta['video_list'] )
        && is_array( $post_meta['video_list'] )
        && count( $post_meta['video_list'] ) == 1
        && ( ! empty( $post_meta['video_list'][0]['video_url'] ) || ! empty( $post_meta['video_list'][0]['video_embed'] ) );

	// Featured image
    ob_start();
	n7_golf_club_show_post_featured_image( array(
		'thumb_bg'  => true,
		'popup'     => true,
        'class_avg' => in_array( $n7_golf_club_post_format, array( 'video' ) )
            ? ( ! $video_autoplay
                ? 'content_wrap'
                : 'with_thumb post_featured_bg with_video with_video_autoplay'
            )
            : '',
        'autoplay'  => $video_autoplay,
        'post_meta' => $post_meta
	) );
	$n7_golf_club_post_header = ob_get_contents();
	ob_end_clean();
	$n7_golf_club_with_featured_image = n7_golf_club_is_with_featured_image( $n7_golf_club_post_header );
	// Post title and meta
	ob_start();
	n7_golf_club_show_post_title_and_meta( array(
										'content_wrap'  => true,
										'share_type'    => 'list',
										'author_avatar' => true,
										'show_labels'   => true,
										'add_spaces'    => true,
										)
									);
	$n7_golf_club_post_header .= ob_get_contents();
	ob_end_clean();

	if ( strpos( $n7_golf_club_post_header, 'post_featured' ) !== false
		|| strpos( $n7_golf_club_post_header, 'post_title' ) !== false
		|| strpos( $n7_golf_club_post_header, 'post_meta' ) !== false
	) {
		?>
		<div class="post_header_wrap post_header_wrap_in_header post_header_wrap_style_<?php
			echo esc_attr( n7_golf_club_get_theme_option( 'single_style' ) );
			if ( $n7_golf_club_with_featured_image ) {
				echo ' with_featured_image';
			}
		?>">
			<?php
			do_action( 'n7_golf_club_action_before_post_header' );
			n7_golf_club_show_layout( $n7_golf_club_post_header );
			do_action( 'n7_golf_club_action_after_post_header' );
			?>
		</div>
		<?php
	}
}
