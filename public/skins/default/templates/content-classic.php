<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

$n7_golf_club_template_args = get_query_var( 'n7_golf_club_template_args' );

if ( is_array( $n7_golf_club_template_args ) ) {
	$n7_golf_club_columns    = empty( $n7_golf_club_template_args['columns'] ) ? 2 : max( 1, $n7_golf_club_template_args['columns'] );
	$n7_golf_club_blog_style = array( $n7_golf_club_template_args['type'], $n7_golf_club_columns );
    $n7_golf_club_columns_class = n7_golf_club_get_column_class( 1, $n7_golf_club_columns, ! empty( $n7_golf_club_template_args['columns_tablet']) ? $n7_golf_club_template_args['columns_tablet'] : '', ! empty($n7_golf_club_template_args['columns_mobile']) ? $n7_golf_club_template_args['columns_mobile'] : '' );
} else {
	$n7_golf_club_blog_style = explode( '_', n7_golf_club_get_theme_option( 'blog_style' ) );
	$n7_golf_club_columns    = empty( $n7_golf_club_blog_style[1] ) ? 2 : max( 1, $n7_golf_club_blog_style[1] );
    $n7_golf_club_columns_class = n7_golf_club_get_column_class( 1, $n7_golf_club_columns );
}
$n7_golf_club_expanded   = ! n7_golf_club_sidebar_present() && n7_golf_club_get_theme_option( 'expand_content' ) == 'expand';

$n7_golf_club_post_format = get_post_format();
$n7_golf_club_post_format = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );

?><div class="<?php
	if ( ! empty( $n7_golf_club_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( n7_golf_club_is_blog_style_use_masonry( $n7_golf_club_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $n7_golf_club_columns ) : esc_attr( $n7_golf_club_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $n7_golf_club_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $n7_golf_club_columns )
				. ' post_layout_' . esc_attr( $n7_golf_club_blog_style[0] )
				. ' post_layout_' . esc_attr( $n7_golf_club_blog_style[0] ) . '_' . esc_attr( $n7_golf_club_columns )
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

	// Featured image
	$n7_golf_club_hover      = ! empty( $n7_golf_club_template_args['hover'] ) && ! n7_golf_club_is_inherit( $n7_golf_club_template_args['hover'] )
							? $n7_golf_club_template_args['hover']
							: n7_golf_club_get_theme_option( 'image_hover' );

	$n7_golf_club_components = ! empty( $n7_golf_club_template_args['meta_parts'] )
							? ( is_array( $n7_golf_club_template_args['meta_parts'] )
								? $n7_golf_club_template_args['meta_parts']
								: explode( ',', $n7_golf_club_template_args['meta_parts'] )
								)
							: n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'meta_parts' ) );

	n7_golf_club_show_post_featured( apply_filters( 'n7_golf_club_filter_args_featured',
		array(
			'thumb_size' => ! empty( $n7_golf_club_template_args['thumb_size'] )
				? $n7_golf_club_template_args['thumb_size']
				: n7_golf_club_get_thumb_size(
				'classic' == $n7_golf_club_blog_style[0]
						? ( strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $n7_golf_club_columns > 2 ? 'big' : 'huge' )
								: ( $n7_golf_club_columns > 2
									? ( $n7_golf_club_expanded ? 'square' : 'square' )
									: ($n7_golf_club_columns > 1 ? 'square' : ( $n7_golf_club_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $n7_golf_club_columns > 2 ? 'masonry-big' : 'full' )
								: ($n7_golf_club_columns === 1 ? ( $n7_golf_club_expanded ? 'huge' : 'big' ) : ( $n7_golf_club_columns <= 2 && $n7_golf_club_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $n7_golf_club_hover,
			'meta_parts' => $n7_golf_club_components,
			'no_links'   => ! empty( $n7_golf_club_template_args['no_links'] ),
        ),
        'content-classic',
        $n7_golf_club_template_args
    ) );

	// Title and post meta
	$n7_golf_club_show_title = get_the_title() != '';
	$n7_golf_club_show_meta  = count( $n7_golf_club_components ) > 0 && ! in_array( $n7_golf_club_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $n7_golf_club_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'n7_golf_club_filter_show_blog_meta', $n7_golf_club_show_meta, $n7_golf_club_components, 'classic' ) ) {
				if ( count( $n7_golf_club_components ) > 0 ) {
					do_action( 'n7_golf_club_action_before_post_meta' );
					n7_golf_club_show_post_meta(
						apply_filters(
							'n7_golf_club_filter_post_meta_args', array(
							'components' => join( ',', $n7_golf_club_components ),
							'seo'        => false,
							'echo'       => true,
						), $n7_golf_club_blog_style[0], $n7_golf_club_columns
						)
					);
					do_action( 'n7_golf_club_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'n7_golf_club_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'n7_golf_club_action_before_post_title' );
				if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'n7_golf_club_action_after_post_title' );
			}

			if( !in_array( $n7_golf_club_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'n7_golf_club_filter_show_blog_readmore', ! $n7_golf_club_show_title || ! empty( $n7_golf_club_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
						do_action( 'n7_golf_club_action_before_post_readmore' );
						n7_golf_club_show_post_more_link( $n7_golf_club_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'n7_golf_club_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $n7_golf_club_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('n7_golf_club_filter_show_blog_excerpt', empty($n7_golf_club_template_args['hide_excerpt']) && n7_golf_club_get_theme_option('excerpt_length') > 0, 'classic')) {
			n7_golf_club_show_post_content($n7_golf_club_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $n7_golf_club_template_args['more_button'] )) {
			if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
				do_action( 'n7_golf_club_action_before_post_readmore' );
				n7_golf_club_show_post_more_link( $n7_golf_club_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'n7_golf_club_action_after_post_readmore' );
			}
		}
		$n7_golf_club_content = ob_get_contents();
		ob_end_clean();
		n7_golf_club_show_layout($n7_golf_club_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
