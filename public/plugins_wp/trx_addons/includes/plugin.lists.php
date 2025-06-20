<?php
/**
 * Lists generators
 *
 * @package ThemeREX Addons
 * @since v1.6.22
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}


// Return an option 'Not selected' for all tag select
if ( ! function_exists('trx_addons_get_not_selected_text') ) {
	function trx_addons_get_not_selected_text( $label ) {
		return apply_filters( 'trx_addons_filter_not_selected_text',
								sprintf( apply_filters( 'trx_addons_filter_not_selected_mask', __( '- %s -', 'trx_addons' ) ), $label )
							);
	}
}


// Return numbers range
if ( !function_exists( 'trx_addons_get_list_range' ) ) {
	function trx_addons_get_list_range($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = $i;
		return $prepend_inherit 
				? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
				: $list;
	}
}


// Return months list
if ( !function_exists( 'trx_addons_get_list_months' ) ) {
	function trx_addons_get_list_months($prepend_inherit=false) {
		$list = array();
		for ($i=1; $i<=12; $i++)
			$list[$i] = date_i18n('F', strtotime('2018-'.$i.'-01'));
		return $prepend_inherit 
				? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
				: $list;
	}
}

// Return list of allowed custom post's taxonomies
if ( !function_exists( 'trx_addons_get_list_taxonomies_all' ) ) {
	function trx_addons_get_list_taxonomies_all($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$post_types = get_post_types(array(
				'public' => true,
				'show_ui' => true,
				'exclude_from_search' => false
			), 'objects');
			if (is_array($post_types)) {
				foreach ($post_types as $pt) {
					$terms = get_object_taxonomies( $pt->name, 'objects' );
					foreach ($terms as $t) {
						if ( empty( $t->show_ui ) || empty( $t->show_in_menu ) ) {
							continue;
						}
						if ( ! isset( $list[$t->name] ) ) {
							$list[$t->name] = sprintf('%1$s (%2$s)', $t->label, $pt->label);
						}
					}
				}
			}
		}
		return $prepend_inherit
			? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
			: $list;
	}
}

// Return list of taxonomies
if ( !function_exists( 'trx_addons_get_list_taxonomies' ) ) {
	function trx_addons_get_list_taxonomies($prepend_inherit=false, $post_type='post') {
		static $list = array();
		if (empty($list[$post_type])) {
			$list[$post_type] = array();
			$taxonomies = get_object_taxonomies($post_type, 'objects');
			foreach ($taxonomies as $slug => $taxonomy)
				$list[$post_type][$slug] = $taxonomy->label;
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$post_type]) 
					: $list[$post_type];
	}
}


// Return list of categories
if ( !function_exists( 'trx_addons_get_list_categories' ) ) {
	function trx_addons_get_list_categories($prepend_inherit=false ) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$terms = get_categories( array(
											'type' => 'post',
											'orderby' => 'name',
											'order' => 'ASC',
											'hide_empty' => 0,
											'hierarchical' => 1,
											'taxonomy' => 'category',
											'pad_counts' => false
											)
										);
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
//				foreach ($terms as $cat) {
//					$list[$cat->term_id] = apply_filters( 'trx_addons_filter_term_name', $cat->name, $cat);
//				}
				$list = trx_addons_get_hierarchical_list( apply_filters( "the_category_list", $terms) );
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of terms
if ( !function_exists( 'trx_addons_get_list_terms' ) ) {
	function trx_addons_get_list_terms($prepend_inherit=false, $taxonomy='category', $opt=array()) {
		static $list = array();
		$opt = array_merge(array(
			'meta_query'   => '',
			'meta_key'	   => '',
			'meta_value'   => '',
			'meta_compare' => '',
			'meta_type'    => '',
			'parent'	   => '',
			'pad_counts'   => false,
			'hide_empty'   => false,
			'return_key'   => 'id'
			), $opt);
		$hash = 'list_terms'
				. '_' . (is_array($taxonomy) ? join('_', $taxonomy) : $taxonomy)
				. '_' . ($opt['return_key'])
				. '_' . (is_array($opt['parent']) ? join('_', $opt['parent']) : $opt['parent'])
				. '_' . ($opt['meta_key'])
				. '_' . ($opt['meta_value'])
				. '_' . (is_array($opt['meta_query']) ? serialize($opt['meta_query']) : $opt['meta_query']);
		if (empty($list[$hash])) {
			$list[$hash] = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$args = array(
					'orderby' => 'name',
					'order' => 'ASC',
					'hide_empty' => $opt['hide_empty'],
					'hierarchical' => 1,
					'taxonomy' => $taxonomy,
					'pad_counts' => $opt['pad_counts']
					);
				if ($opt['parent'] != '') {
					$args['parent'] = $opt['parent'];
				}
				if (is_array($opt['meta_query'])) {
					$args['meta_query'] = $opt['meta_query'];
				} else if (!empty($opt['meta_key'])) {
					$args['meta_key'] = $opt['meta_key'];
					$args['meta_value'] = $opt['meta_value'];
					if ( ! empty($opt['meta_type'] ) ) {
						$args['meta_type'] = $opt['meta_type'];
					}
					if ( ! empty($opt['meta_compare'] ) ) {
						$args['meta_compare'] = $opt['meta_compare'];
					}
				}
				$terms = get_terms( $taxonomy, apply_filters( 'trx_addons_filter_get_list_terms_args', $args, $taxonomy, $opt ) );
			} else {
				$terms = trx_addons_get_terms_by_taxonomy_from_db($taxonomy, $opt);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				$list[$hash] = trx_addons_get_hierarchical_list( apply_filters( "the_{$taxonomy}_list", $terms ), (int) $opt['parent'], 0, $opt['return_key'] );
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list[$hash]) 
					: $list[$hash];
	}
}


// Return hierarchical list of terms
if ( !function_exists( 'trx_addons_get_hierarchical_terms' ) ) {
	function trx_addons_get_hierarchical_terms( $terms, $parent=0, $level=0 ) {
		$list = array();
		foreach ( $terms as $term ) {
			if ( ( empty( $term->parent ) ? 0 : $term->parent ) == $parent ) {
				$term->hierarchy_level = $level;
				$list[] = $term;
				$list = array_merge( $list, trx_addons_get_hierarchical_terms( $terms, $term->term_id, $level+1 ) );
			}
		}
		return $list;
	}
}


// Return hierarchical list of terms
if ( !function_exists( 'trx_addons_get_hierarchical_list' ) ) {
	function trx_addons_get_hierarchical_list($terms, $parent=0, $level=0, $key='id') {
		$list = array();
		foreach ($terms as $term) {
			if ($term->parent == $parent ) {
				$list[ $key=='id' ? $term->term_id : $term->slug ] = ( $level ? str_repeat('-', $level) . ' ': '' )
										. $term->name
										. ( ! empty($term->count)
												? ' (' . intval($term->count) . ')'
												: ''
											);
				$list = trx_addons_array_merge($list, trx_addons_get_hierarchical_list($terms, $term->term_id, $level+1, $key));
			}
		}
		return $list;
	}
}


// Return list of post's types
if ( !function_exists( 'trx_addons_get_list_posts_types' ) ) {
	function trx_addons_get_list_posts_types($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$types = get_post_types(
						array( 
							'public' => true,
							'exclude_from_search' => false
						), 
						'objects'
					);
			$list = array();
			if (is_array($types)) {
				foreach ($types as $slug => $type) {
					$list[$type->name] = $type->label;
				}
			}
			// Add our custom layouts
			if ( defined( 'TRX_ADDONS_CPT_LAYOUTS_PT' ) ) {
				$list[ TRX_ADDONS_CPT_LAYOUTS_PT ] = esc_html__( 'Layout', 'trx_addons' );
			}
			$list = apply_filters( 'trx_addons_filter_get_list_post_types', $list );
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'trx_addons_get_list_posts' ) ) {
	function trx_addons_get_list_posts($prepend_inherit=false, $opt=array()) {
		static $list = array();
		$opt = array_merge( array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'post_parent'		=> '',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'meta_key'			=> '',
			'meta_value'		=> '',
			'meta_compare'		=> '',
			'meta_type'			=> '',
			'suppress_filters'	=> false,	// Need to compatibility with WPML, because default value is true in the get_posts()
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'not_selected'		=> true,
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type' => $opt) );
		$hash = 'list_posts'
				. ( isset( $opt['post__in'] )
					? '_' . (is_array($opt['post__in']) ? join('_', $opt['post__in']) : $opt['post__in'])
					: (   '_' . (is_array($opt['post_type']) ? join('_', $opt['post_type']) : $opt['post_type'])
						. '_' . (is_array($opt['post_parent']) ? join('_', $opt['post_parent']) : $opt['post_parent'])
						. '_' . ($opt['taxonomy'])
						. '_' . (is_array($opt['taxonomy_value']) ? join('_', $opt['taxonomy_value']) : $opt['taxonomy_value'])
						. '_' . ($opt['meta_key'])
						. '_' . ($opt['meta_compare'])
						. '_' . ($opt['meta_value'])
						. '_' . ($opt['orderby'])
						. '_' . ($opt['order'])
						. '_' . ($opt['return'])
						. '_' . ($opt['posts_per_page'])
						)
					);
		if ( ! isset( $list[$hash] ) ) {
			$list[$hash] = array();
			if ($opt['not_selected'] !== false) {
				$list[$hash]['none'] = $opt['not_selected']===true 
													? trx_addons_get_not_selected_text( esc_html__( 'Not selected', 'trx_addons' ) )
													: $opt['not_selected'];
			}
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => -1 == $opt['posts_per_page'] ? 1000 : $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if ( ! empty( $opt['post_parent'] ) ) {
				if ( is_array($opt['post_parent'] ) ) {
					$args['post_parent__in'] = $opt['post_parent'];
				} else {
					$args['post_parent'] = $opt['post_parent'];
				}
			}
			if ( ! empty( $opt['taxonomy_value'] ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => is_array($opt['taxonomy_value']) 
										? ((int) $opt['taxonomy_value'][0] > 0  ? 'term_taxonomy_id' : 'slug')
										: ((int) $opt['taxonomy_value'] > 0  ? 'term_taxonomy_id' : 'slug'),
						'terms' => is_array($opt['taxonomy_value'])
										? $opt['taxonomy_value'] 
										: ((int) $opt['taxonomy_value'] > 0 ? (int) $opt['taxonomy_value'] : $opt['taxonomy_value'] ) 
					)
				);
			}
			if ( ! empty( $opt['meta_key'] ) ) {
				$args['meta_key'] = $opt['meta_key'];
			}
			if ( ! empty( $opt['meta_value'] ) ) {
				$args['meta_value'] = $opt['meta_value'];
			}
			if ( ! empty( $opt['meta_compare'] ) ) {
				$args['meta_compare'] = $opt['meta_compare'];
			}
			if ( ! empty( $opt['meta_type'] ) ) {
				$args['meta_type'] = $opt['meta_type'];
			}
			$posts = get_posts( apply_filters( 'trx_addons_filter_get_list_posts_args', $args, $opt ) );			
			if ( is_array( $posts ) && count( $posts ) > 0 ) {
				foreach ( $posts as $post ) {
					$list[$hash][$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title . ( $args['post_type'] == 'any' ? ' (' . $post->post_type . ')' : '' );
				}
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge( array( 'inherit' => esc_html__("Inherit", 'trx_addons') ), $list[$hash] ) 
					: $list[$hash];
	}
}


// Return list pages
if ( !function_exists( 'trx_addons_get_list_pages' ) ) {
	function trx_addons_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'taxonomy'			=> '',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return trx_addons_get_list_posts($prepend_inherit, $opt);
	}
}

// Return list of the custom layouts
if ( !function_exists( 'trx_addons_get_list_layouts' ) ) {
	function trx_addons_get_list_layouts($not_selected=false, $type='custom', $order='ID') {
		if (defined('TRX_ADDONS_CPT_LAYOUTS_PT')) {
			$list = trx_addons_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => $type,
							'orderby' => $order,
							'order' => 'asc',
							'not_selected' => $not_selected
							)
						);
		} else {
			$list = array();
		}
		return $list;
	}
}

// Return list of cases when layouts should be showed
if ( !function_exists( 'trx_addons_get_list_layouts_show_on' ) ) {
	function trx_addons_get_list_layouts_show_on( $prepend_inherit = false ) {
		$list = apply_filters('trx_addons_filter_get_list_layouts_display', array(
			'none'				=> esc_html__('Do not show on page loads', 'trx_addons'),
			'on_page_load'		=> esc_html__('Every time the page loads', 'trx_addons'),
			'on_page_load_once'	=> esc_html__('When the page first loads', 'trx_addons'),
			'on_page_close'		=> esc_html__('When leaving site', 'trx_addons'),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of registered users
if ( !function_exists( 'trx_addons_get_list_users' ) ) {
	function trx_addons_get_list_users($prepend_inherit=false, $by='login', $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = trx_addons_get_not_selected_text( esc_html__( 'Not selected', 'trx_addons' ) );
			$users = get_users(array(
									'orderby' => 'display_name',
									'order' => 'ASC',
									'role__in' => $roles
									)
								);
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					//--- Not need to check roles because a param 'role__in' is added to the query above
					//--- ( this param help filter records and increase a query speed:
					//---   if a site has many subscribers - they are not included in the array $users )
					if ( false && is_array($user->roles) && count($user->roles) > 0 ) {
						$accept = false;
						foreach ($user->roles as $role) {
							if (in_array($role, $roles)) {
								$accept = true;
								break;
							}
						}
					}
					//---
					if ($accept) {
						$list[ ( $by=='login' ? $user->user_login : $user->ID ) ] = $user->display_name;
					}
				}
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of registered user's roles
if ( !function_exists( 'trx_addons_get_list_users_roles' ) ) {
	function trx_addons_get_list_users_roles($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = trx_addons_get_not_selected_text( esc_html__( 'Not selected', 'trx_addons' ) );
			if ( function_exists( 'get_editable_roles' ) ) {
				$roles = get_editable_roles();
				if ( is_array( $roles ) ) {
					foreach ( $roles as $role => $details ) {
						$list[ $role ] = translate_user_role( $details['name'] );
					}
				}
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of the team-compatible post's types
if ( !function_exists( 'trx_addons_get_list_team_posts_types' ) ) {
	function trx_addons_get_list_team_posts_types($prepend_inherit=false) {
		static $list = false;
		if ( $list === false ) {
			$list = apply_filters( 'trx_addons_filter_get_list_team_posts_types', array( TRX_ADDONS_CPT_TEAM_PT => __( 'Team', 'trx_addons' ) ) );
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of the specified icons (font icons, svg icons or png icons)
if ( !function_exists( 'trx_addons_get_list_icons' ) ) {
	function trx_addons_get_list_icons($style) {
		$lists = get_transient('trx_addons_list_icons');
		if ( ! is_array($lists) || ! isset($lists[$style]) || ! is_array($lists[$style]) || count($lists[$style]) < 2 ) {
			if ($style == 'icons') {
				$lists[$style] = trx_addons_array_from_list(trx_addons_get_list_icons_classes());
			} else if ($style == 'images') {
				$lists[$style] = trx_addons_get_list_files('css/icons.png', 'png');
			} else { //if ($style == 'svg') {
				$lists[$style] = trx_addons_get_list_files('css/icons.svg', 'svg');
			}
			if (is_admin() && is_array($lists[$style]) && count($lists[$style]) > 1) {
				set_transient('trx_addons_list_icons', $lists, 6 * 60 * 60);		// Store to the cache for 6 hours
			}
		}
		return $lists[$style];
	}
}

// Return iconed classes list
if ( !function_exists( 'trx_addons_get_list_icons_classes' ) ) {
	function trx_addons_get_list_icons_classes($prepend_inherit=false) {
		static $list = false;
		if ( $list === false ) {
			$list = apply_filters('trx_addons_filter_get_list_icons_classes', $list, $prepend_inherit);
			if ( $list === false ) {
				$list = ! is_admin() ? array() : trx_addons_parse_icons_classes(trx_addons_get_file_dir("css/font-icons/css/trx_addons_icons-codes.css"));
			}
			if ( ! isset($list['none']) ) {
				$list = trx_addons_array_merge( array('none' => 'none'), $list );
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge( array('inherit' => esc_html__("Inherit", 'trx_addons')), $list ) 
					: $list;
	}
}


// Return list files in the folder
if ( !function_exists('trx_addons_get_list_files')) {	
	function trx_addons_get_list_files($folder, $ext='', $only_names=false) {
		static $list = array();
		$hash = $folder.'_'.$ext.'_'.($only_names ? '1' : '0');
		if (!isset($list[$hash])) {
			$dir = trx_addons_get_folder_dir($folder);
			$url = trx_addons_get_folder_url($folder);
			$list[$hash] = array();
			if ( !empty($dir) && is_dir($dir) ) {
				$files = @glob(sprintf("%s/%s", $dir, !empty($ext) ? "*.{$ext}" : '*.*'));
				if ( is_array($files) ) {
					foreach ($files as $file) {
						if ( substr($file, 0, 1) == '.' || is_dir( $file ) )
							continue;
						$file = basename($file);
						$key = substr($file, 0, strrpos($file, '.'));
						if (substr($key, -4)=='.min') $key = substr($file, 0, strrpos($key, '.'));
						$list[$hash][$key] = $only_names ? ucfirst(str_replace('_', ' ', $key)) : ($url) . '/' . ($file);
					}
				}
				if (!isset($list[$hash]['none'])) $list[$hash] = trx_addons_array_merge(array('none' => ''), $list[$hash]);
			}
		}
		return $list[$hash];
	}
}


// Return list folders in the folder
if ( !function_exists('trx_addons_get_list_folders')) {	
	function trx_addons_get_list_folders($folder) {
		static $list = array();
		$hash = $folder;
		if (!isset($list[$hash])) {
			$dir = trx_addons_get_folder_dir($folder);
			$list[$hash] = array();
			if ( !empty($dir) && is_dir($dir) ) {
				$folders = @glob(sprintf("%s/*", $dir));
				if ( is_array($folders) ) {
					foreach ($folders as $fld) {
						if ( substr($fld, 0, 1) == '.' )
							continue;
						$fld = basename($fld);
						$list[$hash][$fld] = ucfirst(str_replace('_', ' ', $fld));
					}
				}
				if (!isset($list[$hash]['none'])) $list[$hash] = trx_addons_array_merge(array('none' => ''), $list[$hash]);
			}
		}
		return $list[$hash];
	}
}


// Return all thumbnails sizes
if (!function_exists('trx_addons_get_list_thumbnail_sizes') ){
	function trx_addons_get_list_thumbnail_sizes() {
		$list = array();
		$thumbnails = get_intermediate_image_sizes();
		$list['full'] = esc_html__('Full size', 'trx_addons');
		foreach ($thumbnails as $thumbnail ) {
			if( !empty($GLOBALS['_wp_additional_image_sizes'][$thumbnail]) ){
				$width = $GLOBALS['_wp_additional_image_sizes'][$thumbnail]['width'];
				$height = $GLOBALS['_wp_additional_image_sizes'][$thumbnail]['height'];
			} else {
				$width = get_option($thumbnail . '_size_w', '');
				$height = get_option($thumbnail . '_size_h', '');
			}
			$list[$thumbnail] = $thumbnail . ' (' . $width . 'x' . $height . ')';
		}
		return $list;
	}
}


// Return input hover effects
if ( !function_exists( 'trx_addons_get_list_input_hover' ) ) {
	function trx_addons_get_list_input_hover($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_input_hover', array(
			'default'	=> esc_html__('Default',	'trx_addons'),
			'accent'	=> esc_html__('Accented',	'trx_addons'),
			'path'		=> esc_html__('Path',		'trx_addons'),
			'jump'		=> esc_html__('Jump',		'trx_addons'),
			'underline'	=> esc_html__('Underline',	'trx_addons'),
			'iconed'	=> esc_html__('Iconed',		'trx_addons'),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return menu hover effects
if ( !function_exists( 'trx_addons_get_list_menu_hover' ) ) {
	function trx_addons_get_list_menu_hover($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_menu_hover', array(
			'fade'			=> esc_html__('Fade',		'trx_addons'),
			'fade_box'		=> esc_html__('Fade Box',	'trx_addons'),
			'slide_box'		=> esc_html__('Slide Box',	'trx_addons'),
			'slide_line'	=> esc_html__('Slide Line',	'trx_addons'),
			'color_line'	=> esc_html__('Color Line',	'trx_addons'),
			'zoom_line'		=> esc_html__('Zoom Line',	'trx_addons'),
			'path_line'		=> esc_html__('Path Line',	'trx_addons'),
			'roll_down'		=> esc_html__('Roll Down',	'trx_addons'),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return list of the enter animations
if ( !function_exists( 'trx_addons_get_list_animations_in' ) ) {
	function trx_addons_get_list_animations_in($prepend_inherit=false, $none_key = 'none') {
		$list = apply_filters('trx_addons_filter_get_list_animations_in', array(
			$none_key 			=> trx_addons_get_not_selected_text( esc_html__( 'None', 'trx_addons' ) ),
			'bounceIn'			=> esc_html__('Bounce In',			'trx_addons'),
			'bounceInUp'		=> esc_html__('Bounce In Up',		'trx_addons'),
			'bounceInDown'		=> esc_html__('Bounce In Down',		'trx_addons'),
			'bounceInLeft'		=> esc_html__('Bounce In Left',		'trx_addons'),
			'bounceInRight'		=> esc_html__('Bounce In Right',	'trx_addons'),
			'elastic'			=> esc_html__('Elastic In',			'trx_addons'),
			'fadeIn'			=> esc_html__('Fade In',			'trx_addons'),
			'fadeInUp'			=> esc_html__('Fade In Up',			'trx_addons'),
			'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'trx_addons'),
			'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'trx_addons'),
			'fadeInDown'		=> esc_html__('Fade In Down',		'trx_addons'),
			'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'trx_addons'),
			'fadeInLeft'		=> esc_html__('Fade In Left',		'trx_addons'),
			'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'trx_addons'),
			'fadeInRight'		=> esc_html__('Fade In Right',		'trx_addons'),
			'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'trx_addons'),
			'flipInX'			=> esc_html__('Flip In X',			'trx_addons'),
			'flipInY'			=> esc_html__('Flip In Y',			'trx_addons'),
			'lightSpeedIn'		=> esc_html__('Light Speed In',		'trx_addons'),
			'rotateIn'			=> esc_html__('Rotate In',			'trx_addons'),
			'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','trx_addons'),
			'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'trx_addons'),
			'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'trx_addons'),
			'rotateInDownRight'	=> esc_html__('Rotate In Down Right','trx_addons'),
			'rollIn'			=> esc_html__('Roll In',			'trx_addons'),
			'slideInUp'			=> esc_html__('Slide In Up',		'trx_addons'),
			'slideInDown'		=> esc_html__('Slide In Down',		'trx_addons'),
			'slideInLeft'		=> esc_html__('Slide In Left',		'trx_addons'),
			'slideInRight'		=> esc_html__('Slide In Right',		'trx_addons'),
			'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'trx_addons'),
			'zoomIn'			=> esc_html__('Zoom In',			'trx_addons'),
			'zoomInUp'			=> esc_html__('Zoom In Up',			'trx_addons'),
			'zoomInDown'		=> esc_html__('Zoom In Down',		'trx_addons'),
			'zoomInLeft'		=> esc_html__('Zoom In Left',		'trx_addons'),
			'zoomInRight'		=> esc_html__('Zoom In Right',		'trx_addons'),
/*
			'shake'				=> esc_html__('Shake',				'trx_addons'),
			'headShake'			=> esc_html__('Head Shake',			'trx_addons'),
			'jello'				=> esc_html__('Jello',				'trx_addons'),
			'heartBeat'			=> esc_html__('Heart Beat',			'trx_addons'),
			'jackInTheBox'		=> esc_html__('Jack In The Box',	'trx_addons'),
*/
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'trx_addons_get_list_animations_out' ) ) {
	function trx_addons_get_list_animations_out($prepend_inherit=false, $none_key = 'none') {
		$list = apply_filters('trx_addons_filter_get_list_animations_out', array(
			$none_key		=> trx_addons_get_not_selected_text( esc_html__( 'None', 'trx_addons' ) ),
			'bounceOut'		=> esc_html__('Bounce Out',			'trx_addons'),
			'bounceOutUp'	=> esc_html__('Bounce Out Up',		'trx_addons'),
			'bounceOutDown'	=> esc_html__('Bounce Out Down',	'trx_addons'),
			'bounceOutLeft'	=> esc_html__('Bounce Out Left',	'trx_addons'),
			'bounceOutRight'=> esc_html__('Bounce Out Right',	'trx_addons'),
			'fadeOut'		=> esc_html__('Fade Out',			'trx_addons'),
			'fadeOutUp'		=> esc_html__('Fade Out Up',		'trx_addons'),
			'fadeOutUpBig'	=> esc_html__('Fade Out Up Big',	'trx_addons'),
			'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','trx_addons'),
			'fadeOutDownBig'=> esc_html__('Fade Out Down Big',	'trx_addons'),
			'fadeOutDown'	=> esc_html__('Fade Out Down',		'trx_addons'),
			'fadeOutLeft'	=> esc_html__('Fade Out Left',		'trx_addons'),
			'fadeOutLeftBig'=> esc_html__('Fade Out Left Big',	'trx_addons'),
			'fadeOutRight'	=> esc_html__('Fade Out Right',		'trx_addons'),
			'fadeOutRightBig'=> esc_html__('Fade Out Right Big','trx_addons'),
			'flipOutX'		=> esc_html__('Flip Out X',			'trx_addons'),
			'flipOutY'		=> esc_html__('Flip Out Y',			'trx_addons'),
			'hinge'			=> esc_html__('Hinge Out',			'trx_addons'),
			'lightSpeedOut'	=> esc_html__('Light Speed Out',	'trx_addons'),
			'rotateOut'		=> esc_html__('Rotate Out',			'trx_addons'),
			'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'trx_addons'),
			'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',	'trx_addons'),
			'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'trx_addons'),
			'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'trx_addons'),
			'rollOut'			=> esc_html__('Roll Out',		'trx_addons'),
			'slideOutUp'		=> esc_html__('Slide Out Up',	'trx_addons'),
			'slideOutDown'		=> esc_html__('Slide Out Down',	'trx_addons'),
			'slideOutLeft'		=> esc_html__('Slide Out Left',	'trx_addons'),
			'slideOutRight'		=> esc_html__('Slide Out Right','trx_addons'),
			'zoomOut'			=> esc_html__('Zoom Out',		'trx_addons'),
			'zoomOutUp'			=> esc_html__('Zoom Out Up',	'trx_addons'),
			'zoomOutDown'		=> esc_html__('Zoom Out Down',	'trx_addons'),
			'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'trx_addons'),
			'zoomOutRight'		=> esc_html__('Zoom Out Right',	'trx_addons')
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('trx_addons_get_animation_classes')) {
	function trx_addons_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return trx_addons_is_off($animation) 
					? '' 
					: 'animated '.esc_attr($animation).' '.esc_attr($speed).(!trx_addons_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}

// Return parameter data-post-animation for the posts archive or shortcode output
if (!function_exists('trx_addons_add_blog_animation')) {
	function trx_addons_add_blog_animation($sc, $args=array()) {
		$animation = '';
		if ( !empty($args['animation'])) {
			$animation = $args['animation'];
		} else if ( !trx_addons_sc_stack_check("trx_sc_{$sc}") && trx_addons_check_option($sc . '_blog_animation') ) {
			$animation = trx_addons_get_option( $sc . '_blog_animation' );
		}		
		if ( !trx_addons_is_off($animation) && empty( $args['slider'] ) ) {
			echo ' data-post-animation="' . esc_attr( trx_addons_get_animation_classes( $animation ) ) . '"';
		}
	}
}


// Return list of the easing effects
if ( !function_exists( 'trx_addons_get_list_ease' ) ) {
	function trx_addons_get_list_ease($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_ease', array(
			'linear'  => esc_html__('Linear', 'trx_addons'),
			'power1'  => esc_html__('Power1', 'trx_addons'),
			'power2'  => esc_html__('Power2', 'trx_addons'),
			'power3'  => esc_html__('Power3', 'trx_addons'),
			'power4'  => esc_html__('Power4', 'trx_addons'),
			'back'    => esc_html__('Back', 'trx_addons'),
			'elastic' => esc_html__('Elastic', 'trx_addons'),
			'bounce'  => esc_html__('Bounce', 'trx_addons'),
			'rough'   => esc_html__('Rough', 'trx_addons'),
			'slowmo'  => esc_html__('Slowmo', 'trx_addons'),
			'stepped' => esc_html__('Stepped', 'trx_addons'),
			'circ'    => esc_html__('Circ', 'trx_addons'),
			'expo'    => esc_html__('Expo', 'trx_addons'),
			'sine'    => esc_html__('Sine', 'trx_addons')
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'trx_addons_get_list_menus' ) ) {
	function trx_addons_get_list_menus($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = trx_addons_get_not_selected_text( esc_html__( 'Not selected', 'trx_addons' ) );
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return menu locations list, prepended inherit
if ( !function_exists( 'trx_addons_get_list_menu_locations' ) ) {
	function trx_addons_get_list_menu_locations($prepend_inherit=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			$list['none'] = trx_addons_get_not_selected_text( esc_html__( 'Not selected', 'trx_addons' ) );
			$menus = get_registered_nav_menus();
			if (is_array($menus)) {
				foreach ( $menus as $location => $description )
					$list[$location] = $description;
			}
			$list = apply_filters('trx_addons_filter_menu_locations', $list);
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}

// Return submenu styles list, prepended inherit
if ( !function_exists( 'trx_addons_get_list_sc_submenu_styles' ) ) {
	function trx_addons_get_list_sc_submenu_styles($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_submenu_styles', array(
			'popup'    => esc_html__('Popup', 'trx_addons'),
			'dropdown' => esc_html__('Dropdown', 'trx_addons'),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list) 
					: $list;
	}
}


// Return custom sidebars list, prepended inherit and hide sidebars item (if need)
if ( !function_exists( 'trx_addons_get_list_sidebars' ) ) {
	function trx_addons_get_list_sidebars($prepend_inherit=false, $add_hide=false) {
		static $list = false;
		if ($list === false) {
			$list = array();
			global $wp_registered_sidebars;
			if (is_array($wp_registered_sidebars)) {
				foreach ( $wp_registered_sidebars as $k => $v ) {
					$list[$v['id']] = $v['name'];
				}
			}
			$list = apply_filters('trx_addons_filter_sidebars', $list);
		}
		if ($add_hide) $list = trx_addons_array_merge( array( 'hide' => trx_addons_get_not_selected_text( esc_html__( 'Select widgets', 'trx_addons' ) ) ), $list );
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}


// Return list of the background positions
if ( !function_exists( 'trx_addons_get_list_background_positions' ) ) {
	function trx_addons_get_list_background_positions($prepend_inherit=false, $add_empty = true, $add_custom = false) {
		$list = apply_filters('trx_addons_filter_get_list_background_positions', array(
			'top left' => esc_html__( 'Top Left', 'trx_addons' ),
			'top center' => esc_html__( 'Top Center', 'trx_addons' ),
			'top right' => esc_html__( 'Top Right', 'trx_addons' ),
			'center left' => esc_html__( 'Center Left', 'trx_addons' ),
			'center center' => esc_html__( 'Center Center', 'trx_addons' ),
			'center right' => esc_html__( 'Center Right', 'trx_addons' ),
			'bottom left' => esc_html__( 'Bottom Left', 'trx_addons' ),
			'bottom center' => esc_html__( 'Bottom Center', 'trx_addons' ),
			'bottom right' => esc_html__( 'Bottom Right', 'trx_addons' ),
		));
		if ( $add_empty ) {
			$list = trx_addons_array_merge(array('' => esc_html__("Default", 'trx_addons')), $list);
		}
		if ( $add_custom ) {
			$list = trx_addons_array_merge(array('initial' => esc_html__("Custom", 'trx_addons')), $list);
		}
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}


// Return list of the background repeats
if ( !function_exists( 'trx_addons_get_list_background_repeats' ) ) {
	function trx_addons_get_list_background_repeats($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_background_repeats', array(
			'no-repeat' => esc_html__( 'No repeat', 'trx_addons' ),
			'repeat'    => esc_html__( 'Repeat', 'trx_addons' ),
			'repeat-x'  => esc_html__( 'Repeat X', 'trx_addons' ),
			'repeat-y'  => esc_html__( 'Repeat Y', 'trx_addons' ),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}


// Return list of the background sizes
if ( !function_exists( 'trx_addons_get_list_background_sizes' ) ) {
	function trx_addons_get_list_background_sizes($prepend_inherit=false) {
		$list = apply_filters('trx_addons_filter_get_list_background_sizes', array(
			'contain' => esc_html__( 'Contain', 'trx_addons' ),
			'cover'  => esc_html__( 'Cover', 'trx_addons' ),
			'unset' => esc_html__( 'Normal', 'trx_addons' ),
		));
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}


// Return list of the visibility states
if ( !function_exists( 'trx_addons_get_list_show_hide' ) ) {
	function trx_addons_get_list_show_hide($prepend_inherit=false, $numeric=false) {
		$list = array(
			($numeric ? 1 : 'show') => esc_html__('Show', 'trx_addons'),
			($numeric ? 0 : 'hide') => esc_html__('Hide', 'trx_addons')
		);
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}



// Lists for shortcode's parameters
//-------------------------------------------------------------------------

// Return list of the title align
if ( !function_exists( 'trx_addons_get_list_sc_aligns' ) ) {
	function trx_addons_get_list_sc_aligns($prepend_inherit=false, $add_none=true, $add_justify=false) {
		$list = array(
			'left' => esc_html__('Left', 'trx_addons'),
			'center' => esc_html__('Center', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		);
		if ($add_none) $list = trx_addons_array_merge(array('none' => esc_html__("Default", 'trx_addons')), $list);
		if ($add_justify) $list['justify'] = esc_html__("Justify", 'trx_addons');
		$list = apply_filters('trx_addons_filter_get_list_sc_aligns', $list);
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}

// Return list of the floats
if ( !function_exists( 'trx_addons_get_list_sc_floats' ) ) {
	function trx_addons_get_list_sc_floats($prepend_inherit=false, $add_none=true) {
		$list = array(
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		);
		if ($add_none) $list = trx_addons_array_merge(array('none' => esc_html__("None", 'trx_addons')), $list);
		$list = apply_filters('trx_addons_filter_get_list_sc_floats', $list);
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}

// Return list of the tabs positions
if ( !function_exists( 'trx_addons_get_list_sc_tabs_positions' ) ) {
	function trx_addons_get_list_sc_tabs_positions($prepend_inherit=false) {
		$list = array(
			'top'  => esc_html__('Top', 'trx_addons'),
			'left' => esc_html__('Left', 'trx_addons'),
		);
		$list = apply_filters('trx_addons_filter_get_list_sc_tabs_positions', $list);
		return $prepend_inherit 
					? trx_addons_array_merge(array('inherit' => esc_html__("Inherit", 'trx_addons')), $list)
					: $list;
	}
}

// Return list of the slider pagination positions
if ( !function_exists( 'trx_addons_get_list_sc_paginations' ) ) {
	function trx_addons_get_list_sc_paginations($none_key = 'none') {
		$list = array(
			'prev_next'	=> esc_html__('Previous / Next', 'trx_addons'),
			'pages'		=> esc_html__('Page numbers', 'trx_addons'),
			'advanced_pages'=> esc_html__('Advanced page numbers', 'trx_addons'),
			'load_more'	=> esc_html__('Load more', 'trx_addons'),
			'infinite'	=> esc_html__('Infinite scroll', 'trx_addons'),
		);
		if (!empty($none_key)) {
			$list = array_merge(array($none_key => esc_html__('None', 'trx_addons')), $list);
		}
		return apply_filters('trx_addons_filter_get_list_sc_paginations', $list);
	}
}

// Return list of the title tags
if ( !function_exists( 'trx_addons_get_list_sc_title_tags' ) ) {
	function trx_addons_get_list_sc_title_tags($none_key = 'none', $extended = false) {
		return apply_filters('trx_addons_filter_get_list_sc_title_tags', array_merge(
			(!empty($none_key) 
				? array($none_key => esc_html__('Default', 'trx_addons'))
				: array()
			),
			array(
				'h1' => esc_html__('Heading 1', 'trx_addons'),
				'h2' => esc_html__('Heading 2', 'trx_addons'),
				'h3' => esc_html__('Heading 3', 'trx_addons'),
				'h4' => esc_html__('Heading 4', 'trx_addons'),
				'h5' => esc_html__('Heading 5', 'trx_addons'),
				'h6' => esc_html__('Heading 6', 'trx_addons')
			),
			($extended
				? array(
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
					)
				: array()
			)
		));
	}
}

// Return list of the title tags
if ( !function_exists( 'trx_addons_get_list_sc_subtitle_positions' ) ) {
	function trx_addons_get_list_sc_subtitle_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_subtitle_positions', array(
				'above' => esc_html__('Above title', 'trx_addons'),
				'below' => esc_html__('Below title', 'trx_addons'),
			));
	}
}

// Return list of the title gradients
if ( !function_exists( 'trx_addons_get_list_sc_title_gradient_fills' ) ) {
	function trx_addons_get_list_sc_title_gradient_fills() {
		return apply_filters('trx_addons_filter_get_list_sc_title_gradient_fills', array(
				'block' => esc_html__('Block', 'trx_addons'),
				'inline' => esc_html__('Inline', 'trx_addons'),
		) );
	}
}

// Return list of the share types
if ( !function_exists( 'trx_addons_get_list_sc_share_types' ) ) {
	function trx_addons_get_list_sc_share_types() {
		return apply_filters('trx_addons_filter_get_list_sc_share_types', array(
				'drop'  => __( 'Dropdown list', 'trx_addons' ),
				'list'  => __( 'Small icons', 'trx_addons' ),
				'block' => __( 'Large icons', 'trx_addons' ),
			));
	}
}

// Return list of the show on behaviours
if ( !function_exists( 'trx_addons_get_list_sc_show_on' ) ) {
	function trx_addons_get_list_sc_show_on() {
		return apply_filters('trx_addons_filter_get_list_sc_show_on', array(
				'permanent' => __( 'Show always', 'trx_addons' ),
				'scroll'    => __( 'Scroll to viewport', 'trx_addons' ),
			));
	}
}

// Return list of the title types
if ( !function_exists( 'trx_addons_get_list_sc_supertitle_item_types' ) ) {
	function trx_addons_get_list_sc_supertitle_item_types() {
		return apply_filters('trx_addons_filter_get_list_sc_supertitle_item_types', array(
				'text'  => __( 'Text', 'trx_addons' ),
				'media' => __( 'Media', 'trx_addons' ),
				'icon'  => __( 'Icon', 'trx_addons' ),
			));
	}
}

// Return list of the cover places
if ( !function_exists( 'trx_addons_get_list_sc_cover_places' ) ) {
	function trx_addons_get_list_sc_cover_places() {
		return apply_filters('trx_addons_filter_get_list_sc_cover_places', array(
			'row' => esc_html__('Closest row', 'trx_addons'),
			'column' => esc_html__('Closest column', 'trx_addons'),
			'1' => esc_html__('Parent', 'trx_addons'),
			'2' => esc_html__('Second-level parent', 'trx_addons'),
			'3' => esc_html__('Third-level parent', 'trx_addons'),
		));
	}
}


// Return list of the slider engines
if ( !function_exists( 'trx_addons_get_list_sc_slider_engines' ) ) {
	function trx_addons_get_list_sc_slider_engines() {
		$list = array(
			"swiper" => esc_html__("Posts slider (Swiper)", 'trx_addons'),
			"elastistack" => esc_html__("Posts slider (ElastiStack)", 'trx_addons')
		);
		return apply_filters('trx_addons_filter_get_list_sc_slider_engines', $list);
	}
}

// Return list of the slider controls positions
if ( !function_exists( 'trx_addons_get_list_sc_slider_controls' ) ) {
	function trx_addons_get_list_sc_slider_controls($none_key = 'none') {
		$list = array(
			'side'		=> esc_html__('Side', 'trx_addons'),
			'outside'	=> esc_html__('Outside', 'trx_addons'),
			'top'		=> esc_html__('Top', 'trx_addons'),
			'bottom'	=> esc_html__('Bottom', 'trx_addons')
		);
		if (!empty($none_key)) {
			$list = array_merge(array($none_key => esc_html__('None', 'trx_addons')), $list);
		}
		return apply_filters('trx_addons_filter_get_list_sc_slider_controls', $list);
	}
}

// Return list of the slider pagination positions
if ( !function_exists( 'trx_addons_get_list_sc_slider_paginations' ) ) {
	function trx_addons_get_list_sc_slider_paginations($none_key = 'none', $bottom_outside = true) {
		$list = array(
			'left'		=> esc_html__('Left', 'trx_addons'),
			'right'		=> esc_html__('Right', 'trx_addons'),
			'bottom'	=> esc_html__('Bottom', 'trx_addons')
		);
		if (!empty($none_key)) {
			$list = array_merge(array($none_key => esc_html__('None', 'trx_addons')), $list);
		}
		if ($bottom_outside) {
			$list['bottom_outside'] = esc_html__('Bottom Outside', 'trx_addons');
		}
		return apply_filters('trx_addons_filter_get_list_sc_slider_paginations', $list);
	}
}

// Return list of the slider pagination types
if ( !function_exists( 'trx_addons_get_list_sc_slider_paginations_types' ) ) {
	function trx_addons_get_list_sc_slider_paginations_types() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_paginations_types', array(
			'bullets' => esc_html__('Bullets', 'trx_addons'),
			'fraction' => esc_html__('Fraction (slide numbers)', 'trx_addons'),
			'progressbar' => esc_html__('Progress bar', 'trx_addons')
		));
	}
}

// Return list of the slider controls pagination types
if ( !function_exists( 'trx_addons_get_list_sc_slider_controls_paginations_types' ) ) {
	function trx_addons_get_list_sc_slider_controls_paginations_types() {
		return apply_filters(
					'trx_addons_filter_get_list_sc_slider_controls_paginations_types',
					array_merge(
						array(
							'none' => esc_html__('Hide pagination', 'trx_addons'),
							'thumbs' => esc_html__('Slides thumbnails', 'trx_addons')
						),
						trx_addons_get_list_sc_slider_paginations_types()
					)
				);
	}
}

// Return list of the slider titles
if ( !function_exists( 'trx_addons_get_list_sc_slider_titles' ) ) {
	function trx_addons_get_list_sc_slider_titles() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_titles', array(
			'no' => esc_html__('No titles', 'trx_addons'),
			'center' => esc_html__('Center', 'trx_addons'),
			'bottom' => esc_html__('Bottom Center', 'trx_addons'),
			'lb' => esc_html__('Bottom Left', 'trx_addons'),
			'rb' => esc_html__('Bottom Right', 'trx_addons'),
			'outside' => esc_html__('Outside', 'trx_addons')
		));
	}
}

// Return list of the slides change effects
if ( !function_exists( 'trx_addons_get_list_sc_slider_effects' ) ) {
	function trx_addons_get_list_sc_slider_effects() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_effects', array(
			'slide' => esc_html__('Slide', 'trx_addons'),
			'swap' => esc_html__('Swap', 'trx_addons'),
			'fade' => esc_html__('Fade', 'trx_addons'),
			'cube' => esc_html__('Cube', 'trx_addons'),
			'flip' => esc_html__('Flip', 'trx_addons'),
			'coverflow' => esc_html__('Coverflow', 'trx_addons')
		));
	}
}

