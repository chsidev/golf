<?php
/* Woocommerce extensions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_theme_setup9' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_theme_setup9', 9 );
	function n7_golf_club_woocommerce_extensions_theme_setup9() {
		if ( n7_golf_club_exists_woocommerce() ) {
			// Frontend styles and scripts
			add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_frontend_scripts', 1100 );
			add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'n7_golf_club_woocommerce_extensions_frontend_scripts', 10, 1 );
			add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_frontend_scripts_responsive', 2000 );
			add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'n7_golf_club_woocommerce_extensions_frontend_scripts_responsive', 10, 1 );
			add_filter( 'n7_golf_club_filter_merge_styles', 'n7_golf_club_woocommerce_extensions_merge_styles' );
			add_filter( 'n7_golf_club_filter_merge_styles_responsive', 'n7_golf_club_woocommerce_extensions_merge_styles_responsive' );
			add_filter( 'n7_golf_club_filter_merge_scripts', 'n7_golf_club_woocommerce_extensions_merge_scripts' );
		}
	}
}

// Enqueue WooCommerce extensions styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_frontend_scripts', 1100 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'n7_golf_club_woocommerce_extensions_frontend_scripts', 10, 1 );
	function n7_golf_club_woocommerce_extensions_frontend_scripts( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && n7_golf_club_need_frontend_scripts( 'woocommerce' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/woocommerce/woocommerce-extensions.css' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_style( 'n7-golf-club-woocommerce-extensions', $n7_golf_club_url, array(), null );
			}
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/woocommerce/woocommerce-extensions.js' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_script( 'n7-golf-club-woocommerce-extensions', $n7_golf_club_url, array( 'jquery' ), null, true );
			}
		}
	}
}

// Enqueue WooCommerce extensions responsive styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_frontend_scripts_responsive' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_frontend_scripts_responsive', 2000 );
	//Handler of the add_action( 'trx_addons_action_load_scripts_front_woocommerce_extensions', 'n7_golf_club_woocommerce_extensions_frontend_scripts_responsive', 10, 1 );
	function n7_golf_club_woocommerce_extensions_frontend_scripts_responsive( $force = false ) {
		static $loaded = false;
		if ( ! $loaded && (
			current_action() == 'wp_enqueue_scripts' && n7_golf_club_need_frontend_scripts( 'woocommerce' )
			||
			current_action() != 'wp_enqueue_scripts' && $force === true
			)
		) {
			$loaded = true;
			$n7_golf_club_url = n7_golf_club_get_file_url( 'plugins/woocommerce/woocommerce-extensions-responsive.css' );
			if ( '' != $n7_golf_club_url ) {
				wp_enqueue_style( 'n7-golf-club-woocommerce-extensions-responsive', $n7_golf_club_url, array(), null, n7_golf_club_media_for_load_css_responsive( 'woocommerce-extensions' ) );
			}
		}
	}
}

// Merge custom styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_merge_styles' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_styles', 'n7_golf_club_woocommerce_extensions_merge_styles');
	function n7_golf_club_woocommerce_extensions_merge_styles( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions.css' ] = false;
		return $list;
	}
}

// Merge responsive styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_merge_styles_responsive' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_styles_responsive', 'n7_golf_club_woocommerce_extensions_merge_styles_responsive');
	function n7_golf_club_woocommerce_extensions_merge_styles_responsive( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions-responsive.css' ] = false;
		return $list;
	}
}

// Merge custom scripts
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_merge_scripts' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_merge_scripts', 'n7_golf_club_woocommerce_extensions_merge_scripts');
	function n7_golf_club_woocommerce_extensions_merge_scripts( $list ) {
		$list[ 'plugins/woocommerce/woocommerce-extensions.js' ] = false;
		return $list;
	}
}



/* Common functions
------------------------------------------------------------------- */

// Check if taxomony is a Woocommerce product's attribute
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_attrib_get_type' ) ) {
	function n7_golf_club_woocommerce_extensions_attrib_get_type( $taxonomy ) {
		$type = '';
		if ( n7_golf_club_exists_woocommerce() ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attribute ) {
					if ( wc_attribute_taxonomy_name( $attribute->attribute_name ) == $taxonomy ) {
						$type = $attribute->attribute_type;
						break;
					}
				}
			}
		}
		return $type;
	}
}



/* Move breadcrumbs above title
------------------------------------------------------------------- */
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_move_breadcrumbs_before_title' ) ) {
	add_action( 'init', 'n7_golf_club_woocommerce_extensions_move_breadcrumbs_before_title' );
	function n7_golf_club_woocommerce_extensions_move_breadcrumbs_before_title() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'move_breadcrumbs' )
		) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );	// Priority 5 used for the title
		}
	}
}



