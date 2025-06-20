<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

$n7_golf_club_template_args = get_query_var( 'n7_golf_club_template_args' );
$n7_golf_club_columns = 1;
if ( is_array( $n7_golf_club_template_args ) ) {
	$n7_golf_club_columns    = empty( $n7_golf_club_template_args['columns'] ) ? 1 : max( 1, $n7_golf_club_template_args['columns'] );
	$n7_golf_club_blog_style = array( $n7_golf_club_template_args['type'], $n7_golf_club_columns );
	if ( ! empty( $n7_golf_club_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $n7_golf_club_columns > 1 ) {
	    $n7_golf_club_columns_class = n7_golf_club_get_column_class( 1, $n7_golf_club_columns, ! empty( $n7_golf_club_template_args['columns_tablet']) ? $n7_golf_club_template_args['columns_tablet'] : '', ! empty($n7_golf_club_template_args['columns_mobile']) ? $n7_golf_club_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $n7_golf_club_columns_class ); ?>">
		<?php
	}
}
$n7_golf_club_expanded    = ! n7_golf_club_sidebar_present() && n7_golf_club_get_theme_option( 'expand_content' ) == 'expand';
$n7_golf_club_post_format = get_post_format();
$n7_golf_club_post_format = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $n7_golf_club_post_format ) );
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
			'thumb_size' => ! empty( $n7_golf_club_template_args['thumb_size'] )
							? $n7_golf_club_template_args['thumb_size']
							: n7_golf_club_get_thumb_size( strpos( n7_golf_club_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $n7_golf_club_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$n7_golf_club_template_args
	) );

	// Title and post meta
	$n7_golf_club_show_title = get_the_title() != '';
	$n7_golf_club_show_meta  = count( $n7_golf_club_components ) > 0 && ! in_array( $n7_golf_club_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $n7_golf_club_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'n7_golf_club_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'n7_golf_club_action_before_post_title' );
				if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'n7_golf_club_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'n7_golf_club_filter_show_blog_excerpt', empty( $n7_golf_club_template_args['hide_excerpt'] ) && n7_golf_club_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'n7_golf_club_filter_show_blog_meta', $n7_golf_club_show_meta, $n7_golf_club_components, 'excerpt' ) ) {
				if ( count( $n7_golf_club_components ) > 0 ) {
					do_action( 'n7_golf_club_action_before_post_meta' );
					n7_golf_club_show_post_meta(
						apply_filters(
							'n7_golf_club_filter_post_meta_args', array(
								'components' => join( ',', $n7_golf_club_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'n7_golf_club_action_after_post_meta' );
				}
			}

			if ( n7_golf_club_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'n7_golf_club_action_before_full_post_content' );
					the_content( '' );
					do_action( 'n7_golf_club_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'n7-golf-club' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'n7-golf-club' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				n7_golf_club_show_post_content( $n7_golf_club_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'n7_golf_club_filter_show_blog_readmore',  ! isset( $n7_golf_club_template_args['more_button'] ) || ! empty( $n7_golf_club_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $n7_golf_club_template_args['no_links'] ) ) {
					do_action( 'n7_golf_club_action_before_post_readmore' );
					if ( n7_golf_club_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						n7_golf_club_show_post_more_link( $n7_golf_club_template_args, '<p>', '</p>' );
					} else {
						n7_golf_club_show_post_comments_link( $n7_golf_club_template_args, '<p>', '</p>' );
					}
					do_action( 'n7_golf_club_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $n7_golf_club_template_args ) ) {
	if ( ! empty( $n7_golf_club_template_args['slider'] ) || $n7_golf_club_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