// Return list of the slider TOC's styles
if ( !function_exists( 'trx_addons_get_list_sc_slider_toc_styles' ) ) {
	function trx_addons_get_list_sc_slider_toc_styles() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_toc_styles', array(
			'default' => esc_html__('Default', 'trx_addons'),
		));
	}
}

// Return list of the slider TOC's positions
if ( !function_exists( 'trx_addons_get_list_sc_slider_toc_positions' ) ) {
	function trx_addons_get_list_sc_slider_toc_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_toc_positions', array(
			'left'   => esc_html__('Left', 'trx_addons'),
			'right'  => esc_html__('Right', 'trx_addons'),
			'bottom' => esc_html__('Bottom', 'trx_addons'),
		));
	}
}

// Return list of the slider controller's styles
if ( !function_exists( 'trx_addons_get_list_sc_slider_controller_styles' ) ) {
	function trx_addons_get_list_sc_slider_controller_styles() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_controller_styles', array(
			'thumbs' => esc_html__('Thumbs', 'trx_addons'),
			'titles' => esc_html__('Titles', 'trx_addons'),
			'thumbs_titles' => esc_html__('Thumbs+Titles', 'trx_addons'),
		));
	}
}

// Return list of the slider controls styles
if ( !function_exists( 'trx_addons_get_list_sc_slider_controls_styles' ) ) {
	function trx_addons_get_list_sc_slider_controls_styles() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_controls_styles', array(
			'default' => esc_html__('Default', 'trx_addons'),
		));
	}
}

