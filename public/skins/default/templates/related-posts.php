<?php
/**
 * The default template to displaying related posts
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.54
 */

$n7_golf_club_link        = get_permalink();
$n7_golf_club_post_format = get_post_format();
$n7_golf_club_post_format = empty( $n7_golf_club_post_format ) ? 'standard' : str_replace( 'post-format-', '', $n7_golf_club_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $n7_golf_club_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	n7_golf_club_show_post_featured(
		array(
			'thumb_size' => apply_filters( 'n7_golf_club_filter_related_thumb_size', n7_golf_club_get_thumb_size( (int) n7_golf_club_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $n7_golf_club_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'n7-golf-club' );
			} else {
				the_title();
			}
		?></a></h6>
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<span class="post_date"><a href="<?php echo esc_url( $n7_golf_club_link ); ?>"><?php echo wp_kses_data( n7_golf_club_get_date() ); ?></a></span>
			<?php
		}
		?>
	</div>
</div>
# Change 1 on 2025-05-24