/* Add parameter 'Single Product Gallery Style'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_gallery_style_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
		) {
			// Add 'single_product_gallery_style' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'single_product_gallery_thumbs', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_gallery_style_args', array(
				'single_product_gallery_style'  => array(
					'title'      => esc_html__( 'Gallery style', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Select the style of the gallery on the single product page.', 'n7-golf-club' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					'options'    => apply_filters(
													'n7_golf_club_filter_woocommerce_single_product_gallery_style',
													array(
														'default'   => esc_html__( 'Default', 'n7-golf-club' ),
														'fullwidth' => esc_html__( 'Fullwidth', 'n7-golf-club' ),
														'cascade'   => esc_html__( 'Cascade', 'n7-golf-club' ),
														'grid'      => esc_html__( 'Grid', 'n7-golf-club' ),
													)
												),
					'std'        => 'default',
					'type'       => 'select',
				),
			) ) );

			// Add condition to the 'single_product_gallery_thumbs'
			n7_golf_club_storage_set_array2( 'options', 'single_product_gallery_thumbs', 'dependency', array(
				'single_product_gallery_style' => array( 'default', 'fullwidth' ),
			) );

		}
	}
}

// Disable slider and zoom for 'fullwidth' (only zoom), 'cascade' and 'grid'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_product_gallery_style_theme_support', 9 );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_theme_support() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
		) {
			$style = n7_golf_club_get_theme_option( 'single_product_gallery_style' );
			// Disable 'zoom'
			if ( in_array( $style, array( 'fullwidth', 'cascade', 'grid' ) ) ) {
				remove_theme_support( 'wc-product-gallery-zoom' );
			}
			// Disable 'slider'
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				remove_theme_support( 'wc-product-gallery-slider' );
			}
			// Load 'masonry' script
			if ( 'grid' == $style && is_product() ) {
				n7_golf_club_load_masonry_scripts();
			}
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_add_wrapper_classes' ) ) {
	add_filter( 'n7_golf_club_filter_single_product_wrapper_class', 'n7_golf_club_woocommerce_extensions_product_gallery_style_add_wrapper_classes' );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_add_wrapper_classes( $classes ) {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
			&& is_product()
		) {
			$classes[] = 'single_product_gallery_style_' . esc_attr( n7_golf_club_get_theme_option( 'single_product_gallery_style' ) );
		}
		return $classes;
	}
}

// Increase thumbnail size for some gallery's styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_thumbnail_size' ) ) {
	add_filter( 'woocommerce_gallery_thumbnail_size', 'n7_golf_club_woocommerce_extensions_product_gallery_style_thumbnail_size' );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_thumbnail_size( $sizes = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = n7_golf_club_get_theme_option( 'single_product_gallery_style' );
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				$gallery_thumbnail = wc_get_image_size( 'woocommerce_single' );
				$sizes = array( (int) $gallery_thumbnail['width'], (int) $gallery_thumbnail['height'] );
			}
		}
		return $sizes;
	}
}

// Increase main image size for some gallery's styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_image_size' ) ) {
	add_filter( 'woocommerce_gallery_image_size', 'n7_golf_club_woocommerce_extensions_product_gallery_style_image_size' );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_image_size( $sizes = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = n7_golf_club_get_theme_option( 'single_product_gallery_style' );
			if ( $style == 'fullwidth' ) {
				$sizes = 'full';
			}
		}
		return $sizes;
	}
}


// Disable slider for some gallery's styles
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_disable_slider' ) ) {
	add_filter( 'woocommerce_single_product_flexslider_enabled', 'n7_golf_club_woocommerce_extensions_product_gallery_style_disable_slider' );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_disable_slider( $enabled = true ) {
		return $enabled
					&& ( ! apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' )
						||
						! in_array( n7_golf_club_get_theme_option( 'single_product_gallery_style' ), array( 'cascade', 'grid' ) )
						);
	}
}


// Remove 'single_product_gallery_thumbs_left' from the body 
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_style_remove_body_classes' ) ) {
	add_filter( 'body_class', 'n7_golf_club_woocommerce_extensions_product_gallery_style_remove_body_classes', 20 );
	function n7_golf_club_woocommerce_extensions_product_gallery_style_remove_body_classes( $classes ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_style' ) ) {
			$style = n7_golf_club_get_theme_option( 'single_product_gallery_style' );
			if ( in_array( $style, array( 'cascade', 'grid' ) ) ) {
				if ( n7_golf_club_exists_woocommerce() && is_product() ) {
					$classes = n7_golf_club_array_delete_by_value( $classes, 'single_product_gallery_thumbs_left' );
				}
			}
		}
		return $classes;
	}
}



/* Add parameter 'Single product gallery lightbox on/off'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' )
		) {
			// Add 'single_product_gallery_lightbox' to the theme-specific options
			n7_golf_club_storage_set_array_after( 'options', 'single_product_gallery_thumbs', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_gallery_lightbox_args', array(
				'single_product_gallery_lightbox' => array(
					'title'   => esc_html__( 'Allow lightbox with large image', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Allow the lightbox with a large image in the single product page.", 'n7-golf-club' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Disable image lightbox
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_support', 9 );
	function n7_golf_club_woocommerce_extensions_product_gallery_lightbox_theme_support() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' )
			&& is_product()
		) {
			if ( (int) n7_golf_club_get_theme_option( 'single_product_gallery_lightbox' ) == 0 ) {
				remove_theme_support( 'wc-product-gallery-lightbox' );
			}
		}
	}
}

// Remove link from gallery image layout if lightbox is disabled
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_remove_links' ) ) {
	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'n7_golf_club_woocommerce_extensions_product_gallery_lightbox_remove_links', 9 );
	function n7_golf_club_woocommerce_extensions_product_gallery_lightbox_remove_links( $html = '', $image_id = 0 ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_lightbox' ) ) {
			if ( (int) n7_golf_club_get_theme_option( 'single_product_gallery_lightbox' ) == 0 && strpos( $html, '<a ' ) !== false ) {
				$html = preg_replace( '/(.*)(<a [^>]*>)(.*)(<\/a>)(.*)/', '$1$3$5', $html );
			}
		}
		return $html;
	}
}



/* Add parameter 'Single product gallery zoom on/off'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_zoom' )
		) {
			// Add 'single_product_gallery_zoom' to the theme-specific options
			n7_golf_club_storage_set_array_after( 'options', 'single_product_gallery_thumbs', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_gallery_zoom_args', array(
				'single_product_gallery_zoom' => array(
					'title'   => esc_html__( 'Allow zoom on the large image', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Allow the zoom on the large image in the single product page.", 'n7-golf-club' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Disable image zoom
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_support' ) ) {
	add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_support', 9 );
	function n7_golf_club_woocommerce_extensions_product_gallery_zoom_theme_support() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'gallery_zoom' )
			&& is_product()
		) {
			if ( (int) n7_golf_club_get_theme_option( 'single_product_gallery_zoom' ) == 0 ) {
				remove_theme_support( 'wc-product-gallery-zoom' );
			}
		}
	}
}



/* Add parameter 'Single Product Details Style'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_details_style_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_details_style_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_style' )
		) {
			// Add 'single_product_details_style' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_details_style_args', array(
				'single_product_details_style'   => array(
					'title'      => esc_html__( 'Details style', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Select the style of the section "Details" on the single product page.', 'n7-golf-club' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					'options'    => apply_filters(
													'n7_golf_club_filter_woocommerce_single_product_details_styles',
													array(
														'default'   => esc_html__( 'Default (Tabs)', 'n7-golf-club' ),
														'stacked'   => esc_html__( 'Stacked', 'n7-golf-club' ),
														'accordion' => esc_html__( 'Accordion', 'n7-golf-club' ),
													)
												),
					'std'        => 'default',
					'type'       => 'select',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_style_add_wrapper_classes' ) ) {
	add_filter( 'n7_golf_club_filter_single_product_wrapper_class', 'n7_golf_club_woocommerce_extensions_product_details_style_add_wrapper_classes' );
	function n7_golf_club_woocommerce_extensions_product_details_style_add_wrapper_classes( $classes ) {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& is_product()
		) {
			$classes[] = 'single_product_details_style_' . esc_attr( n7_golf_club_get_theme_option( 'single_product_details_style' ) );
		}
		return $classes;
	}
}


// Enqueue accordion script if description style is equal to 'Accordion'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_style_accordion_enqueue_script' ) ) {
	add_action( 'wp_enqueue_scripts', 'n7_golf_club_woocommerce_extensions_product_details_style_accordion_enqueue_script', 1100 );
	function n7_golf_club_woocommerce_extensions_product_details_style_accordion_enqueue_script() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& n7_golf_club_get_theme_option( 'single_product_details_style' ) == 'accordion'
			&& n7_golf_club_exists_woocommerce()
			&& is_product()
		) {
			wp_enqueue_script( 'jquery-ui-accordion', false, array( 'jquery', 'jquery-ui-core' ), null, true );
		}
	}
}


// Remove tabs if description style is equal to 'Stacked'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_style_stacked_remove_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'n7_golf_club_woocommerce_extensions_product_details_style_stacked_remove_product_tabs', 1000 );
	function n7_golf_club_woocommerce_extensions_product_details_style_stacked_remove_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& n7_golf_club_get_theme_option( 'single_product_details_style' ) == 'stacked'
			&& is_array( $tabs )
		) {
			// Move additional info to top
			if ( isset( $tabs['additional_information'] ) && apply_filters( 'n7_golf_club_filter_woocommerce_extensions_move_additional_first', false ) ) {
				$tabs_new = array( 'additional_information' => $tabs['additional_information'] );
				unset( $tabs['additional_information'] );
				$tabs = array_merge( $tabs_new, $tabs );
			}
			// Display blocks
			do_action( 'n7_golf_club_action_before_product_stacks', $tabs );
			?><div class="woocommerce-stacks"><?php
				foreach ( $tabs as $k => $tab ) {
					if ( isset( $tab['callback'] ) ) {
						do_action( 'n7_golf_club_action_before_product_stack', $k, $tab );
						?><div class="woocommerce-stack woocommerce-stack-<?php echo esc_attr( $k ); ?>"><?php
							call_user_func( $tab['callback'], $k, $tab );
						?></div><?php
						do_action( 'n7_golf_club_action_after_product_stack', $k, $tab );
					}
				}
			?></div><?php
			do_action( 'n7_golf_club_action_after_product_stacks', $tabs );
			// Remove all tabs to prevent display
			$tabs = array();
		}
		return $tabs;
	}
}


// Remove tabs if description style is equal to 'Accordion'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_style_accordion_remove_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'n7_golf_club_woocommerce_extensions_product_details_style_accordion_remove_product_tabs', 1000 );
	function n7_golf_club_woocommerce_extensions_product_details_style_accordion_remove_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_style' )
			&& n7_golf_club_get_theme_option( 'single_product_details_style' ) == 'accordion'
			&& is_array( $tabs )
		) {
			// Move additional info to top
			if ( isset( $tabs['additional_information'] ) && apply_filters( 'n7_golf_club_filter_woocommerce_extensions_move_additional_first', false ) ) {
				$tabs_new = array( 'additional_information' => $tabs['additional_information'] );
				unset( $tabs['additional_information'] );
				$tabs = array_merge( $tabs_new, $tabs );
			}
			// Display blocks
			do_action( 'n7_golf_club_action_before_product_accordion', $tabs );
			?><div class="n7_golf_club_accordion woocommerce-accordion" data-collapsible="true"><?php
				foreach ( $tabs as $k => $tab ) {
					if ( isset( $tab['callback'] ) ) {
						do_action( 'n7_golf_club_action_before_product_accordion_title', $k, $tab );
						?><h3 class="n7_golf_club_accordion_title woocommerce-accordion-title woocommerce-accordion-title-<?php echo esc_attr( $k ); ?>"><?php
							echo wp_kses( $tab['title'], 'n7_golf_club_kses_content' );
						?></h3><?php
						do_action( 'n7_golf_club_action_after_product_accordion_title', $k, $tab );
						do_action( 'n7_golf_club_action_before_product_accordion_content', $k, $tab );
						?><div class="n7_golf_club_accordion_content woocommerce-accordion-content woocommerce-accordion-content-<?php echo esc_attr( $k ); ?>"><?php
							call_user_func( $tab['callback'], $k, $tab );
						?></div><?php
						do_action( 'n7_golf_club_action_after_product_accordion_content', $k, $tab );
					}
				}
			?></div><?php
			do_action( 'n7_golf_club_action_after_product_accordion', $tabs );
			// Remove all tabs to prevent display
			$tabs = array();
		}
		return $tabs;
	}
}


/* Add parameter 'Single Product Details position'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_position_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_details_position_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_details_position_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
		) {
			// Add 'single_product_details_position' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_details_position_args', array(
				'single_product_details_position' => array(
					'title'   => esc_html__( 'Details position', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Select a position of the single product details.", 'n7-golf-club' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					'options' => apply_filters( 'n7_golf_club_filter_woocommerce_extensions_details_position', array(
						'default'       => esc_html__( 'Default', 'n7-golf-club' ),
						'under_gallery' => esc_html__( 'Under the gallery', 'n7-golf-club' ),
						'under_summary' => esc_html__( 'Under the summary', 'n7-golf-club' ),
					) ),
					'std'     => 'default',
					'type'    => 'select',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_position_add_wrapper_classes' ) ) {
	add_filter( 'n7_golf_club_filter_single_product_wrapper_class', 'n7_golf_club_woocommerce_extensions_product_details_position_add_wrapper_classes' );
	function n7_golf_club_woocommerce_extensions_product_details_position_add_wrapper_classes( $classes ) {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& is_product()
		) {
			$classes[] = 'single_product_details_position_' . esc_attr( n7_golf_club_get_theme_option( 'single_product_details_position' ) );
		}
		return $classes;
	}
}

// Move upsells outside the article if details is placed under the gallery
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_position_move_upsells_outside' ) ) {
	add_action( 'woocommerce_before_main_content', 'n7_golf_club_woocommerce_extensions_product_details_position_move_upsells_outside' );
	function n7_golf_club_woocommerce_extensions_product_details_position_move_upsells_outside() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& in_array( n7_golf_club_get_theme_option( 'single_product_details_position' ), array( 'under_gallery', 'under_summary' ) )
			&& is_product()
		) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			add_action( 'woocommerce_after_main_content', 'woocommerce_upsell_display', 15 );
		}
	}
}

// Move related products outside the article if details is placed under the gallery
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_details_position_move_related_products_outside' ) ) {
	add_action( 'woocommerce_before_main_content', 'n7_golf_club_woocommerce_extensions_product_details_position_move_related_products_outside' );
	function n7_golf_club_woocommerce_extensions_product_details_position_move_related_products_outside() {
		if ( n7_golf_club_exists_woocommerce()
			&& (int)n7_golf_club_get_theme_option( 'show_related_posts' ) > 0
			&& (int)n7_golf_club_get_theme_option( 'related_posts' ) > 0
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& in_array( n7_golf_club_get_theme_option( 'single_product_details_position' ), array( 'under_gallery', 'under_summary' ) )
			&& is_product()
		) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			add_action( 'woocommerce_after_main_content', 'woocommerce_output_related_products', 20 );
		}
	}
}



/* Add parameter 'Single Product Summary Sticky'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_summary_sticky_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_summary_sticky_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_summary_sticky_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
		) {
			// Add 'single_product_summary_sticky' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_summary_sticky_args', array(
				'single_product_summary_sticky' => array(
					'title'      => esc_html__( 'Summary sticky', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Make "Summary" sticky on the single product page.', 'n7-golf-club' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					'dependency' => array(
						'single_product_details_position' => array( 'under_gallery' ),
					),
					'std'        => 0,
					'type'       => 'switch',
				),
			) ) );
		}
	}
}

// Add plugin-specific classes to the product
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_summary_sticky_add_wrapper_classes' ) ) {
	add_filter( 'n7_golf_club_filter_single_product_wrapper_class', 'n7_golf_club_woocommerce_extensions_product_summary_sticky_add_wrapper_classes' );
	function n7_golf_club_woocommerce_extensions_product_summary_sticky_add_wrapper_classes( $classes ) {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& n7_golf_club_get_theme_option( 'single_product_details_position' ) == 'under_gallery'
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
			&& n7_golf_club_get_theme_option( 'single_product_summary_sticky' ) == 1
			&& is_product()
		) {
			$classes[] = 'single_product_summary_sticky';
		}
		return $classes;
	}
}

// Add holder instead related products inside the article
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_summary_sticky_add_holder' ) ) {
	add_action( 'woocommerce_after_single_product_summary', 'n7_golf_club_woocommerce_extensions_product_summary_sticky_add_holder', 20 );
	function n7_golf_club_woocommerce_extensions_product_summary_sticky_add_holder() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'details_position' )
			&& n7_golf_club_get_theme_option( 'single_product_details_position' ) == 'under_gallery'
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_summary_sticky' )
			&& n7_golf_club_get_theme_option( 'single_product_summary_sticky' ) == 1
			&& is_product()
		) {
			?><div class="single_product_summary_sticky_related_holder"></div><?php
		}
	}
}



/* Move 'Additional information' under the product's description
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_additional_info_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_additional_info_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_additional_info_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
		) {
			// Add 'single_product_additional_info' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_additional_info_args', array(
				'single_product_additional_info' => array(
					'title'   => esc_html__( 'Show additional info under short description', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Make additional product information more prominent by moving it up under the short description.", 'n7-golf-club' ) ),
					'std'     => 0,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}

// Remove tab 'Additional information' from the tabs list
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_additional_info_default_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'n7_golf_club_woocommerce_extensions_additional_info_default_product_tabs', 100 );
	function n7_golf_club_woocommerce_extensions_additional_info_default_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
			&& (int) n7_golf_club_get_theme_option( 'single_product_additional_info' ) == 1
			&& isset( $tabs['additional_information'] )
		) {
			unset( $tabs['additional_information'] );	//woocommerce_product_additional_information_tab
		}
		return $tabs;
	}
}

// Add 'Additional information' above the product's price
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_additional_info_template' ) ) {
	add_action( 'woocommerce_single_product_summary', 'n7_golf_club_woocommerce_extensions_additional_info_template', 25 );
	function n7_golf_club_woocommerce_extensions_additional_info_template() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'additional_info' )
			&& (int) n7_golf_club_get_theme_option( 'single_product_additional_info' ) == 1
			&& function_exists( 'woocommerce_product_additional_information_tab' )
		) {
			?><div class="woocommerce-product-details__additional-information"><?php
				woocommerce_product_additional_information_tab();
			?></div><?php
		}
	}
}



/* Custom text after price on the single product page
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_text_after_price_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_text_after_price_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_text_after_price_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'text_after_price' )
		) {
			// Add 'single_product_text_after_price' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_text_after_price_args', array(
				'single_product_text_after_price' => array(
					'title'    => esc_html__( 'Text after price', 'n7-golf-club' ),
					'desc'     => wp_kses_data( __( 'Specify custom text to show it after the product price on the single product page.', 'n7-golf-club' ) ),
					'std'      => '',
					'type'     => 'text_editor',
				),
			) ) );
		}
	}
}

// Add custom text after "Add to cart" button
// @hooked woocommerce_template_single_price - 10
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_text_after_price' ) ) {
	add_action( 'woocommerce_single_product_summary', 'n7_golf_club_woocommerce_extensions_product_text_after_price', 10 );
	function n7_golf_club_woocommerce_extensions_product_text_after_price() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'text_after_price' ) ) {
			n7_golf_club_show_layout( do_shortcode( n7_golf_club_get_theme_option( 'single_product_text_after_price' ) ), '<div class="single_product_custom_text_after_price">', '</div>' );
		}
	}
}



/* Custom text after "Add to cart" on the single product page
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'text_after_add_to_cart' )
		) {
			// Add 'single_product_text_after_add_to_cart' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_text_after_add_to_cart_args', array(
				'single_product_text_after_add_to_cart' => array(
					'title'    => esc_html__( 'Text after "Add to Cart"', 'n7-golf-club' ),
					'desc'     => wp_kses_data( __( 'Specify custom text to show it after "Add to Cart" button on the single product page.', 'n7-golf-club' ) ),
					'std'      => '',
					'type'     => 'text_editor',
				),
			) ) );
		}
	}
}

// Add custom text after "Add to cart" button
// @hooked woocommerce_template_single_add_to_cart - 30
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart' ) ) {
	add_action( 'woocommerce_single_product_summary', 'n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart', 32 );
	function n7_golf_club_woocommerce_extensions_product_text_after_add_to_cart() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'text_after_add_to_cart' ) ) {
			n7_golf_club_show_layout( do_shortcode( n7_golf_club_get_theme_option( 'single_product_text_after_add_to_cart' ) ), '<div class="single_product_custom_text_after_add_to_cart">', '</div>' );
		}
	}
}



/* Add parameter 'Single Product "Add to cart" Sticky'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' )
		) {
			// Add 'single_product_add_to_cart_sticky' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_add_to_cart_sticky_args', array(
				'single_product_add_to_cart_sticky' => array(
					'title'    => esc_html__( 'Sticky "Add to Cart" Bottom Bar', 'n7-golf-club' ),
					'desc'     => wp_kses_data( __( 'Add sticky "Add to Cart" bottom bar to the single product page.', 'n7-golf-club' ) ),
					'override' => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					'std'      => 0,
					'type'     => 'switch',
				),
			) ) );
		}
	}
}

// Add sticky bar to the footer
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar' ) ) {
	add_action( 'wp_footer', 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar' );
	function n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_bottom_bar() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' )
			&& (int) n7_golf_club_get_theme_option( 'single_product_add_to_cart_sticky' ) == 1
			&& is_product()
		) {
			global $product;
			// Disable slider in bottom bar
			add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false' );
			?>
			<div class="single_product_bottom_bar_sticky">
				<div class="single_product_bottom_bar_title">
					<?php
					$post_thumbnail_id = $product->get_image_id();
					if ( $post_thumbnail_id > 0 ) {
						$html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $post_thumbnail_id ), $post_thumbnail_id );
						?><div class="single_product_bottom_bar_product_image"><?php
							echo wp_kses( $html, 'n7_golf_club_kses_content' );
						?></div><?php
					}
					?>
					<div class="single_product_bottom_bar_product_title"><?php
						the_title( '<h6 class="product_title entry-title">', '</h6>' );
					?></div>
				</div>
				<div class="single_product_bottom_bar_info">
					<div class="single_product_bottom_bar_product_price"><?php
						echo wp_kses( $product->get_price_html(), 'n7_golf_club_kses_content' );
					?></div>
					<div class="single_product_bottom_bar_product_button"><?php
						ob_start();
						woocommerce_template_single_add_to_cart();
						$output = ob_get_contents();
						ob_end_clean();
						if ( strpos( $output, '<form class="variations_form ' ) !== false ) {
							$output = '<button type="button" class="button alt single_product_bottom_bar_button_select_options">' . esc_html__( 'Select options', 'n7-golf-club' ) . '</button>';
						}
						trx_addons_show_layout( $output );
					?></div>
				</div>
			</div>
			<div class="single_product_bottom_bar_sticky_holder"></div>
			<?php
			// Enable slider again
			remove_action( 'woocommerce_single_product_flexslider_enabled', '__return_false' );	// Is equal to rf
		}
	}
}

// Add var to js
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_localize_script' ) ) {
	add_action( 'n7_golf_club_filter_localize_script', 'n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_localize_script' );
	function n7_golf_club_woocommerce_extensions_product_add_to_cart_sticky_localize_script( $vars = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_to_cart_sticky' ) ) {
			$vars['add_to_cart_sticky'] = n7_golf_club_exists_woocommerce()
											&& is_product()
											&& (int)n7_golf_club_get_theme_option( 'single_product_add_to_cart_sticky' ) == 1;
		}
		return $vars;
	}
}



/* Add parameter 'Show Product meta'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_meta_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_meta_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_product_meta_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_meta' )
		) {
			// Add 'single_product_meta' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_product_meta_args', array(
				'single_product_meta' => array(
					'title'   => esc_html__( 'Show product meta', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Show Categories, Tags, SKU, Product ID on the single product page.", 'n7-golf-club' ) ),
					'std'     => 1,
					'type'    => 'switch',
				),
			) ) );
		}
	}
}


// Disable product meta
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_meta_disable' ) ) {
	add_action( 'woocommerce_single_product_summary', 'n7_golf_club_woocommerce_extensions_product_meta_disable', 1 );
	function n7_golf_club_woocommerce_extensions_product_meta_disable() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_meta' )
			&& is_product()
		) {
			if ( (int) n7_golf_club_get_theme_option( 'single_product_meta' ) == 0 ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			}
		}
	}
}



/* Add tabs to the single product details
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_custom_tabs_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_custom_tabs_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_custom_tabs_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'custom_tabs' )
		) {
			// Add 'single_product_custom_tabs' to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'show_related_posts_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_custom_tabs_args', array(
				'single_product_custom_tabs' => array(
					"title" => esc_html__( "Tabs manager", 'n7-golf-club' ),
					"desc" => wp_kses_data( __( "Manage tabs in the Details section of the single product: hide any tab, reorder tabs, add an icon to the tab title, change a title, add new tabs with custom content", 'n7-golf-club' ) ),
					'override'   => array(
						'mode'    => 'product',
						'section' => esc_html__( 'Content', 'n7-golf-club' ),
					),
					"clone" => true,
					"std" => array(
						array( 'tab_title' => esc_html__( "Description", 'n7-golf-club' ), 'tab_slug' => 'description', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
						array( 'tab_title' => esc_html__( "Reviews (%d)", 'n7-golf-club' ), 'tab_slug' => 'reviews', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
						array( 'tab_title' => esc_html__( "Additional information", 'n7-golf-club' ), 'tab_slug' => 'additional_information', 'tab_icon' => '', 'tab_show' => 1, 'tab_content' => '' ),
					),
					"type" => "group",
					"fields" => array(
						"tab_show" => array(
							"title" => esc_html__( "Tab visible", 'n7-golf-club' ),
							"class" => "n7_golf_club_column-1_4",
							"std" => 1,
							"type" => "switch"
						),
						"tab_title" => array(
							"title" => esc_html__( "Tab title", 'n7-golf-club' ),
							"class" => "n7_golf_club_column-1_4",
							"std" => "",
							"type" => "text"
						),
						"tab_slug" => array(
							"title" => esc_html__( "Tab slug", 'n7-golf-club' ),
							"class" => "n7_golf_club_column-1_4",
							"std" => "",
							"type" => "text"
						),
						"tab_icon" => array(
							"title" => esc_html__( "Tab icon", 'n7-golf-club' ),
							"class" => "n7_golf_club_column-1_4",
							"std" => "",
							"type" => "icon"
						),
						"tab_content" => array(
							"title" => esc_html__( "Tab content", 'n7-golf-club' ),
							"desc" => esc_html__( "Only changing the title, order or visibility of standard WooCommerce tabs (Reviews, Description and Additional information) is allowed. Don't fill in any content for them: this is possible for custom tabs only.", 'n7-golf-club')
									. '<br>'
									. esc_html__( "You can use macros %%TITLE%% and %%PRICE%% to insert the title and price of this product into the content.", 'n7-golf-club'),
							"class" => "n7_golf_club_column-1_1",
							"std" => "",
							"type" => "text_editor"
						),
					)
				),
			) ) );
		}
	}
}

// Add custom tabs (if defined for the current product)
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_custom_tabs_add_to_product_tabs' ) ) {
	add_filter( 'woocommerce_product_tabs', 'n7_golf_club_woocommerce_extensions_custom_tabs_add_to_product_tabs' );
	function n7_golf_club_woocommerce_extensions_custom_tabs_add_to_product_tabs( $tabs = array() ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'custom_tabs' ) ) {
			global $product;
			$custom_tabs = n7_golf_club_get_theme_option( 'single_product_custom_tabs' );
			if ( is_array( $custom_tabs ) ) {
				$priority = 0;
				$new_tabs = array();
				foreach ( $custom_tabs as $tab ) {
					if ( ! empty( $tab['tab_title'] ) && (int) $tab['tab_show'] > 0 ) {
						$priority += 10;
						$slug = sanitize_title( ! empty( $tab['tab_slug'] ) ? $tab['tab_slug'] : $tab['tab_title'] );
						$new_tabs[ $slug ] = isset( $tabs[ $slug ] )
												? $tabs[ $slug ]
												: array( 'callback' => 'n7_golf_club_woocommerce_extensions_custom_tabs_template' );
						$new_tabs[ $slug ][ 'title' ] = ( ! n7_golf_club_is_off( $tab['tab_icon'] )
												 			? '<span class="woocommerce-tab-icon ' . esc_attr( $tab['tab_icon'] ) . '"></span>'
												 			: ''
												 			)
														. ( 'reviews' == $slug && is_object( $product )
															? str_replace( '%d', $product->get_review_count(), $tab['tab_title'] )
															: $tab['tab_title']
															);
						$new_tabs[ $slug ][ 'priority' ] = $priority;
					}
				}
				$tabs = $new_tabs;
			}
		}
		return $tabs;
	}
}

// Display custom tabs
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_custom_tabs_template' ) ) {
	function n7_golf_club_woocommerce_extensions_custom_tabs_template( $tab_slug, $tab_data ) {
		global $product;
		$custom_tabs = n7_golf_club_get_theme_option( 'single_product_custom_tabs' );
		if ( is_array( $custom_tabs ) && is_object( $product ) ) {
			foreach ( $custom_tabs as $tab ) {
				$slug = sanitize_title( $tab['tab_title'] );
				if ( $tab_slug == ( ! empty( $tab['tab_slug'] ) ? $tab['tab_slug'] : $slug ) ) {
					$title = get_the_title();
					$price = $product->get_price_html();
					n7_golf_club_show_layout( do_shortcode(
						str_ireplace(
							array( '%%TITLE%%', '%%PRICE%%' ),
							array( $title,      $price ),
							$tab['tab_content']
						)
					) );
				}
			}
		}
	}
}



/* Add section 'Rating details' to the single product
------------------------------------------------------------------- */