// Return list of the slides change effects
if ( !function_exists( 'trx_addons_get_list_sc_slider_directions' ) ) {
	function trx_addons_get_list_sc_slider_directions() {
		return apply_filters('trx_addons_filter_get_list_sc_slider_directions', trx_addons_get_list_sc_directions());
	}
}


// Return list of the video controller's styles
if ( !function_exists( 'trx_addons_get_list_sc_video_list_controller_styles' ) ) {
	function trx_addons_get_list_sc_video_list_controller_styles() {
		return apply_filters('trx_addons_filter_get_list_sc_video_list_controller_styles', array(
			'default' => esc_html__('Default', 'trx_addons'),
		));
	}
}

// Return list of the video controller's positions
if ( !function_exists( 'trx_addons_get_list_sc_video_list_controller_positions' ) ) {
	function trx_addons_get_list_sc_video_list_controller_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_video_list_controller_positions', array(
			'left'   => esc_html__('Left', 'trx_addons'),
			'right'  => esc_html__('Right', 'trx_addons'),
			'bottom' => esc_html__('Bottom', 'trx_addons'),
		));
	}
}


// Return list of the orderby options for CPT shortcodes
if ( !function_exists( 'trx_addons_get_list_sc_query_orderby' ) ) {
	function trx_addons_get_list_sc_query_orderby($none_key = 'none', $keys = array('none', 'ID', 'post_date', 'title', 'comments', 'likes', 'views', 'rand')) {
		$list = array();
		if (!is_array($keys) && strpos($keys, ',') !== false) {
			$keys = array_map('trim', explode(',', $keys));
		}
		foreach($keys as $key) {
			if ($key == $none_key)
				$list[$key] = esc_html__('None', 'trx_addons');
			else if ($key == 'ID' || $key == 'post_id')
				$list[$key] = esc_html__('Post ID', 'trx_addons');
			else if ($key == 'date' || $key == 'post_date')
				$list[$key] = esc_html__('Date', 'trx_addons');
			else if ($key == 'update' || $key == 'post_update' || $key == 'post_modified')
				$list[$key] = esc_html__('Update', 'trx_addons');
			else if ($key == 'title' || $key == 'post_title')
				$list[$key] = esc_html__('Title', 'trx_addons');
			else if ($key == 'comments')
				$list[$key] = esc_html__('Comments number', 'trx_addons');
			else if ($key == 'likes')
				$list[$key] = esc_html__('Likes number', 'trx_addons');
			else if ($key == 'views')
				$list[$key] = esc_html__('Views number', 'trx_addons');
			else if ($key == 'price')
				$list[$key] = esc_html__('Price', 'trx_addons');
			else if ($key == 'rand' || $key == 'random')
				$list['rand'] = esc_html__('Random', 'trx_addons');
		}
		return apply_filters('trx_addons_filter_get_list_sc_query_orderby', $list, $keys);
	}
}

