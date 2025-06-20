<?php
/**
 * The template to display Admin notices
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.98.0
 */

$n7_golf_club_skins_url   = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$n7_golf_club_active_skin = n7_golf_club_skins_get_active_skin_name();
?>
<div class="n7_golf_club_admin_notice n7_golf_club_skins_notice notice notice-error">
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
		<?php esc_html_e( 'Active skin is missing!', 'n7-golf-club' ); ?>
	</h3>
	<div class="n7_golf_club_notice_text">
		<p>
			<?php
			// Translators: Add a current skin name to the message
			echo wp_kses_data( sprintf( __( "Your active skin <b>'%s'</b> is missing. Usually this happens when the theme is updated directly through the server or FTP.", 'n7-golf-club' ), ucfirst( $n7_golf_club_active_skin ) ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "Please use only <b>'ThemeREX Updater v.1.6.0+'</b> plugin for your future updates.", 'n7-golf-club' ) );
			?>
		</p>
		<p>
			<?php
			echo wp_kses_data( __( "But no worries! You can re-download the skin via 'Skins Manager' ( Theme Panel - Theme Dashboard - Skins ).", 'n7-golf-club' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="n7_golf_club_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $n7_golf_club_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'n7-golf-club' );
			?>
		</a>
	</div>
</div>