// Replace core Woocommerce tabs output
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_replace_woocommerce_core_tabs' ) ) {
	add_action( 'woocommerce_after_single_product_summary', 'n7_golf_club_woocommerce_extensions_replace_woocommerce_core_tabs', 1 );
	function n7_golf_club_woocommerce_extensions_replace_woocommerce_core_tabs() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'rating_details' ) ) {
			global $product;
			if ( is_object( $product ) && function_exists( 'woocommerce_output_product_data_tabs' ) ) {
				$rating_count = $product->get_rating_count();
				if ( $rating_count > 0 ) {
					remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
					add_action( 'woocommerce_after_single_product_summary', 'n7_golf_club_woocommerce_extensions_add_rating_details', 10 );
				}
			}
		}
	}
}

// Add 'Rating Details' to the tab 'Reviews'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_rating_details' ) ) {
	// Handler of the add_action( 'woocommerce_after_single_product_summary', 'n7_golf_club_woocommerce_extensions_add_rating_details', 10 );
	function n7_golf_club_woocommerce_extensions_add_rating_details() {
		global $product;
		// Capture a tabs output
		ob_start();
		woocommerce_output_product_data_tabs();
		$output = ob_get_contents();
		ob_end_clean();
		// Inject the section 'Rating Details'
		$rating_count  = $product->get_rating_count();
		$review_count  = $product->get_review_count();
		$average       = $product->get_average_rating();
		$html          = '<h5 class="rating_details_title">' . esc_html__( 'Average rating', 'n7-golf-club' ) . '</h5>'
						. '<div class="rating_details_avg">' . esc_html( $average ) . '</div>'
						. '<div class="rating_details_avg_stars">' . wc_get_rating_html( $average, $rating_count ) . '</div>'
						. '<div class="rating_details_avg_total">' . esc_html( sprintf( _n( '%d review', '%d reviews', $review_count, 'n7-golf-club' ), $review_count ) ) . '</div>';
		$rating_counts = $product->get_rating_counts();
		if ( is_array( $rating_counts ) ) {
			$rating_counts     = n7_golf_club_array_merge( array( 5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0 ), $rating_counts );
			$percent_decimals  = apply_filters( 'n7_golf_club_filter_woocommerce_extensions_rating_details_percent_decimals', 1 );
			$rating_as_percent = apply_filters( 'n7_golf_club_filter_woocommerce_extensions_rating_details_as_percent', true );
			$html .= '<div class="rating_details_table">';
			foreach( $rating_counts as $mark => $total ) {
				$percent = round( $total / $rating_count * 100, $percent_decimals );
				$html .= '<div class="rating_details_table_row">'
							. '<div class="rating_details_table_cell rating_details_table_cell_mark">'
								. esc_html( sprintf( _n( '%d star', '%d stars', $mark, 'n7-golf-club' ), $mark ) )
							. '</div>'
							. '<div class="rating_details_table_cell rating_details_table_cell_bar">'
									. '<div class="rating_details_table_cell_bar_fill_' . esc_attr( $total ) . '"'
										. ' style="width:' . esc_attr( $percent ) . '%">'
									. '</div>'
							. '</div>'
							. '<div class="rating_details_table_cell rating_details_table_cell_total">'
								. esc_html( $rating_as_percent ? $percent . '%' : $total )
							. '</div>'
						. '</div>';
			}
			$html .= '</div>';
		}
		$output = str_replace(
						'<div id="comments',
						'<div class="rating_details">' . $html . '</div>'
						. '<div id="comments',
						$output
						);
		// Display tabs
		n7_golf_club_show_layout( $output );
	}
}