// Return list of the orderby options for Popular posts widgets
if ( !function_exists( 'trx_addons_get_list_widget_query_orderby' ) ) {
	function trx_addons_get_list_widget_query_orderby() {
		return apply_filters('trx_addons_filter_popular_posts_orderby', array(
			'date'		=> __('Date published', 'trx_addons'),
			'title'		=> __('Post title', 'trx_addons'),
			'likes'		=> __('Likes number', 'trx_addons'),
			'views'		=> __('Views number', 'trx_addons'),
			'comments'	=> __('Comments number', 'trx_addons'),
			'rand' 		=> __('Random', 'trx_addons')
		));
	}
}

// Return list of the order options
if ( !function_exists( 'trx_addons_get_list_sc_query_orders' ) ) {
	function trx_addons_get_list_sc_query_orders() {
		return apply_filters('trx_addons_filter_get_list_sc_query_orders', array(
			'desc' => esc_html__('Descending', 'trx_addons'),
			'asc' => esc_html__('Ascending', 'trx_addons')
		));
	}
}

// Return list of the directions
if ( !function_exists( 'trx_addons_get_list_sc_directions' ) ) {
	function trx_addons_get_list_sc_directions() {
		return apply_filters('trx_addons_filter_get_list_sc_directions', array(
			'horizontal' => esc_html__('Horizontal', 'trx_addons'),
			'vertical' => esc_html__('Vertical', 'trx_addons')
		));
	}
}

