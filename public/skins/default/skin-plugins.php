<?php
/**
 * Required plugins
 *
 * @package N7 GOLF CLUB
 * @since N7 GOLF CLUB 1.76.0
 */

// THEME-SUPPORTED PLUGINS
// If plugin not need - remove its settings from next array
//----------------------------------------------------------
$n7_golf_club_theme_required_plugins_groups = array(
	'core'          => esc_html__( 'Core', 'n7-golf-club' ),
	'page_builders' => esc_html__( 'Page Builders', 'n7-golf-club' ),
	'ecommerce'     => esc_html__( 'E-Commerce & Donations', 'n7-golf-club' ),
	'socials'       => esc_html__( 'Socials and Communities', 'n7-golf-club' ),
	'events'        => esc_html__( 'Events and Appointments', 'n7-golf-club' ),
	'content'       => esc_html__( 'Content', 'n7-golf-club' ),
	'other'         => esc_html__( 'Other', 'n7-golf-club' ),
);
$n7_golf_club_theme_required_plugins        = array(
	'trx_addons'                 => array(
		'title'       => esc_html__( 'ThemeREX Addons', 'n7-golf-club' ),
		'description' => esc_html__( "Will allow you to install recommended plugins, demo content, and improve the theme's functionality overall with multiple theme options", 'n7-golf-club' ),
		'required'    => true,
		'logo'        => 'trx_addons.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['core'],
	),
	'elementor'                  => array(
		'title'       => esc_html__( 'Elementor', 'n7-golf-club' ),
		'description' => esc_html__( "Is a beautiful PageBuilder, even the free version of which allows you to create great pages using a variety of modules.", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'elementor.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['page_builders'],
	),
	'gutenberg'                  => array(
		'title'       => esc_html__( 'Gutenberg', 'n7-golf-club' ),
		'description' => esc_html__( "It's a posts editor coming in place of the classic TinyMCE. Can be installed and used in parallel with Elementor", 'n7-golf-club' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'gutenberg.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['page_builders'],
	),
	'js_composer'                => array(
		'title'       => esc_html__( 'WPBakery PageBuilder', 'n7-golf-club' ),
		'description' => esc_html__( "Popular PageBuilder which allows you to create excellent pages", 'n7-golf-club' ),
		'required'    => false,
		'install'     => false,          // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'js_composer.jpg',
		'group'       => $n7_golf_club_theme_required_plugins_groups['page_builders'],
	),
	'woocommerce'                => array(
		'title'       => esc_html__( 'WooCommerce', 'n7-golf-club' ),
		'description' => esc_html__( "Connect the store to your website and start selling now", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'woocommerce.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['ecommerce'],
	),
	'elegro-payment'             => array(
		'title'       => esc_html__( 'Elegro Crypto Payment', 'n7-golf-club' ),
		'description' => esc_html__( "Extends WooCommerce Payment Gateways with an elegro Crypto Payment", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'elegro-payment.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['ecommerce'],
	),
	'instagram-feed'             => array(
		'title'       => esc_html__( 'Instagram Feed', 'n7-golf-club' ),
		'description' => esc_html__( "Displays the latest photos from your profile on Instagram", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'instagram-feed.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['socials'],
	),
	'mailchimp-for-wp'           => array(
		'title'       => esc_html__( 'MailChimp for WP', 'n7-golf-club' ),
		'description' => esc_html__( "Allows visitors to subscribe to newsletters", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'mailchimp-for-wp.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['socials'],
	),
	'booked'                     => array(
		'title'       => esc_html__( 'Booked Appointments', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => 'booked.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['events'],
	),
	'the-events-calendar'        => array(
		'title'       => esc_html__( 'The Events Calendar', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'the-events-calendar.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['events'],
	),
	'contact-form-7'             => array(
		'title'       => esc_html__( 'Contact Form 7', 'n7-golf-club' ),
		'description' => esc_html__( "CF7 allows you to create an unlimited number of contact forms", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'contact-form-7.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),

	'latepoint'                  => array(
		'title'       => esc_html__( 'LatePoint', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => n7_golf_club_get_file_url( 'plugins/latepoint/latepoint.png' ),
		'group'       => $n7_golf_club_theme_required_plugins_groups['events'],
	),
	'advanced-popups'                  => array(
		'title'       => esc_html__( 'Advanced Popups', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
		'logo'        => n7_golf_club_get_file_url( 'plugins/advanced-popups/advanced-popups.jpg' ),
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'devvn-image-hotspot'                  => array(
		'title'       => esc_html__( 'Image Hotspot by DevVN', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => n7_golf_club_get_file_url( 'plugins/devvn-image-hotspot/devvn-image-hotspot.png' ),
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'ti-woocommerce-wishlist'                  => array(
		'title'       => esc_html__( 'TI WooCommerce Wishlist', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
		'logo'        => n7_golf_club_get_file_url( 'plugins/ti-woocommerce-wishlist/ti-woocommerce-wishlist.png' ),
		'group'       => $n7_golf_club_theme_required_plugins_groups['ecommerce'],
	),
	'twenty20'                  => array(
		'title'       => esc_html__( 'Twenty20 Image Before-After', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
        'install'     => false,
		'logo'        => n7_golf_club_get_file_url( 'plugins/twenty20/twenty20.png' ),
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'essential-grid'             => array(
		'title'       => esc_html__( 'Essential Grid', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
		'install'     => false,
		'logo'        => 'essential-grid.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'revslider'                  => array(
		'title'       => esc_html__( 'Revolution Slider', 'n7-golf-club' ),
		'description' => '',
		'required'    => false,
		'logo'        => 'revslider.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'sitepress-multilingual-cms' => array(
		'title'       => esc_html__( 'WPML - Sitepress Multilingual CMS', 'n7-golf-club' ),
		'description' => esc_html__( "Allows you to make your website multilingual", 'n7-golf-club' ),
		'required'    => false,
		'install'     => false,      // Do not offer installation of the plugin in the Theme Dashboard and TGMPA
		'logo'        => 'sitepress-multilingual-cms.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['content'],
	),
	'wp-gdpr-compliance'         => array(
		'title'       => esc_html__( 'Cookie Information', 'n7-golf-club' ),
		'description' => esc_html__( "Allow visitors to decide for themselves what personal data they want to store on your site", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'wp-gdpr-compliance.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['other'],
	),
	'trx_updater'                => array(
		'title'       => esc_html__( 'ThemeREX Updater', 'n7-golf-club' ),
		'description' => esc_html__( "Update theme and theme-specific plugins from developer's upgrade server.", 'n7-golf-club' ),
		'required'    => false,
		'logo'        => 'trx_updater.png',
		'group'       => $n7_golf_club_theme_required_plugins_groups['other'],
	),
);

if ( N7_GOLF_CLUB_THEME_FREE ) {
	unset( $n7_golf_club_theme_required_plugins['js_composer'] );
	unset( $n7_golf_club_theme_required_plugins['booked'] );
	unset( $n7_golf_club_theme_required_plugins['the-events-calendar'] );
	unset( $n7_golf_club_theme_required_plugins['calculated-fields-form'] );
	unset( $n7_golf_club_theme_required_plugins['essential-grid'] );
	unset( $n7_golf_club_theme_required_plugins['revslider'] );
	unset( $n7_golf_club_theme_required_plugins['sitepress-multilingual-cms'] );
	unset( $n7_golf_club_theme_required_plugins['trx_updater'] );
	unset( $n7_golf_club_theme_required_plugins['trx_popup'] );
}

// Add plugins list to the global storage
n7_golf_club_storage_set( 'required_plugins', $n7_golf_club_theme_required_plugins );