/* Add parameter "Product's Video"
------------------------------------------------------------------- */
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_setup' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_product_video_setup' );
	function n7_golf_club_woocommerce_extensions_product_video_setup() {
		if ( n7_golf_club_exists_trx_addons()
			&& n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' )
		) {
			if ( is_admin() ) {
				add_filter( 'n7_golf_club_filter_override_options', 'n7_golf_club_woocommerce_extensions_product_video_add_options' );
				add_filter( 'n7_golf_club_filter_update_post_options', 'n7_golf_club_woocommerce_extensions_product_video_update_options', 10, 3 );
			}
		}
	}
}

// After 'wp' options from the post meta are available
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_init' ) ) {
	add_action( 'wp', 'n7_golf_club_woocommerce_extensions_product_video_init', 20 );
	function n7_golf_club_woocommerce_extensions_product_video_init() {
		if ( n7_golf_club_exists_trx_addons()
			&& n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' )
		) {
			if ( ! is_admin() ) {
				// Add video to the gallery
				if ( n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_position' ) == 'first_thumb' ) {
					// Place video first (before main video)
					add_filter( 'woocommerce_single_product_image_thumbnail_html', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails', 10, 2 );
				} else {
					// Place video last (after all thumbnails)
					add_action( 'woocommerce_product_thumbnails', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails', 100 );
				}
			}
		}
	}
}

// Add options to the product
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_add_options' ) ) {
	//Handler of the add_filter('n7_golf_club_filter_override_options', 'n7_golf_club_woocommerce_extensions_product_video_add_options');
	function n7_golf_club_woocommerce_extensions_product_video_add_options( $list ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			global $post_type;
			if ( 'product' == $post_type && n7_golf_club_options_allow_override( $post_type ) ) {
				$list[] = array(
					'n7_golf_club_woocommerce_extensions_product_video_options',
					esc_html__( 'Product video', 'n7-golf-club' ),
					'n7_golf_club_woocommerce_extensions_product_video_options_show',
					$post_type,
					'side',
					'default',
				);
			}
		}
		return $list;
	}
}