// Return list of the mouse events
if ( !function_exists( 'trx_addons_get_list_sc_mouse_events' ) ) {
	function trx_addons_get_list_sc_mouse_events() {
		return apply_filters('trx_addons_filter_get_list_sc_mouse_events', array(
			'drag' => esc_html__('Drag', 'trx_addons'),
			'move' => esc_html__('Move', 'trx_addons')
		));
	}
}

// Return list of the element positions
if ( !function_exists( 'trx_addons_get_list_sc_positions' ) ) {
	function trx_addons_get_list_sc_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_positions', array(
			'tl' => esc_html__('Top Left', 'trx_addons'),
			'tc' => esc_html__('Top Center', 'trx_addons'),
			'tr' => esc_html__('Top Right', 'trx_addons'),
			'ml' => esc_html__('Middle Left', 'trx_addons'),
			'mc' => esc_html__('Middle Center', 'trx_addons'),
			'mr' => esc_html__('Middle Right', 'trx_addons'),
			'bl' => esc_html__('Bottom Left', 'trx_addons'),
			'bc' => esc_html__('Bottom Center', 'trx_addons'),
			'br' => esc_html__('Bottom Right', 'trx_addons')
		));
	}
}

// Return list of the image positions in the blogger
if ( !function_exists( 'trx_addons_get_list_sc_blogger_image_positions' ) ) {
	function trx_addons_get_list_sc_blogger_image_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_blogger_image_positions', array(
			'top' => esc_html__('Top', 'trx_addons'),
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons'),
			'alter' => esc_html__('Alternation', 'trx_addons'),
		));
	}
}

