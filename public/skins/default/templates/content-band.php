<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.71.0
 */

$n7_golf_club_template_args = get_query_var( 'n7_golf_club_template_args' );

$n7_golf_club_columns       = 1;

$n7_golf_club_expanded      = ! n7_golf_club_sidebar_present() && n7_golf_club_get_theme_option( 'expand_content' ) == 'expand';

$n7_golf_club_post_format   = get_post_format();
$n7_golf_club_post_format   = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );

if ( is_array( $n7_golf_club_template_args ) ) {
	$n7_golf_club_columns    = empty( $n7_golf_club_template_args['columns'] ) ? 1 : max( 1, $n7_golf_club_template_args['columns'] );
	$n7_golf_club_blog_style = array( $n7_golf_club_template_args['type'], $n7_golf_club_columns );
	if ( ! empty( $n7_golf_club_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $n7_golf_club_columns > 1 ) {
	    $n7_golf_club_columns_class = n7_golf_club_get_column_class( 1, $n7_golf_club_columns, ! empty( $n7_golf_club_template_args['columns_tablet']) ? $n7_golf_club_template_args['columns_tablet'] : '', ! empty($n7_golf_club_template_args['columns_mobile']) ? $n7_golf_club_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $n7_golf_club_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $n7_golf_club_post_format ) );
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
								: array_map( 'trim', explode( ',', $n7_golf_club_template_args['meta_parts'] ) )
								)
							: n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'meta_parts' ) );
	n7_golf_club_show_post_featured( apply_filters( 'n7_golf_club_filter_args_featured',
		array(
			'no_links'   => ! empty( $n7_golf_club_template_args['no_links'] ),
			'hover'      => $n7_golf_club_hover,
			'meta_parts' => $n7_golf_club_components,
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $n7_golf_club_template_args['thumb_size'] )
								? $n7_golf_club_template_args['thumb_size']
								: n7_golf_club_get_thumb_size( 
								in_array( $n7_golf_club_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $n7_golf_club_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$n7_golf_club_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$n7_golf_club_show_title = get_the_title() != '';
		$n7_golf_club_show_meta  = count( $n7_golf_club_components ) > 0 && ! in_array( $n7_golf_club_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $n7_golf_club_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'n7_golf_club_filter_show_blog_categories', $n7_golf_club_show_meta && in_array( 'categories', $n7_golf_club_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'n7_golf_club_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						n7_golf_club_show_post_meta( apply_filters(
															'n7_golf_club_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $n7_golf_club_hover, 1
															)
											);
						?>
					</div>
					<?php
					$n7_golf_club_components = n7_golf_club_array_delete_by_value( $n7_golf_club_components, 'categories' );
					do_action( 'n7_golf_club_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'n7_golf_club_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'n7_golf_club_action_before_post_title' );
					if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'n7_golf_club_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $n7_golf_club_template_args['excerpt_length'] ) && ! in_array( $n7_golf_club_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$n7_golf_club_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'n7_golf_club_filter_show_blog_excerpt', empty( $n7_golf_club_template_args['hide_excerpt'] ) && n7_golf_club_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				n7_golf_club_show_post_content( $n7_golf_club_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'n7_golf_club_filter_show_blog_meta', $n7_golf_club_show_meta, $n7_golf_club_components, 'band' ) ) {
			if ( count( $n7_golf_club_components ) > 0 ) {
				do_action( 'n7_golf_club_action_before_post_meta' );
				n7_golf_club_show_post_meta(
					apply_filters(
						'n7_golf_club_filter_post_meta_args', array(
							'components' => join( ',', $n7_golf_club_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'n7_golf_club_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'n7_golf_club_filter_show_blog_readmore', ! $n7_golf_club_show_title || ! empty( $n7_golf_club_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
				do_action( 'n7_golf_club_action_before_post_readmore' );
				n7_golf_club_show_post_more_link( $n7_golf_club_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'n7_golf_club_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $n7_golf_club_template_args ) ) {
	if ( ! empty( $n7_golf_club_template_args['slider'] ) || $n7_golf_club_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