// Return options to the product video
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_get_options' ) ) {
	function n7_golf_club_woocommerce_extensions_product_video_get_options() {
		return apply_filters( 'n7_golf_club_filter_woocommerce_extensions_product_video_options', array(
					'woocommerce_extensions_product_video_url'   => array(
						"title" => esc_html__( "Video URL", 'n7-golf-club' ),
						"desc" => wp_kses_data( __('Specify URL to show a videoplayer from Youtube, Vimeo or other compatible video hosting', 'n7-golf-club') ),
						"std" => "",
						"type" => "text"
					),
					'woocommerce_extensions_product_video_image' => array(
						"title" => esc_html__( "Cover image", 'n7-golf-club' ),
						"desc" => wp_kses_data( __( "Select an image to be used as a video cover", 'n7-golf-club' ) ),
						"std" => "",
						"type" => "image"
					),
					'woocommerce_extensions_product_video_position' => array(
						"title" => esc_html__( "Video position", 'n7-golf-club' ),
						"desc" => wp_kses_data( __( "Select a position of the video in the galery", 'n7-golf-club' ) ),
						"std" => "first_thumb",
						"options" => apply_filters( 'n7_golf_club_filter_woocommerce_extensions_product_video_positions', array(
							'first_thumb' => esc_html__( 'First thumb', 'n7-golf-club' ),
							'last_thumb' => esc_html__( 'Last thumb', 'n7-golf-club' ),
						) ),
						"type" => "select"
					),
					'woocommerce_extensions_product_video_in_popup' => array(
						"title" => esc_html__( "Open video in popup", 'n7-golf-club' ),
						"desc" => wp_kses_data( __( "Open video in a popup or embed to the gallery", 'n7-golf-club' ) ),
						"std" => 1,
						"type" => "switch"
					),
					'woocommerce_extensions_product_video_button_position' => array(
						"title" => esc_html__( "Button position", 'n7-golf-club' ),
						"desc" => wp_kses_data( __( "Select a position of the button to open popup", 'n7-golf-club' ) ),
						"std" => "lb",
						"dependency" => array(
							'compare' => 'or',
							'woocommerce_extensions_product_video_in_popup' => array( 1 ),
							'woocommerce_extensions_product_video_image' => array( 'not_empty' )
						),
						"options" => apply_filters( 'n7_golf_club_filter_woocommerce_extensions_product_video_button_positions', array(
							'lt' => esc_html__( 'Top Left', 'n7-golf-club' ),
							'rt' => esc_html__( 'Top Right', 'n7-golf-club' ),
							'cc' => esc_html__( 'Middle Center', 'n7-golf-club' ),
							'lb' => esc_html__( 'Bottom Left', 'n7-golf-club' ),
							'rb' => esc_html__( 'Bottom Right', 'n7-golf-club' )
						) ),
						"type" => "select"
					),
					'woocommerce_extensions_product_video_ratio' => array(
						"title" => esc_html__( "Video ratio", 'n7-golf-club' ),
						"desc" => wp_kses_data( __( "Select a ratio of the video", 'n7-golf-club' ) ),
						"std" => "16:9",
						"dependency" => array(
							'woocommerce_extensions_product_video_in_popup' => array( 0 ),
							'woocommerce_extensions_product_video_image' => array( 'is_empty' )
						),
						"options" => apply_filters( 'n7_golf_club_filter_woocommerce_extensions_product_video_ratio', array(
							'2:1'  => esc_html__( '2:1', 'n7-golf-club' ),
							'17:9' => esc_html__( '17:9', 'n7-golf-club' ),
							'16:9' => esc_html__( '16:9', 'n7-golf-club' ),
							'4:3'  => esc_html__( '4:3', 'n7-golf-club' ),
							'1:1'  => esc_html__( '1:1', 'n7-golf-club' ),
							'3:4'  => esc_html__( '3:4', 'n7-golf-club' ),
							'9:16' => esc_html__( '9:16', 'n7-golf-club' ),
							'9:17' => esc_html__( '9:17', 'n7-golf-club' ),
							'1:2'  => esc_html__( '1:2', 'n7-golf-club' ),
						)),
						"type" => "select"
					),
		) );
	}
}


// Add extension-specific dependencies to the general theme dependencies
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_dependencies' ) ) {
	add_filter( 'n7_golf_club_filter_get_theme_dependencies', 'n7_golf_club_woocommerce_extensions_product_video_dependencies' );
	function n7_golf_club_woocommerce_extensions_product_video_dependencies( $depends ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			$options = n7_golf_club_woocommerce_extensions_product_video_get_options();
			foreach ( $options as $k => $v ) {
				if ( isset( $v['dependency'] ) ) {
					$depends[ $k ] = $v['dependency'];
				}
			}
		}
		return $depends;
	}
}