// Return list of the image ratio
if ( !function_exists( 'trx_addons_get_list_sc_image_ratio' ) ) {
	function trx_addons_get_list_sc_image_ratio( $masonry = true, $none = true ) {
		$list = apply_filters( 'trx_addons_filter_get_list_sc_image_ratio', array(
			'none' => esc_html__('Default', 'trx_addons'),
			'masonry' => esc_html__('Masonry', 'trx_addons'),
			'2:1' => esc_html__('2:1', 'trx_addons'),
			'17:9' => esc_html__('17:9', 'trx_addons'),
			'16:9' => esc_html__('16:9', 'trx_addons'),
			'4:3' => esc_html__('4:3', 'trx_addons'),
			'1:1' => esc_html__('1:1', 'trx_addons'),
			'3:4' => esc_html__('3:4', 'trx_addons'),
			'9:16' => esc_html__('9:16', 'trx_addons'),
			'9:17' => esc_html__('9:17', 'trx_addons'),
			'1:2' => esc_html__('1:2', 'trx_addons'),
		) );
		if ( ! $masonry ) unset( $list['masonry'] );
		if ( ! $none ) unset( $list['none'] );
		return $list;
	}
}

// Return list of the image hover styles
if ( !function_exists( 'trx_addons_get_list_sc_image_hover' ) ) {
	function trx_addons_get_list_sc_image_hover() {
		return apply_filters('trx_addons_filter_get_list_sc_image_hover', array(
			'inherit' => esc_html__('Inherit', 'trx_addons'),
			'none' => esc_html__('No hover', 'trx_addons'),
			'info' => esc_html__('Info', 'trx_addons'),
			'links' => esc_html__('Links', 'trx_addons'),
			'plain' => esc_html__('Plain', 'trx_addons'),
			'wide' => esc_html__('Wide', 'trx_addons'),
			'zoomin' => esc_html__('Zoom In', 'trx_addons'),
		));
	}
}

