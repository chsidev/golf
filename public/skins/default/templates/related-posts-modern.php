<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

$n7_golf_club_link        = get_permalink();
$n7_golf_club_post_format = get_post_format();
$n7_golf_club_post_format = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $n7_golf_club_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	n7_golf_club_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'n7_golf_club_filter_related_thumb_size', n7_golf_club_get_thumb_size( (int) n7_golf_club_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( n7_golf_club_get_post_categories( '' ), 'n7_golf_club_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $n7_golf_club_link ) . '">'
										. wp_kses_data( '' == get_the_title() ? esc_html__( '- No title -', 'n7-golf-club' ) : get_the_title() )
									. '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $n7_golf_club_link ) . '" class="post_meta_item post_date">' . wp_kses_data( n7_golf_club_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
