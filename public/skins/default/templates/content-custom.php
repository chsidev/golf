<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.50
 */

$n7_golf_club_template_args = get_query_var( 'n7_golf_club_template_args' );
if ( is_array( $n7_golf_club_template_args ) ) {
	$n7_golf_club_columns    = empty( $n7_golf_club_template_args['columns'] ) ? 2 : max( 1, $n7_golf_club_template_args['columns'] );
	$n7_golf_club_blog_style = array( $n7_golf_club_template_args['type'], $n7_golf_club_columns );
} else {
	$n7_golf_club_blog_style = explode( '_', n7_golf_club_get_theme_option( 'blog_style' ) );
	$n7_golf_club_columns    = empty( $n7_golf_club_blog_style[1] ) ? 2 : max( 1, $n7_golf_club_blog_style[1] );
}
$n7_golf_club_blog_id       = n7_golf_club_get_custom_blog_id( join( '_', $n7_golf_club_blog_style ) );
$n7_golf_club_blog_style[0] = str_replace( 'blog-custom-', '', $n7_golf_club_blog_style[0] );
$n7_golf_club_expanded      = ! n7_golf_club_sidebar_present() && n7_golf_club_get_theme_option( 'expand_content' ) == 'expand';
$n7_golf_club_components    = ! empty( $n7_golf_club_template_args['meta_parts'] )
							? ( is_array( $n7_golf_club_template_args['meta_parts'] )
								? join( ',', $n7_golf_club_template_args['meta_parts'] )
								: $n7_golf_club_template_args['meta_parts']
								)
							: n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'meta_parts' ) );
$n7_golf_club_post_format   = get_post_format();
$n7_golf_club_post_format   = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );

$n7_golf_club_blog_meta     = n7_golf_club_get_custom_layout_meta( $n7_golf_club_blog_id );
$n7_golf_club_custom_style  = ! empty( $n7_golf_club_blog_meta['scripts_required'] ) ? $n7_golf_club_blog_meta['scripts_required'] : 'none';

if ( ! empty( $n7_golf_club_template_args['slider'] ) || $n7_golf_club_columns > 1 || ! n7_golf_club_is_off( $n7_golf_club_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $n7_golf_club_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( n7_golf_club_is_off( $n7_golf_club_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $n7_golf_club_custom_style ) ) . "-1_{$n7_golf_club_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $n7_golf_club_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $n7_golf_club_columns )
					. ' post_layout_' . esc_attr( $n7_golf_club_blog_style[0] )
					. ' post_layout_' . esc_attr( $n7_golf_club_blog_style[0] ) . '_' . esc_attr( $n7_golf_club_columns )
					. ( ! n7_golf_club_is_off( $n7_golf_club_custom_style )
						? ' post_layout_' . esc_attr( $n7_golf_club_custom_style )
							. ' post_layout_' . esc_attr( $n7_golf_club_custom_style ) . '_' . esc_attr( $n7_golf_club_columns )
						: ''
						)
		);
	n7_golf_club_add_blog_animation( $n7_golf_club_template_args );
	?>
>
	<?php
	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}
	// Custom layout
	do_action( 'n7_golf_club_action_show_layout', $n7_golf_club_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $n7_golf_club_template_args['slider'] ) || $n7_golf_club_columns > 1 || ! n7_golf_club_is_off( $n7_golf_club_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
