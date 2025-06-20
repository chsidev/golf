<?php
/**
 * The template to display the user's avatar, bio and socials on the Author page
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.71.0
 */
?>

<div class="author_page author vcard" itemprop="author" itemscope="itemscope" itemtype="<?php echo esc_attr( n7_golf_club_get_protocol( true ) ); ?>//schema.org/Person">

	<div class="author_avatar" itemprop="image">
		<?php
		$n7_golf_club_mult = n7_golf_club_get_retina_multiplier();
		echo get_avatar( get_the_author_meta( 'user_email' ), 120 * $n7_golf_club_mult );
		?>
	</div><!-- .author_avatar -->

	<h4 class="author_title" itemprop="name"><span class="fn"><?php the_author(); ?></span></h4>

	<?php
	$n7_golf_club_author_description = get_the_author_meta( 'description' );
	if ( ! empty( $n7_golf_club_author_description ) ) {
		?>
		<div class="author_bio" itemprop="description"><?php echo wp_kses( wpautop( $n7_golf_club_author_description ), 'n7_golf_club_kses_content' ); ?></div>
		<?php
	}
	?>

	<div class="author_details">
		<span class="author_posts_total">
			<?php
			$n7_golf_club_posts_total = count_user_posts( get_the_author_meta('ID'), 'post' );
			if ( $n7_golf_club_posts_total > 0 ) {
				// Translators: Add the author's posts number to the message
				echo wp_kses( sprintf( _n( '%s article published', '%s articles published', $n7_golf_club_posts_total, 'n7-golf-club' ),
										'<span class="author_posts_total_value">' . number_format_i18n( $n7_golf_club_posts_total ) . '</span>'
								 		),
							'n7_golf_club_kses_content'
							);
			} else {
				esc_html_e( 'No posts published.', 'n7-golf-club' );
			}
			?>
		</span><?php
			ob_start();
			do_action( 'n7_golf_club_action_user_meta', 'author-page' );
			$n7_golf_club_socials = ob_get_contents();
			ob_end_clean();
			n7_golf_club_show_layout( $n7_golf_club_socials,
				'<span class="author_socials"><span class="author_socials_caption">' . esc_html__( 'Follow:', 'n7-golf-club' ) . '</span>',
				'</span>'
			);
		?>
	</div><!-- .author_details -->

</div><!-- .author_page -->