// Callback function to show product options
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_options_show' ) ) {
	function n7_golf_club_woocommerce_extensions_product_video_options_show( $post = false, $args = false ) {
		if ( empty( $post ) || ! is_object( $post ) || empty( $post->ID ) ) {
			global $post, $post_type;
			$mb_post_id   = $post->ID;
			$mb_post_type = $post_type;
		} else {
			$mb_post_id   = $post->ID;
			$mb_post_type = $post->post_type;
		}
		if ( 'product' == $mb_post_type && n7_golf_club_options_allow_override( $mb_post_type ) ) {
			// Load saved options
			$meta = get_post_meta( $mb_post_id, 'n7_golf_club_options', true );
			$options = n7_golf_club_woocommerce_extensions_product_video_get_options();
			?>
			<div class="n7_golf_club_options n7_golf_club_product_options n7_golf_club_product_video_options">
				<div class="n7_golf_club_options_section">
					<?php
					foreach ( $options as $k => $v ) {
						$v['val'] = isset( $meta[ $k ] ) ? $meta[ $k ] : $v['std'];
						n7_golf_club_show_layout( n7_golf_club_options_show_field( $k, $v ) );
					}
					?>
				</div>
			</div>
			<?php
		}
	}
}


// Save product options
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_update_options' ) ) {
	//Handler of the add_filter( 'n7_golf_club_filter_update_post_options', 'n7_golf_club_woocommerce_extensions_product_video_update_options', 10, 3 );
	function n7_golf_club_woocommerce_extensions_product_video_update_options( $meta, $post_id, $post_type = '' ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' )
			&& 'product' == $post_type
		) {
			$options = n7_golf_club_woocommerce_extensions_product_video_get_options();
			foreach ( $options as $k => $v ) {
				// Get option value from POST
				$meta[ $k ] = isset( $_POST[ "n7_golf_club_options_field_{$k}" ] )
								? n7_golf_club_get_value_gp( "n7_golf_club_options_field_{$k}" )
								: ( 'checkbox' == $v['type'] ? 0 : '' );
			}
		}
		return $meta;
	}
}



// Add thumb with video to the gallery
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails' ) ) {
	//Handler of the add_action( 'woocommerce_product_thumbnails', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails', 1 | 100 );
	//Handler of the add_filter( 'woocommerce_single_product_image_thumbnail_html', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails', 10, 2 );
	function n7_golf_club_woocommerce_extensions_product_video_add_thumbnails( $image_html = '', $post_thumbnail_id = 0 ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_video' ) ) {
			// Remove filter to prevent resursion
			if ( has_filter( 'woocommerce_single_product_image_thumbnail_html', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails' ) ) {
				remove_action( 'woocommerce_single_product_image_thumbnail_html', 'n7_golf_club_woocommerce_extensions_product_video_add_thumbnails', 10 );	// Is equal to rf
			}
			// Add thumb with video (if specified)
			$video = n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_url' );
			if ( ! empty( $video ) ) {
				$popup      = n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_in_popup' );
				$cover      = n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_image' );
				$cover_id   = ! empty( $cover ) ? n7_golf_club_attachment_url_to_postid( $cover ) : 0;
				$button_pos = n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_button_position' );
				// Prepare layout with thumb
				if ( $cover_id ) {
					$html = wc_get_gallery_image_html( $cover_id );
				} else {
					$cover     = wc_placeholder_img_src( 'woocommerce_single' );
					$cover_id  = $cover;
					$cover_alt = esc_html__( 'Product video', 'n7-golf-club' );
					$html  = sprintf( '<div class="woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder" data-thumb="%1$s" data-thumb-alt="%2$s">',
										esc_url( $cover ),
										$cover_alt
									);
					$style = n7_golf_club_get_theme_option( 'single_product_gallery_style', 'default' );
					if ( $popup || ! in_array( $style, array( 'cascade', 'grid' ) ) ) {
						$html .= '<a href="' . esc_url( $cover ) . '">'
									. sprintf( '<img src="%1$s" alt="%2$s" class="wp-post-image" />',
												esc_url( $cover ),
												$cover_alt
											)
									. '</a>';
					} else {
						$cover    = '';
						$cover_id = '';
					}
					$html .= '</div>';
				}
				// Add class to the image wrap
				$html = str_replace(
							'"woocommerce-product-gallery__image',
							'"woocommerce-product-gallery__image'
								. ' woocommerce-product-gallery__image--with_video'
								. ( $cover
									? ' woocommerce-product-gallery__image--video_button_' . esc_attr( $button_pos )
									: ' woocommerce-product-gallery__image--video_present'
										. ' woocommerce-product-gallery__image--video_ratio_' . str_replace( ':', '_', n7_golf_club_get_theme_option( 'woocommerce_extensions_product_video_ratio' ) )
									),
							$html
						);
				// If video in the popup - add a 'play' button
				if ( $popup ) {
					$popup_layout = explode(
									'<!-- .sc_layouts_popup -->',
									n7_golf_club_get_video_layout( array(
																	'link'  => $video,
																	'embed' => '',
																	'cover' => $cover_id,
																	'cover_size' => 'full',
																	'show_cover' => false,
																	'popup' => true
																	)
																)
									);
					if ( ! empty( $popup_layout[0] ) && ! empty( $popup_layout[1] ) ) {
						if ( preg_match( '/<a .*<\/a>/', $popup_layout[0], $matches ) && ! empty( $matches[0] ) ) {
							// Remove link to the lightbox
							//$html = preg_replace( '/(.*)(<a[^>]*>)(.*)(<\/a>)(.*)/', '$1$3$5', $html );
							//$html = str_replace( 'data-large_image', 'data-dummy_image', $html );
							// Add popup and button
							$html = str_replace(
										'</div>',
										sprintf( '<div class="post_video_hover post_video_hover_popup">%1$s</div>%2$s',
													 $matches[0],
													 $popup_layout[1]
												)
										. '</div>',
										$html
									);
						}
					}
				} else {
					$video_layout = n7_golf_club_get_video_layout( array(
																	'link'  => $video,
																	'embed' => '',
																	'cover' => $cover_id,
																	'cover_size' => 'full',
																	'show_cover' => false,
																	'popup' => false
																	)
																);
					$html = str_replace(
								'</div>',
								sprintf( '<div class="woocommerce-product-gallery__image--video_wrap">%s</div>', $video_layout )
								. '</div>',
								$html
							);
				}
				$html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $cover );
				if ( ! empty( $image_html ) ) {
					$image_html = trim( $html ) . trim( $image_html );
				} else {
					n7_golf_club_show_layout( $html );
				}
			}
		}
		return $image_html;
	}
}