// Return list of the button's sizes
if ( !function_exists( 'trx_addons_get_list_sc_button_sizes' ) ) {
	function trx_addons_get_list_sc_button_sizes() {
		return apply_filters('trx_addons_filter_get_list_sc_button_sizes', array(
			'normal' => esc_html__('Normal', 'trx_addons'),
			'small' => esc_html__('Small', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the content's widths
if ( !function_exists( 'trx_addons_get_list_sc_content_widths' ) ) {
	function trx_addons_get_list_sc_content_widths($none_key = 'none', $with_percents=true) {
		$list = array(
			$none_key => esc_html__('Default', 'trx_addons'),
			'1_1' => esc_html__('Full width', 'trx_addons'),
			'1_2' => esc_html__('1/2 of page', 'trx_addons'),
			'1_3' => esc_html__('1/3 of page', 'trx_addons'),
			'2_3' => esc_html__('2/3 of page', 'trx_addons'),
			'1_4' => esc_html__('1/4 of page', 'trx_addons'),
			'3_4' => esc_html__('3/4 of page', 'trx_addons'),
		);
		if ($with_percents) {
			$list = array_merge($list, array(
				'100p'=> esc_html__('100% of container', 'trx_addons'),
				'90p' => esc_html__('90% of container', 'trx_addons'),
				'80p' => esc_html__('80% of container', 'trx_addons'),
				'75p' => esc_html__('75% of container', 'trx_addons'),
				'70p' => esc_html__('70% of container', 'trx_addons'),
				'60p' => esc_html__('60% of container', 'trx_addons'),
				'50p' => esc_html__('50% of container', 'trx_addons'),
				'45p' => esc_html__('45% of container', 'trx_addons'),
				'40p' => esc_html__('40% of container', 'trx_addons'),
				'30p' => esc_html__('30% of container', 'trx_addons'),
				'25p' => esc_html__('25% of container', 'trx_addons'),
				'20p' => esc_html__('20% of container', 'trx_addons'),
				'15p' => esc_html__('15% of container', 'trx_addons'),
				'10p' => esc_html__('10% of container', 'trx_addons'),
			));
		}
		return apply_filters('trx_addons_filter_get_list_sc_content_widths', $list);
	}
}

// Return list of the content's paddings and margins sizes
if ( !function_exists( 'trx_addons_get_list_sc_content_paddings_and_margins' ) ) {
	function trx_addons_get_list_sc_content_paddings_and_margins($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_content_paddings_and_margins', array(
			$none_key	=> esc_html__('None', 'trx_addons'),
			'tiny'		=> esc_html__('Tiny', 'trx_addons'),
			'small'		=> esc_html__('Small', 'trx_addons'),
			'medium'	=> esc_html__('Medium', 'trx_addons'),
			'large'		=> esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the content's push and pull sizes
if ( !function_exists( 'trx_addons_get_list_sc_content_push_and_pull' ) ) {
	function trx_addons_get_list_sc_content_push_and_pull($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_content_push_and_pull', array(
			$none_key => esc_html__('None', 'trx_addons'),
			'tiny' => esc_html__('Tiny', 'trx_addons'),
			'small' => esc_html__('Small', 'trx_addons'),
			'medium' => esc_html__('Medium', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the shift sizes to move content along X- and/or Y-axis
if ( !function_exists( 'trx_addons_get_list_sc_content_shift' ) ) {
	function trx_addons_get_list_sc_content_shift($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_content_shift', array(
			$none_key => esc_html__('None', 'trx_addons'),
			'tiny' => esc_html__('Tiny', 'trx_addons'),
			'small' => esc_html__('Small', 'trx_addons'),
			'medium' => esc_html__('Medium', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons'),
			'tiny_negative' => esc_html__('Tiny (negative)', 'trx_addons'),
			'small_negative' => esc_html__('Small (negative)', 'trx_addons'),
			'medium_negative' => esc_html__('Medium (negative)', 'trx_addons'),
			'large_negative' => esc_html__('Large (negative)', 'trx_addons')
		));
	}
}

// Return list of the bg sizes to oversize content area
if ( !function_exists( 'trx_addons_get_list_sc_content_extra_bg' ) ) {
	function trx_addons_get_list_sc_content_extra_bg($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_content_extra_bg', array(
			$none_key => esc_html__('None', 'trx_addons'),
			'tiny' => esc_html__('Tiny', 'trx_addons'),
			'small' => esc_html__('Small', 'trx_addons'),
			'medium' => esc_html__('Medium', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the bg mask values to color tone of the bg image
if ( !function_exists( 'trx_addons_get_list_sc_content_extra_bg_mask' ) ) {
	function trx_addons_get_list_sc_content_extra_bg_mask($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_content_extra_bg_mask', array(
			$none_key => esc_html__('None', 'trx_addons'),
			'bg_color' => esc_html__('Use bg color', 'trx_addons'),
			'1'  => esc_html__('10%', 'trx_addons'),
			'2'  => esc_html__('20%', 'trx_addons'),
			'3'  => esc_html__('30%', 'trx_addons'),
			'4'  => esc_html__('40%', 'trx_addons'),
			'5'  => esc_html__('50%', 'trx_addons'),
			'6'  => esc_html__('60%', 'trx_addons'),
			'7'  => esc_html__('70%', 'trx_addons'),
			'8'  => esc_html__('80%', 'trx_addons'),
			'9'  => esc_html__('90%', 'trx_addons')
		));
	}
}

// Return list of the empty_space heights
if ( !function_exists( 'trx_addons_get_list_sc_empty_space_heights' ) ) {
	function trx_addons_get_list_sc_empty_space_heights($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_empty_space_heights', array(
					'tiny' => esc_html__('Tiny', 'trx_addons'),
					'small' => esc_html__('Small', 'trx_addons'),
					'medium' => esc_html__('Medium', 'trx_addons'),
					'large' => esc_html__('Large', 'trx_addons'),
					'huge' => esc_html__('Huge', 'trx_addons'),
					$none_key => esc_html__('From the value above', 'trx_addons'),
		));
	}
}

// Return list of the googlemap styles
if ( !function_exists( 'trx_addons_get_list_sc_googlemap_styles' ) ) {
	function trx_addons_get_list_sc_googlemap_styles() {
		return apply_filters('trx_addons_filter_sc_googlemap_styles', array(
			'default' => esc_html__('Default', 'trx_addons'),
			'greyscale' => esc_html__('Greyscale', 'trx_addons'),
			'inverse' => esc_html__('Inverse', 'trx_addons'),
			'simple' => esc_html__('Simple', 'trx_addons')
		));
	}
}

// Return list of the googlemap animations
if ( !function_exists( 'trx_addons_get_list_sc_googlemap_animations' ) ) {
	function trx_addons_get_list_sc_googlemap_animations() {
		return apply_filters('trx_addons_filter_sc_googlemap_animations', array(
			'none' => esc_html__('None', 'trx_addons'),
			'drop' => esc_html__('Drop', 'trx_addons'),
			'bounce' => esc_html__('Bounce', 'trx_addons')
		));
	}
}

// Return list of the osmap styles
if ( !function_exists( 'trx_addons_get_list_sc_osmap_tilers' ) ) {
	function trx_addons_get_list_sc_osmap_tilers() {
		return apply_filters('trx_addons_filter_sc_osmap_tilers', array(
			'vector' => esc_html__('Vector', 'trx_addons'),
			'raster' => esc_html__('Raster', 'trx_addons')
		));
	}
}

// Return list of the osmap styles
if ( !function_exists( 'trx_addons_get_list_sc_osmap_styles' ) ) {
	function trx_addons_get_list_sc_osmap_styles($full = false) {
		$tilers = trx_addons_get_option( 'api_openstreet_tiler_' . trx_addons_get_option('api_openstreet_tiler') );
		$styles = array();
		if ( is_array( $tilers ) ) {
			foreach( $tilers as $t ) {
				if ( ! empty( $t['title'] ) && ! empty( $t['slug'] ) && ! empty( $t['url'] ) ) {
					$styles[ $t['slug'] ] = $full ? $t : $t['title'];
				}
			}
		}
		return apply_filters('trx_addons_filter_sc_osmap_styles', $styles, $full);
	}
}

// Return list of the mouse events
if ( !function_exists( 'trx_addons_get_list_sc_icompare_handlers' ) ) {
	function trx_addons_get_list_sc_icompare_handlers() {
		return apply_filters('trx_addons_filter_get_list_sc_icompare_handlers', array(
			'round' => esc_html__('Round', 'trx_addons'),
			'square' => esc_html__('Square', 'trx_addons')
		));
	}
}

// Return list of the icon's positions
if ( !function_exists( 'trx_addons_get_list_sc_icon_positions' ) ) {
	function trx_addons_get_list_sc_icon_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_icon_positions', array(
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons'),
			'top' => esc_html__('Top', 'trx_addons')
		));
	}
}

// Return list of the icon's sizes
if ( !function_exists( 'trx_addons_get_list_sc_icon_sizes' ) ) {
	function trx_addons_get_list_sc_icon_sizes() {
		return apply_filters('trx_addons_filter_get_list_sc_icon_sizes', array(
			'small' => esc_html__('Small', 'trx_addons'),
			'medium' => esc_html__('Medium', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the Instagram redirects
if ( !function_exists( 'trx_addons_get_list_sc_instagram_redirects' ) ) {
	function trx_addons_get_list_sc_instagram_redirects() {
		return apply_filters('trx_addons_filter_get_list_sc_instagram_redirects', array(
			'none' => esc_html__('No links', 'trx_addons'),
			'popup' => esc_html__('Popup', 'trx_addons'),
			'instagram' => esc_html__('Instagram', 'trx_addons')
		));
	}
}

// Return list of the Twitter APIs
if ( !function_exists( 'trx_addons_get_list_sc_twitter_api' ) ) {
	function trx_addons_get_list_sc_twitter_api() {
		return apply_filters('trx_addons_filter_get_list_sc_twitter_api', array(
				'bearer' => __( 'Bearer token (new API)', 'trx_addons' ),
				'token'  => __( 'Token (old API)', 'trx_addons' ),
				'embed'  => __( 'Embed timeline', 'trx_addons' ),
		));
	}
}

// Return list of the hotspot sources
if ( !function_exists( 'trx_addons_get_list_sc_hotspot_sources' ) ) {
	function trx_addons_get_list_sc_hotspot_sources() {
		$list = array(
			'custom' => esc_html__('Custom', 'trx_addons'),
			'post' => esc_html__('Post', 'trx_addons')
		);
		return apply_filters( 'trx_addons_filter_get_list_sc_hotspot_sources', $list );
	}
}

// Return list of the hotspot post parts
if ( !function_exists( 'trx_addons_get_list_sc_hotspot_post_parts' ) ) {
	function trx_addons_get_list_sc_hotspot_post_parts() {
		$list = array(
			'image' => esc_html__('Image', 'trx_addons'),
			'title' => esc_html__('Title', 'trx_addons'),
			'category' => esc_html__('Category', 'trx_addons'),
			'excerpt' => esc_html__('Excerpt', 'trx_addons'),
			'price' => esc_html__('Price', 'trx_addons'),
		);
		return apply_filters( 'trx_addons_filter_get_list_sc_hotspot_post_parts', $list );
	}
}

// Return list of the hotspot symbols
if ( !function_exists( 'trx_addons_get_list_sc_hotspot_symbols' ) ) {
	function trx_addons_get_list_sc_hotspot_symbols() {
		$list = array(
			'none'   => esc_html__('None', 'trx_addons'),
			'icon'   => esc_html__('Icon', 'trx_addons'),
			'image'  => esc_html__('Image', 'trx_addons'),
			'number' => esc_html__('Number', 'trx_addons'),
			'custom' => esc_html__('Custom', 'trx_addons'),
		);
		return apply_filters( 'trx_addons_filter_get_list_sc_hotspot_symbols', $list );
	}
}

// Return list of the smoke spot motions
if ( !function_exists( 'trx_addons_get_list_sc_smoke_spot_motions' ) ) {
	function trx_addons_get_list_sc_smoke_spot_motions() {
		return apply_filters( 'trx_addons_filter_get_list_sc_smoke_spot_motions', array(
			0 => esc_html__('None', 'trx_addons'),
			1 => esc_html__('Slow', 'trx_addons'),
			2 => esc_html__('Fast', 'trx_addons')
		) );
	}
}

// Return list of the smoke spot shapes
if ( !function_exists( 'trx_addons_get_list_sc_smoke_spot_shapes' ) ) {
	function trx_addons_get_list_sc_smoke_spot_shapes() {
		return apply_filters( 'trx_addons_filter_get_list_sc_smoke_spot_shapes', array(
			'1' => esc_html__('Shape 1', 'trx_addons'),
			'2' => esc_html__('Shape 2', 'trx_addons')
		) );
	}
}

// Return list of the socials types
if ( !function_exists( 'trx_addons_get_list_sc_socials_types' ) ) {
	function trx_addons_get_list_sc_socials_types() {
		return apply_filters('trx_addons_filter_get_list_sc_socials_types', array(
			'socials' => esc_html__('Social profiles', 'trx_addons'),
			'share' => esc_html__('Share links', 'trx_addons'),
		));
	}
}

// Return list of the image positions
if ( !function_exists( 'trx_addons_get_list_sc_promo_positions' ) ) {
	function trx_addons_get_list_sc_promo_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_promo_positions', array(
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		));
	}
}

// Return list of the promo's sizes
if ( !function_exists( 'trx_addons_get_list_sc_promo_sizes' ) ) {
	function trx_addons_get_list_sc_promo_sizes() {
		return apply_filters('trx_addons_filter_get_list_sc_promo_sizes', array(
			'tiny' => esc_html__('Tiny', 'trx_addons'),
			'small' => esc_html__('Small', 'trx_addons'),
			'normal' => esc_html__('Normal', 'trx_addons'),
			'large' => esc_html__('Large', 'trx_addons')
		));
	}
}

// Return list of the promo text area's widths
if ( !function_exists( 'trx_addons_get_list_sc_promo_widths' ) ) {
	function trx_addons_get_list_sc_promo_widths($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_promo_widths', array(
			$none_key => esc_html__('Default', 'trx_addons'),
			'1_1' => esc_html__('1/1', 'trx_addons'),
			'1_2' => esc_html__('1/2', 'trx_addons'),
			'1_3' => esc_html__('1/3', 'trx_addons'),
			'2_3' => esc_html__('2/3', 'trx_addons'),
			'1_4' => esc_html__('1/4', 'trx_addons'),
			'3_4' => esc_html__('3/4', 'trx_addons')
		));
	}
}

// Return list of the skills counter styles
if ( !function_exists( 'trx_addons_get_list_sc_skills_counter_styles' ) ) {
	function trx_addons_get_list_sc_skills_counter_styles() {
		return apply_filters('trx_addons_filter_get_list_sc_skills_counter_styles', array(
			'counter'  => esc_html__('Counter', 'trx_addons'),
			'odometer' => esc_html__('Odometer', 'trx_addons'),
		));
	}
}

// Return list of the skills counter aligns
if ( !function_exists( 'trx_addons_get_list_sc_skills_counter_icon_positions' ) ) {
	function trx_addons_get_list_sc_skills_counter_icon_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_skills_counter_icon_positions', array(
			'top'   => esc_html__('Top', 'trx_addons'),
			'left'  => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons'),
		));
	}
}

// Return list of the featured image position in dishes
if ( !function_exists( 'trx_addons_get_list_sc_dishes_positions' ) ) {
	function trx_addons_get_list_sc_dishes_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_dishes_positions', array(
			'top' => esc_html__('Top', 'trx_addons'),
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		));
	}
}

// Return list of the featured elements in services
if ( !function_exists( 'trx_addons_get_list_sc_services_featured' ) ) {
	function trx_addons_get_list_sc_services_featured($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_services_featured', array(
			'image'		=> esc_html__('Image', 'trx_addons'),
			'pictogram'	=> esc_html__('Pictogram', 'trx_addons'),
			'icon'		=> esc_html__('Icon', 'trx_addons'),
			'number'	=> esc_html__('Number', 'trx_addons'),
			$none_key	=> esc_html__('None', 'trx_addons')
		));
	}
}

