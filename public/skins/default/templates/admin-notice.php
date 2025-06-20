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
<div class="n7_golf_club_admin_notice n7_golf_club_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
	<h3 class="n7_golf_club_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'n7-golf-club' ),
				$n7_golf_club_theme_obj->get( 'Name' ) . ( N7_GOLF_CLUB_THEME_FREE ? ' ' . __( 'Free', 'n7-golf-club' ) : '' ),
				$n7_golf_club_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="n7_golf_club_notice_text">
		<p class="n7_golf_club_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $n7_golf_club_theme_obj->description ) );
			?>
		</p>
		<p class="n7_golf_club_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'n7-golf-club' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="n7_golf_club_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=n7_golf_club_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'n7-golf-club' );
			?>
		</a>
	</div>
</div>