/* Add parameter 'Add attributes to the products list'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_attributes_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_add_attributes_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_add_attributes_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_attributes_to_product_list' )
		) {
			// Add parameter to the theme-specific options
			n7_golf_club_storage_set_array_after( 'options', 'shop_pagination', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_add_attributes_to_products_list_args', array(
				'add_attributes_to_products_list' => array(
					'title'   => esc_html__( 'Add attributes to the products list', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Display selected attributes in products on the shop page.", 'n7-golf-club' ) ),
					'std'     => '',
					'dir'     => 'vertical',
					'sortable'=> true,
					'options' => array(),
					'type'    => 'checklist',
				),
				'action_on_attribute_click' => array(
					'title'   => esc_html__( 'Action on attribute click', 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Select an action on attribute click: swap a product image (only for variable products), open a single product page or apply a filter.", 'n7-golf-club' ) ),
					'std'     => 'none',
					'options' => array(
						'none'   => esc_html__( 'No action', 'n7-golf-club' ),
						'swap'   => esc_html__( 'Swap image', 'n7-golf-club' ),
						'link'   => esc_html__( 'Open product', 'n7-golf-club' ),
						'filter' => esc_html__( 'Apply filter', 'n7-golf-club' ),
					),
					'type'    => 'radio',
				),
				'swap_on_attribute_hover' => array(
					'title' => esc_html__( 'Swap images on attribute hover', 'n7-golf-club' ),
					'desc'  => wp_kses_data( __( "Swap a product image (only for variable products) on attribute hover.", 'n7-golf-club' ) ),
					'std'   => 0,
					'type'  => 'switch',
					'dependency' => array(
						'action_on_attribute_click' => array( '^swap' )
					)
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_attributes_get_list_choises' ) ) {
	add_filter( 'n7_golf_club_filter_options_get_list_choises', 'n7_golf_club_woocommerce_extensions_add_attributes_get_list_choises', 10, 2 );
	function n7_golf_club_woocommerce_extensions_add_attributes_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'add_attributes_to_products_list' ) === 0 ) {
				$list = n7_golf_club_woocommerce_extensions_get_list_wc_attributes();
			}
		}
		return $list;
	}
}


// Return list of the WooCommerce attributes
if ( !function_exists( 'n7_golf_club_woocommerce_extensions_get_list_wc_attributes' ) ) {
	function n7_golf_club_woocommerce_extensions_get_list_wc_attributes() {
		static $list = false;
		if ( $list === false ) {
			$list = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attribute ) {
					$list[ wc_attribute_taxonomy_name( $attribute->attribute_name ) ] = $attribute->attribute_label;
				}
			}
		}
		return apply_filters( 'n7_golf_club_filter_woocommerce_extensions_get_list_wc_attributes', $list );
	}
}


// Display selected attribute
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_show_attributes' ) ) {
	add_action( 'woocommerce_after_shop_loop_item', 'n7_golf_club_woocommerce_extensions_show_attributes', 18 );         // 20 is used to close item wrapper
	add_action( 'woocommerce_after_shop_loop_item_title', 'n7_golf_club_woocommerce_extensions_show_attributes', 8 );    //  5 is used to display rating
																													//  7 is used to display excerpt (in the list mode)
																													// 10 is used to display price
	function n7_golf_club_woocommerce_extensions_show_attributes() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_attributes_to_product_list' ) ) {
			$attributes = n7_golf_club_get_theme_option( 'add_attributes_to_products_list' );
			if (   ! empty( $attributes )
				&& ! n7_golf_club_is_off( $attributes )
				&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_show_attributes', is_shop() || function_exists('trx_addons_sc_stack_check') && trx_addons_sc_stack_check('trx_sc_extended_products'), $attributes )
			) {
				$mode = n7_golf_club_storage_get( 'shop_mode' );
				if ( ( 'woocommerce_after_shop_loop_item_title' == current_action() && 'list' == $mode )
					||
					( 'woocommerce_after_shop_loop_item' == current_action() && ( 'thumbs' == $mode || ! is_shop() ) )
				) {
					do_action( 'n7_golf_club_action_product_attributes', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_show_attributes_args', array(
								'attributes' => $attributes,
								'action'     => n7_golf_club_get_theme_option( 'action_on_attribute_click' ),
								'swap'       => n7_golf_club_get_theme_option( 'action_on_attribute_click' ) != 'swap' && n7_golf_club_get_theme_option( 'swap_on_attribute_hover' ) ? 1 : 0
								) ) );
				}
			}
		}
	}
}



/* Add parameter 'Product style' to the shop page settings
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_product_style_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_add_product_style_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_add_product_style_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_style' )
		) {
			// Add parameter to the theme-specific options
			n7_golf_club_storage_set_array_after( 'options', 'shop_mode', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_add_product_style_args', array(
				'product_style' => array(
					'title'      => esc_html__( 'Product style', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Style of product items on the shop page.', 'n7-golf-club' ) ),
					'std'     => 'default',
					'options' => array(),
					'type'    => 'select',
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_product_style_get_list_choises' ) ) {
	add_filter( 'n7_golf_club_filter_options_get_list_choises', 'n7_golf_club_woocommerce_extensions_add_product_style_get_list_choises', 10, 2 );
	function n7_golf_club_woocommerce_extensions_add_product_style_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'product_style' ) === 0 && function_exists( 'trx_addons_woocommerce_extended_products_get_list_styles' ) ) {
				$list = trx_addons_woocommerce_extended_products_get_list_styles();
			}
		}
		return $list;
	}
}


// Substitute default template in the products loop with selected in Theme Options
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_product_style_wc_get_template_part' ) ) {
	add_filter( 'wc_get_template_part', 'n7_golf_club_woocommerce_extensions_add_product_style_wc_get_template_part', 200, 3 );
	function n7_golf_club_woocommerce_extensions_add_product_style_wc_get_template_part( $template, $slug, $name ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( $slug == 'content' && $name == 'product'
				&& function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
				&& ! is_product()	// To prevent replace template of related products in the single product page
			) {
				$style = n7_golf_club_get_theme_option( 'product_style' );
				if ( 'default' != $style ) {
					$layouts = trx_addons_woocommerce_extended_products_get_layouts();
					if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['template'] ) ) {
						$template = $layouts[ $style ]['template'];
					}
				}
			}
		}
		return $template;
	}
}


// Add class with a "product style" to the wrap ul.products
// ( if we are not inside a shortcode 'trx_sc_extended_products' )
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_product_style_to_products_wrap' ) ) {
	add_filter( 'woocommerce_product_loop_start', 'n7_golf_club_woocommerce_extensions_add_product_style_to_products_wrap', 200, 1 );
	function n7_golf_club_woocommerce_extensions_add_product_style_to_products_wrap( $template ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
				&& ! is_product()	// To prevent add class for the wrap of related products in the single product page
			) {
				$style = n7_golf_club_get_theme_option( 'product_style' );
				$new_classes = array(
					sprintf( 'products_style_%s', $style )
				);
				$layouts = trx_addons_woocommerce_extended_products_get_layouts();
				if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['products_classes'] ) ) {
					$new_classes = array_merge(
										$new_classes, 
										is_array( $layouts[ $style ]['products_classes'] )
											? $layouts[ $style ]['products_classes']
											: explode( ' ', $layouts[ $style ]['products_classes'] )
										);
				}
				$template = preg_replace( 
										'/(<ul[^>]*class="products )/',
										'$1' . esc_attr( join( ' ', $new_classes ) ) . ' ',
										$template
										);
			}
		}
		return $template;
	}
}


// Add class with a "product style" to each product item
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_product_style_to_product_items' ) ) {
	add_filter( 'woocommerce_post_class', 'n7_golf_club_woocommerce_extensions_add_product_style_to_product_items', 200, 2 );
	function n7_golf_club_woocommerce_extensions_add_product_style_to_product_items( $classes, $product ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'product_style' ) ) {
			if ( function_exists( 'trx_addons_woocommerce_extended_products_get_layouts' )
				&& ( ! function_exists( 'trx_addons_sc_stack_check' )
					 ||
					 ! trx_addons_sc_stack_check( 'trx_sc_extended_products' )
					)
				&& ! is_product()	// To prevent add class for the wrap of related products in the single product page
			) {
				if ( is_array( $classes ) ) {
					$style = n7_golf_club_get_theme_option( 'product_style' );
					$new_classes = array(
										sprintf( 'product_style_%s', esc_attr( $style ) )
									);
					$layouts = trx_addons_woocommerce_extended_products_get_layouts();
					if ( isset( $layouts[ $style ] ) && ! empty( $layouts[ $style ]['product_classes'] ) ) {
						$new_classes = array_merge(
											$new_classes, 
											is_array( $layouts[ $style ]['product_classes'] )
												? $layouts[ $style ]['product_classes']
												: explode( ' ', $layouts[ $style ]['product_classes'] )
											);
					}
					foreach( $new_classes as $c ) {
						$c = trim( $c );
						if ( ! empty( $c ) && ! in_array( $c, $classes ) ) {
							$classes[] = $c;
						}
					}
				}
			}
		}
		return $classes;
	}
}



/* Add parameter 'Brand attribute'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_brand_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_add_brand_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_add_brand_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_brand' )
		) {
			// Add parameter to the theme-specific options
			n7_golf_club_storage_set_array_before( 'options', 'fonts', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_add_brand_args', array(
				'shop_brand_section'         => array(
					'title'      => esc_html__( 'Brands', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Settings of the "Brand" attribute', 'n7-golf-club' ) ),
					'icon'       => 'icon-star',
					'type'       => 'section',
				),
				'shop_brand_info'            => array(
					'title'      => esc_html__( 'Brand settings', 'n7-golf-club' ),
					'desc'       => wp_kses_data( __( 'Settings of the "Brand" attribute', 'n7-golf-club' ) ),
					'type'       => 'info',
				),
				'brand_attribute'            => array(
					'title'   => esc_html__( "'Brand' attribute", 'n7-golf-club' ),
					'desc'    => wp_kses_data( __( "Use selected attribute as 'Brand' (display it after title).", 'n7-golf-club' ) ),
					'std'     => 'none',
					'options' => array(),
					'type'    => 'select',
				),
				'brand_attribute_on_shop'    => array(
					'title'  => esc_html__( "Show 'Brand' on the shop page", 'n7-golf-club' ),
					'desc'   => wp_kses_data( __( "Show attribute 'Brand' in the each product on the shop page", 'n7-golf-club' ) ),
					'std'    => 0,
					'type'   => 'switch',
				),
				'brand_attribute_on_product' => array(
					'title'  => esc_html__( "Show 'Brand' on the single product page", 'n7-golf-club' ),
					'desc'   => wp_kses_data( __( "Show 'Brand' on the single product page", 'n7-golf-club' ) ),
					'std'    => 1,
					'type'   => 'switch',
				),
				'brand_attribute_label'      => array(
					'title'  => esc_html__( "Label before 'Brand'", 'n7-golf-club' ),
					'desc'   => wp_kses_data( __( "Some text to display before the 'Brand' on the single product page", 'n7-golf-club' ) ),
					'std'    => '',
					'type'   => 'text',
				),
			) ) );
		}
	}
}


// Return lists with choises when its need in the admin mode
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_add_brand_get_list_choises' ) ) {
	add_filter( 'n7_golf_club_filter_options_get_list_choises', 'n7_golf_club_woocommerce_extensions_add_brand_get_list_choises', 10, 2 );
	function n7_golf_club_woocommerce_extensions_add_brand_get_list_choises( $list, $id ) {
		if ( is_array( $list ) && count( $list ) == 0 ) {
			if ( strpos( $id, 'brand_attribute' ) === 0 ) {
				$list = array_merge( array(
							'none' => n7_golf_club_get_not_selected_text( esc_html__( 'Select attribute', 'n7-golf-club' ) )
							), n7_golf_club_woocommerce_extensions_get_list_wc_attributes() );
			}
		}
		return $list;
	}
}


// Remove brand attribute from attributes list
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_remove_brand_from_attributes_list' ) ) {
	add_filter( 'woocommerce_display_product_attributes', 'n7_golf_club_woocommerce_extensions_remove_brand_from_attributes_list', 10, 2 );
	function n7_golf_club_woocommerce_extensions_remove_brand_from_attributes_list( $attributes, $product ) {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_brand' ) ) {
			$brand = n7_golf_club_get_theme_option( 'brand_attribute' );
			if ( ! empty( $brand )
				&& ! n7_golf_club_is_off( $brand )
				&& (int) n7_golf_club_get_theme_option( 'brand_attribute_on_' . ( is_product() ? 'product' : 'shop' ) ) > 0
				&& taxonomy_exists( $brand )
				&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_show_brand', true )
			) {
				$key = "attribute_{$brand}";
				if ( isset( $attributes[ $key ] ) ) {
					unset( $attributes[ $key ] );
				}
			}
		}
		return $attributes;
	}
}


// Display selected attribute as brand (after title) on single product page and on the shop page
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_show_brand' ) ) {
	add_action( 'woocommerce_single_product_summary', 'n7_golf_club_woocommerce_extensions_show_brand', 6 );     // woocommerce_single_product_summary:5 is used to display a product title
	add_action( 'woocommerce_after_shop_loop_item_title', 'n7_golf_club_woocommerce_extensions_show_brand', 6 ); // woocommerce_after_shop_loop_item_title:5 is used to display a product rating
																											// woocommerce_shop_loop_item_title:10 is used to display a product title
	function n7_golf_club_woocommerce_extensions_show_brand() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'add_brand' ) ) {
			global $product;
			$brand = n7_golf_club_get_theme_option( 'brand_attribute' );
			$show  = (int) n7_golf_club_get_theme_option( 'brand_attribute_on_' . ( current_action() == 'woocommerce_single_product_summary' ? 'product' : 'shop' ) );
			if ( ! empty( $brand ) && ! n7_golf_club_is_off( $brand ) && taxonomy_exists( $brand ) && is_object( $product ) && apply_filters( 'n7_golf_club_filter_woocommerce_extensions_show_brand', $show ) ) {
				$type = n7_golf_club_woocommerce_extensions_attrib_get_type( $brand );
				$terms = wc_get_product_terms( $product->get_id(), $brand, array( 'fields' => 'all' ) );
				if ( is_array( $terms ) && count( $terms ) > 0 ) {
					?><div class="woocommerce_extensions_brand"><?php
					foreach ( $terms as $term ) {
						$term_val = $term->name;
						$term_image = '';
						$term_color = '';
						if ( $type == 'image' ) {
							$term_image = get_term_meta( $term->term_id, 'value', true );
							$term_image = ! empty( $term_image ) && n7_golf_club_is_url( $term_image )
											? n7_golf_club_add_thumb_size( $term_image, n7_golf_club_get_thumb_size( 'masonry' ) )
											: '';
						} else if ( $type == 'color' ) {
							$term_color = get_term_meta( $term->term_id, 'value', true );
							if ( empty( $term_color ) ) {
								$term_color = $term->slug;
							}
						}
						?><span class="woocommerce_extensions_brand_item"><?php
							if ( $type == 'image' && ! empty( $term_image ) ) {
								?><img src="<?php echo esc_url( $term_image ); ?>" class="woocommerce_extensions_brand_item_image" alt="<?php echo esc_attr( $term_val ); ?>"><?php
							}
							// Label
							$label = n7_golf_club_get_theme_option( 'brand_attribute_label' );
							if ( ! empty( $label ) && apply_filters( 'n7_golf_club_filter_woocommerce_extensions_show_brand_label', current_action() == 'woocommerce_single_product_summary' ) ) {
								?><span class="woocommerce_extensions_brand_item_label"><?php echo esc_html( $label ); ?></span><?php
							}
							// Brand
							?><span class="woocommerce_extensions_brand_item_caption<?php
								if ( ! empty( $term_color ) ) {
									echo ' ' . esc_attr( n7_golf_club_add_inline_css_class( 'color: ' . esc_attr( $term_color ) ) );
								}
							?>"><?php echo esc_html( $term_val ); ?></span><?php
						?></span><?php
					}
					?></div><?php
				}
			}
		}
	}
}



/* Add parameter 'Use sidebar as filters panel'
------------------------------------------------------------------- */

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'n7_golf_club_woocommerce_extensions_sidebar_filters_theme_setup3', 3 );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_theme_setup3() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
		) {
			// Add 'sidebar_filters_shop' to the theme-specific options for the Product list
			if ( n7_golf_club_check_theme_option( 'sidebar_position_ss_shop' ) ) {
				n7_golf_club_storage_set_array_before( 'options', 'sidebar_position_ss_shop', apply_filters( 'n7_golf_club_filter_woocommerce_extensions_sidebar_filters_shop_args', array(
					'sidebar_filters_shop' => array(
						'title'      => esc_html__( 'Use sidebar as filters panel', 'n7-golf-club' ),
						'desc'       => wp_kses_data( __( 'Set the sidebar to behave like a filter bar on store pages.', 'n7-golf-club' ) ),
						'dependency' => array(
							'sidebar_position_shop' => array( '^hide' ),
						),
						'std'        => 0,
						'type'       => 'switch',
					),
				) ) );

				// Add condition to the 'single_product_gallery_thumbs'
				$dependency = n7_golf_club_storage_get_array( 'options', 'sidebar_position_ss_shop', 'dependency' );
				if ( ! is_array( $dependency ) ) {
					$dependency = array();
				}
				$dependency['sidebar_filters_shop'] = array( 0 );
				n7_golf_club_storage_set_array2( 'options', 'sidebar_position_ss_shop', 'dependency', $dependency );
			}
		}
	}
}

