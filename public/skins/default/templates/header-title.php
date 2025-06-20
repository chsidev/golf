<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0
 */

// Page (category, tag, archive, author) title

if ( n7_golf_club_need_page_title() ) {
	n7_golf_club_sc_layouts_showed( 'title', true );
	n7_golf_club_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								n7_golf_club_show_post_meta(
									apply_filters(
										'n7_golf_club_filter_post_meta_args', array(
											'components' => join( ',', n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', n7_golf_club_array_get_keys_by_value( n7_golf_club_get_theme_option( 'counters' ) ) ),
											'seo'        => n7_golf_club_is_on( n7_golf_club_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$n7_golf_club_blog_title           = n7_golf_club_get_blog_title();
							$n7_golf_club_blog_title_text      = '';
							$n7_golf_club_blog_title_class     = '';
							$n7_golf_club_blog_title_link      = '';
							$n7_golf_club_blog_title_link_text = '';
							if ( is_array( $n7_golf_club_blog_title ) ) {
								$n7_golf_club_blog_title_text      = $n7_golf_club_blog_title['text'];
								$n7_golf_club_blog_title_class     = ! empty( $n7_golf_club_blog_title['class'] ) ? ' ' . $n7_golf_club_blog_title['class'] : '';
								$n7_golf_club_blog_title_link      = ! empty( $n7_golf_club_blog_title['link'] ) ? $n7_golf_club_blog_title['link'] : '';
								$n7_golf_club_blog_title_link_text = ! empty( $n7_golf_club_blog_title['link_text'] ) ? $n7_golf_club_blog_title['link_text'] : '';
							} else {
								$n7_golf_club_blog_title_text = $n7_golf_club_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $n7_golf_club_blog_title_class ); ?>">
								<?php
								$n7_golf_club_top_icon = n7_golf_club_get_term_image_small();
								if ( ! empty( $n7_golf_club_top_icon ) ) {
									$n7_golf_club_attr = n7_golf_club_getimagesize( $n7_golf_club_top_icon );
									?>
									<img src="<?php echo esc_url( $n7_golf_club_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'n7-golf-club' ); ?>"
										<?php
										if ( ! empty( $n7_golf_club_attr[3] ) ) {
											n7_golf_club_show_layout( $n7_golf_club_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $n7_golf_club_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $n7_golf_club_blog_title_link ) && ! empty( $n7_golf_club_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $n7_golf_club_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $n7_golf_club_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'n7_golf_club_action_breadcrumbs' );
						$n7_golf_club_breadcrumbs = ob_get_contents();
						ob_end_clean();
						n7_golf_club_show_layout( $n7_golf_club_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
