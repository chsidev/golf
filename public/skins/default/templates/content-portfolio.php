<?php
/**
 * The Portfolio template to display the content
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

$n7_golf_club_post_format = get_post_format();
$n7_golf_club_post_format = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );

?><div class="
<?php
if ( ! empty( $n7_golf_club_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( n7_golf_club_is_blog_style_use_masonry( $n7_golf_club_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $n7_golf_club_columns ) : esc_attr( $n7_golf_club_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $n7_golf_club_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $n7_golf_club_columns )
		. ( 'portfolio' != $n7_golf_club_blog_style[0] ? ' ' . esc_attr( $n7_golf_club_blog_style[0] )  . '_' . esc_attr( $n7_golf_club_columns ) : '' )
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

	$n7_golf_club_hover   = ! empty( $n7_golf_club_template_args['hover'] ) && ! n7_golf_club_is_inherit( $n7_golf_club_template_args['hover'] )
								? $n7_golf_club_template_args['hover']
								: n7_golf_club_get_theme_option( 'image_hover' );

	if ( 'dots' == $n7_golf_club_hover ) {
		$n7_golf_club_post_link = empty( $n7_golf_club_template_args['no_links'] )
								? ( ! empty( $n7_golf_club_template_args['link'] )
									? $n7_golf_club_template_args['link']
									: get_permalink()
									)
								: '';
		$n7_golf_club_target    = ! empty( $n7_golf_club_post_link ) && false === strpos( $n7_golf_club_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$n7_golf_club_components = ! empty( $n7_golf_club_template_args['meta_parts'] )
							? ( is_array( $n7_golf_club_template_args['meta_parts'] )
								? $n7_golf_club_template_args['meta_parts']
								: explode( ',', $n7_golf_club_template_args['meta_parts'] )
								)
							: n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'meta_parts' ) );

	// Featured image
	n7_golf_club_show_post_featured( apply_filters( 'n7_golf_club_filter_args_featured',
        array(
			'hover'         => $n7_golf_club_hover,
			'no_links'      => ! empty( $n7_golf_club_template_args['no_links'] ),
			'thumb_size'    => ! empty( $n7_golf_club_template_args['thumb_size'] )
								? $n7_golf_club_template_args['thumb_size']
								: n7_golf_club_get_thumb_size(
									n7_golf_club_is_blog_style_use_masonry( $n7_golf_club_blog_style[0] )
										? (	strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false || $n7_golf_club_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false || $n7_golf_club_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => n7_golf_club_is_blog_style_use_masonry( $n7_golf_club_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $n7_golf_club_components,
			'class'         => 'dots' == $n7_golf_club_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $n7_golf_club_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $n7_golf_club_post_link )
												? '<a href="' . esc_url( $n7_golf_club_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $n7_golf_club_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $n7_golf_club_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $n7_golf_club_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!