// Override options 'sidebar_position_ss_shop' and 'sidebar_position_ss_single_shop' if 'sidebar_filters' is on
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_override_position_ss' ) ) {
	add_action( 'n7_golf_club_action_load_options', 'n7_golf_club_woocommerce_extensions_sidebar_filters_override_position_ss');
	function n7_golf_club_woocommerce_extensions_sidebar_filters_override_position_ss() {
		if ( n7_golf_club_exists_woocommerce()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
		) {
			if ( n7_golf_club_check_theme_option( 'sidebar_filters_shop' ) && n7_golf_club_check_theme_option( 'sidebar_position_ss_shop' ) ) {
				if ( (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0 ) {
					n7_golf_club_storage_set_array2( 'options', 'sidebar_position_ss_shop', 'val', 'float' );
				}
			}
		}
	}
}

// Add class to the body
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_body_classes' ) ) {
	add_filter( 'body_class', 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_body_classes' );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_add_body_classes( $classes ) {
		if ( n7_golf_club_exists_woocommerce() && is_shop()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
			&& (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0
		) {
			$classes[] = 'sidebar_as_filters';
		}
		return $classes;
	}
}

// Add the button 'Filters' and wrap it with a select "Order by"
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_open' ) ) {
	add_action( 'woocommerce_before_shop_loop', 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_open', 29 );
	add_action( 'woocommerce_no_products_found', 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_open',9 );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_open() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
			&& (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0
		) {
			?><div class="n7_golf_club_woocommerce_filters_button_wrap<?php
				if ( current_action() == 'woocommerce_no_products_found' ) echo ' n7_golf_club_woocommerce_filters_button_wrap_no_products';
			?>">
				<a href="#" class="n7_golf_club_woocommerce_filters_button icon-sliders">
					<span class="n7_golf_club_woocommerce_filters_button_label"><?php esc_html_e( 'Filters', 'n7-golf-club' ); ?></span>
				</a><?php
		}
	}
}

// Add the button 'Filters'
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_close' ) ) {
	add_action( 'woocommerce_before_shop_loop', 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_close', 31 );
	add_action( 'woocommerce_no_products_found', 'n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_close', 9 );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_button_wrap_close() {
		if ( apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
			&& (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0
		) {
			?></div><?php
		}
	}
}

// Add the button 'Close' to the sidebar
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_close' ) ) {
	add_action( 'n7_golf_club_action_before_sidebar_wrap', 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_close', 10, 1 );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_add_close( $sb = '' ) {
		if ( n7_golf_club_exists_woocommerce()
			&& is_shop()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
			&& (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0
			&& $sb == 'sidebar'
		) {
			?><a class="sidebar_close theme_button_close" tabindex="0"><span class="theme_button_close_icon"></span></a><?php
		}
	}
}

// Add the overlay for the content while sidebar is opened
if ( ! function_exists( 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_overlay' ) ) {
	add_action( 'n7_golf_club_action_content_wrap_end', 'n7_golf_club_woocommerce_extensions_sidebar_filters_add_overlay' );
	function n7_golf_club_woocommerce_extensions_sidebar_filters_add_overlay() {
		if ( n7_golf_club_exists_woocommerce()
			&& is_shop()
			&& apply_filters( 'n7_golf_club_filter_woocommerce_extensions_allow_components', true, 'sidebar_filters' )
			&& (int)n7_golf_club_get_theme_option( 'sidebar_filters_shop' ) > 0
		) {
			?><div class="sidebar_overlay"></div><?php
		}
	}
}



/* Add colors and fonts to the custom CSS
--------------------------------------------------------------- */
if ( n7_golf_club_exists_woocommerce() ) {
	require_once n7_golf_club_get_file_dir( 'plugins/woocommerce/woocommerce-extensions-style.php' );
}
