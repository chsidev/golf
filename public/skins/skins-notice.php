<?php
/**
 * The template to display Admin notices
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.0.64
 */

$n7_golf_club_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$n7_golf_club_skins_args = get_query_var( 'n7_golf_club_skins_notice_args' );
?>
<div class="n7_golf_club_admin_notice n7_golf_club_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins available', 'n7-golf-club' ); ?>
	</h3>
	<?php

	// Description
	$n7_golf_club_total      = $n7_golf_club_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$n7_golf_club_skins_msg  = $n7_golf_club_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $n7_golf_club_total, 'n7-golf-club' ), $n7_golf_club_total ) . '</strong>'
							: '';
	$n7_golf_club_total      = $n7_golf_club_skins_args['free'];
	$n7_golf_club_skins_msg .= $n7_golf_club_total > 0
							? ( ! empty( $n7_golf_club_skins_msg ) ? ' ' . esc_html__( 'and', 'n7-golf-club' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $n7_golf_club_total, 'n7-golf-club' ), $n7_golf_club_total ) . '</strong>'
							: '';
	$n7_golf_club_total      = $n7_golf_club_skins_args['pay'];
	$n7_golf_club_skins_msg .= $n7_golf_club_skins_args['pay'] > 0
							? ( ! empty( $n7_golf_club_skins_msg ) ? ' ' . esc_html__( 'and', 'n7-golf-club' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $n7_golf_club_total, 'n7-golf-club' ), $n7_golf_club_total ) . '</strong>'
							: '';
	?>
	<div class="n7_golf_club_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'n7-golf-club' ), $n7_golf_club_skins_msg ) );
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