// Return list of positions of the featured element in services
if ( !function_exists( 'trx_addons_get_list_sc_services_featured_positions' ) ) {
	function trx_addons_get_list_sc_services_featured_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_services_featured_positions', array(
			'top' => esc_html__('Top', 'trx_addons'),
			'bottom' => esc_html__('Bottom', 'trx_addons'),
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		));
	}
}

// Return list of the tabs effects in services
if ( !function_exists( 'trx_addons_get_list_sc_services_tabs_effects' ) ) {
	function trx_addons_get_list_sc_services_tabs_effects() {
		return apply_filters('trx_addons_filter_get_list_sc_services_tabs_effects', array(
			'fade' => esc_html__('Fade', 'trx_addons'),
			'slide' => esc_html__('Slide', 'trx_addons'),
			'flip' => esc_html__('Page flip', 'trx_addons')
		));
	}
}

// Return list of the main matches position in sport
if ( !function_exists( 'trx_addons_get_list_sc_matches_positions' ) ) {
	function trx_addons_get_list_sc_matches_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_matches_positions', array(
			'top' => esc_html__('Top', 'trx_addons'),
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		));
	}
}


// Return list of the layout types
if ( !function_exists( 'trx_addons_get_list_layout_types' ) ) {
	function trx_addons_get_list_layout_types() {
		return apply_filters( 'trx_addons_filter_layout_types', array(
					'header'  => esc_html__('Header', 'trx_addons'),
					'footer'  => esc_html__('Footer', 'trx_addons'),
					'sidebar' => esc_html__('Sidebar', 'trx_addons'),
					'blog'    => esc_html__('Blog', 'trx_addons'),
					'custom'  => esc_html__('Custom', 'trx_addons')
				) );
	}
}

// Return list of the menu layouts
if ( !function_exists( 'trx_addons_get_list_sc_layouts_type' ) ) {
	function trx_addons_get_list_sc_layouts_type() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_type', array(
			'default' => esc_html__('Default', 'trx_addons'),
			'popup' => esc_html__('Popup', 'trx_addons'),
			'panel' => esc_html__('Panel', 'trx_addons')
		));
	}
}

// Return list of the panel's positions
if ( !function_exists( 'trx_addons_get_list_sc_layouts_panel_positions' ) ) {
	function trx_addons_get_list_sc_layouts_panel_positions() {
		return apply_filters('trx_addons_get_list_sc_layouts_panel_positions', array(
			"left"		=> esc_html__('Left', 'trx_addons'),
			"right"		=> esc_html__('Right', 'trx_addons'),
			"top"		=> esc_html__('Top', 'trx_addons'),
			"bottom"	=> esc_html__('Bottom', 'trx_addons')
		));
	}
}

// Return list of the panel's effects
if ( !function_exists( 'trx_addons_get_list_sc_layouts_panel_effects' ) ) {
	function trx_addons_get_list_sc_layouts_panel_effects() {
		return apply_filters('trx_addons_get_list_sc_layouts_panel_effects', array(
			"slide"		=> esc_html__('Slide', 'trx_addons'),
			"flip"		=> esc_html__('Flip In', 'trx_addons'),
			"flipout"	=> esc_html__('Flip Out', 'trx_addons'),
		));
	}
}

// Return list of the flag's positions in the language switcher
if ( !function_exists( 'trx_addons_get_list_sc_layouts_language_positions' ) ) {
	function trx_addons_get_list_sc_layouts_language_positions($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_language_positions', array(
			$none_key	=> esc_html__('Hide', 'trx_addons'),
			"title"		=> esc_html__('Only in the title', 'trx_addons'),
			"menu"		=> esc_html__('Only in the menu', 'trx_addons'),
			"both"		=> esc_html__('Both', 'trx_addons')
		));
	}
}

// Return list of the parts of the language switcher
if ( !function_exists( 'trx_addons_get_list_sc_layouts_language_parts' ) ) {
	function trx_addons_get_list_sc_layouts_language_parts($none_key = 'none') {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_language_parts', array(
			$none_key	=> esc_html__('Hide', 'trx_addons'),
			"name"		=> esc_html__('Language name', 'trx_addons'),
			"code"		=> esc_html__('Language code', 'trx_addons')
		));
	}
}

// Return list of the menu layouts
if ( !function_exists( 'trx_addons_get_list_sc_layouts_menu' ) ) {
	function trx_addons_get_list_sc_layouts_menu() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_menu', array(
			'default' => esc_html__('Default', 'trx_addons'),
			'burger' => esc_html__('Burger', 'trx_addons')
		));
	}
}

// Return list of the blog item parts
if ( !function_exists( 'trx_addons_get_list_sc_layouts_blog_item_parts' ) ) {
	function trx_addons_get_list_sc_layouts_blog_item_parts() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_blog_item_parts', array(
			'title' => esc_html__('Post title', 'trx_addons'),
			'featured' => esc_html__('Featured image', 'trx_addons'),
			'meta' => esc_html__('Post meta', 'trx_addons'),
			'excerpt' => esc_html__('Excerpt', 'trx_addons'),
			'content' => esc_html__('Full content', 'trx_addons'),
			'custom' => esc_html__('Custom field', 'trx_addons'),
			'button' => esc_html__('Button', 'trx_addons'),
		));
	}
}

// Return list of the meta layouts
if ( !function_exists( 'trx_addons_get_list_sc_layouts_meta' ) ) {
	function trx_addons_get_list_sc_layouts_meta() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_meta', array(
			'default' => esc_html__('Default', 'trx_addons'),
		));
	}
}

// Return list of the search layouts
if ( !function_exists( 'trx_addons_get_list_sc_layouts_search' ) ) {
	function trx_addons_get_list_sc_layouts_search() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_search', array(
			'normal' => esc_html__('Normal', 'trx_addons'),
			'expand' => esc_html__('Expand', 'trx_addons'),
			'fullscreen' => esc_html__('Fullscreen', 'trx_addons')
		));
	}
}

// Return list of the layouts row types
if ( !function_exists( 'trx_addons_get_list_sc_layouts_row_types' ) ) {
	function trx_addons_get_list_sc_layouts_row_types() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_row_types', array(
			'inherit' => esc_html__('Inherit', 'trx_addons'),
			'narrow' => esc_html__('Narrow', 'trx_addons'),
			'compact' => esc_html__('Compact', 'trx_addons'),
			'normal' => esc_html__('Normal', 'trx_addons')
		));
	}
}

// Return list of the icon positions in the layout items
if ( !function_exists( 'trx_addons_get_list_sc_layouts_icons_positions' ) ) {
	function trx_addons_get_list_sc_layouts_icons_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_icons_positions', array(
			'left' => esc_html__('Left', 'trx_addons'),
			'right' => esc_html__('Right', 'trx_addons')
		));
	}
}

// Return list of the cart types
if ( !function_exists( 'trx_addons_get_list_sc_layouts_cart_types' ) ) {
	function trx_addons_get_list_sc_layouts_cart_types() {
		return apply_filters('trx_addons_filter_get_list_sc_layouts_cart_types', array(
			'default' => esc_html__('Default', 'trx_addons'),
			'panel'   => esc_html__('Panel', 'trx_addons'),
			'button'  => esc_html__('Button', 'trx_addons'),
		));
	}
}

// Return list of the content types
if ( !function_exists( 'trx_addons_get_list_content_types' ) ) {
	function trx_addons_get_list_content_types() {
		return apply_filters('trx_addons_filter_get_list_content_types', array(
			'section'  => esc_html__('Section ID', 'trx_addons'),
			'layout'   => esc_html__('Custom layout', 'trx_addons'),
			'template' => esc_html__('Saved template', 'trx_addons'),
		));
	}
}

// Return list of the hscroll bullets positions
if ( !function_exists( 'trx_addons_get_list_sc_hscroll_bullets_positions' ) ) {
	function trx_addons_get_list_sc_hscroll_bullets_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_hscroll_bullets_positions', array(
			'left'   => esc_html__('Left', 'trx_addons'),
			'right'  => esc_html__('Right', 'trx_addons'),
			'bottom' => esc_html__('Bottom', 'trx_addons'),
		));
	}
}

// Return list of the hscroll numbers positions
if ( !function_exists( 'trx_addons_get_list_sc_hscroll_numbers_positions' ) ) {
	function trx_addons_get_list_sc_hscroll_numbers_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_hscroll_numbers_positions', array(
			'left'   => esc_html__('Left', 'trx_addons'),
			'right'  => esc_html__('Right', 'trx_addons'),
			'center' => esc_html__('Center', 'trx_addons'),
		));
	}
}

// Return list of the hscroll progress bar positions
if ( !function_exists( 'trx_addons_get_list_sc_hscroll_progress_positions' ) ) {
	function trx_addons_get_list_sc_hscroll_progress_positions() {
		return apply_filters('trx_addons_filter_get_list_sc_hscroll_progress_positions', array(
			'top'    => esc_html__('Top', 'trx_addons'),
			'left'   => esc_html__('Left', 'trx_addons'),
			'right'  => esc_html__('Right', 'trx_addons'),
			'bottom' => esc_html__('Bottom', 'trx_addons'),
		));
	}
}

// Return list of the switcher effects
if ( !function_exists( 'trx_addons_get_list_sc_switcher_effects' ) ) {
	function trx_addons_get_list_sc_switcher_effects() {
		return apply_filters('trx_addons_filter_get_list_sc_switcher_effects', array(
			'swap'  => esc_html__('Swap', 'trx_addons'),
			'slide' => esc_html__('Slide', 'trx_addons'),
			'fade'  => esc_html__('Fade', 'trx_addons'),
		));
	}
}


// Return list of the video player layouts
if ( !function_exists( 'trx_addons_get_list_widget_video_layouts' ) ) {
	function trx_addons_get_list_widget_video_layouts() {
		return apply_filters('trx_addons_filter_get_list_widget_video_layouts', array(
			'default' => esc_html__('Default', 'trx_addons'),
			'hover'   => esc_html__('Hover', 'trx_addons'),
		));
	}
}
