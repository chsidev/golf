<?php
/**
 * The template to display Admin notices
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.1
 */

$n7_golf_club_theme_slug = get_option( 'template' );
$n7_golf_club_theme_obj  = wp_get_theme( $n7_golf_club_theme_slug );

?>
<div class="n7_golf_club_admin_notice n7_golf_club_rate_notice notice notice-info is-dismissible" data-notice="rate">
	<?php
	// Theme image
	$n7_golf_club_theme_img = n7_golf_club_get_file_url( 'screenshot.jpg' );
	if ( '' != $n7_golf_club_theme_img ) {
		?>
		<div class="n7_golf_club_notice_image"><img src="<?php echo esc_url( $n7_golf_club_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'n7-golf-club' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="n7_golf_club_notice_title"><a href="<?php echo esc_url( n7_golf_club_storage_get( 'theme_rate_url' ) ); ?>" target="_blank">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Rate our theme "%s", please', 'n7-golf-club' ),
				$n7_golf_club_theme_obj->get( 'Name' ) . ( N7_GOLF_CLUB_THEME_FREE ? ' ' . __( 'Free', 'n7-golf-club' ) : '' )
			)
		);
		?>
	</a></h3>
	<?php

	// Description
	?>
	<div class="n7_golf_club_notice_text">
		<p><?php echo wp_kses_data( __( "We are glad you chose our WP theme for your website. You've done well customizing your website and we hope that you've enjoyed working with our theme.", 'n7-golf-club' ) ); ?></p>
		<p><?php echo wp_kses_data( __( "It would be just awesome if you spend just a minute of your time to rate our theme or the customer service you've received from us.", 'n7-golf-club' ) ); ?></p>
		<p class="n7_golf_club_notice_text_info"><?php echo wp_kses_data( __( '* We love receiving your reviews! Every time you leave a review, our CEO Henry Rise gives $5 to homeless dog shelter! Save the planet with us!', 'n7-golf-club' ) ); ?></p>
	</div>
	<?php

	// Buttons
	?>
	<div class="n7_golf_club_notice_buttons">
		<?php
		// Link to the theme download page
		?>
		<a href="<?php echo esc_url( n7_golf_club_storage_get( 'theme_rate_url' ) ); ?>" class="button button-primary" target="_blank"><i class="dashicons dashicons-star-filled"></i> 
			<?php
			// Translators: Add theme name
			echo esc_html( sprintf( __( 'Rate theme %s', 'n7-golf-club' ), $n7_golf_club_theme_obj->name ) );
			?>
		</a>
		<?php
		// Link to the theme support
		?>
		<a href="<?php echo esc_url( n7_golf_club_storage_get( 'theme_support_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-sos"></i> 
			<?php
			esc_html_e( 'Support', 'n7-golf-club' );
			?>
		</a>
		<?php
		// Link to the theme documentation
		?>
		<a href="<?php echo esc_url( n7_golf_club_storage_get( 'theme_doc_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-book"></i> 
			<?php
			esc_html_e( 'Documentation', 'n7-golf-club' );
			?>
		</a>
	</div>
</div>
