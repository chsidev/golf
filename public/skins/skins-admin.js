/* global jQuery:false */
/* global N7_GOLF_CLUB_STORAGE:false */

jQuery( document ).ready( function() {

	"use strict";

	var busy = false;

	// Switch an active skin
	jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_choose_skin' ).on(
		'click', function(e) {
			if ( ! busy ) {
				var link = jQuery( this );
				trx_addons_msgbox_confirm(
					N7_GOLF_CLUB_STORAGE['msg_switch_skin'],
					N7_GOLF_CLUB_STORAGE['msg_switch_skin_caption'],
					function(btn) {
						if ( btn != 1 ) return;
						n7_golf_club_skins_action( 'switch', link.data( 'skin' ) );
					}
				);
			}
			e.preventDefault();
			return false;
		}
	);

	// Delete a skin
	jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_delete_skin' ).on(
		'click', function(e) {
			if ( ! busy ) {
				var link = jQuery( this );
				var msgbox = typeof window.trx_addons_msgbox_agree != 'undefined' ? trx_addons_msgbox_agree : trx_addons_msgbox_confirm;
				msgbox(
					N7_GOLF_CLUB_STORAGE['msg_delete_skin'],
					N7_GOLF_CLUB_STORAGE['msg_delete_skin_caption'],
					function(btn) {
						if ( btn != 1 ) return;
						n7_golf_club_skins_action( 'delete', link.data( 'skin' ), '', link );
					}
				);
			}
			e.preventDefault();
			return false;
		}
	);

	// Download a free skin
	jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_download_skin' ).on(
		'click', function(e) {
			if ( ! busy ) {
				var link = jQuery( this );
				trx_addons_msgbox_confirm(
					N7_GOLF_CLUB_STORAGE['msg_download_skin'],
					N7_GOLF_CLUB_STORAGE['msg_download_skin_caption'],
					function(btn) {
						if ( btn != 1 ) return;
						n7_golf_club_skins_action( 'download', link.data( 'skin' ), '', link );
					}
				);
			}
			e.preventDefault();
			return false;
		}
	);

	// Download a prepaid skin
	jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_buy_skin' ).on(
		'click', function(e) {
			if ( ! busy ) {
				var link = jQuery( this );
				trx_addons_msgbox_dialog(
					'<p>' + N7_GOLF_CLUB_STORAGE['msg_buy_skin'].replace('#', link.data('buy')) + '</p>'
					+ '<p><label><input class="n7_golf_club_skin_code" type="text" placeholder="' + N7_GOLF_CLUB_STORAGE['msg_buy_skin_placeholder'] + '"></label></p>',
					N7_GOLF_CLUB_STORAGE['msg_buy_skin_caption'],
					null,
					function(btn, dialog) {
						if ( btn != 1 ) return;
						n7_golf_club_skins_action( 'buy', link.data( 'skin' ), dialog.find('.n7_golf_club_skin_code').val(), link );
					}
				);
			}
			e.preventDefault();
			return false;
		}
	);

	// Update skin
	jQuery( '#trx_addons_theme_panel_section_skins a.trx_addons_image_block_link_update_skin' ).on(
		'click', function(e) {
			if ( ! busy ) {
				var link = jQuery( this );
				trx_addons_msgbox_confirm(
					N7_GOLF_CLUB_STORAGE['msg_update_skin'],
					N7_GOLF_CLUB_STORAGE['msg_update_skin_caption'],
					function(btn) {
						if ( btn != 1 ) return;
						n7_golf_club_skins_action( 'update', link.data( 'skin' ), '', link );
					}
				);
			}
			e.preventDefault();
			return false;
		}
	);

	// Update skins from 'update-core' screen
	var need_update = false,
		errors = 0;
	jQuery( '.n7_golf_club_upgrade_skins_button:not([disabled])' ).on(
		'click', function(e) {
			var button = jQuery(this),
				checked = button.parents( '.n7_golf_club_upgrade_skins' ).find( 'input[name="checked[]"]:checked' );
			if ( checked.length > 0 ) {
				if ( need_update === false ) {
					need_update = checked.length;
				}
				jQuery( '.n7_golf_club_upgrade_skins_button' ).attr( 'disabled', 'disabled' );
				var chk = checked.eq(0);
				if ( ! chk.next().hasClass( 'n7_golf_club_upgrade_skins_status_wrap' ) ) {
					chk.hide();
					chk.after( '<div class="n7_golf_club_upgrade_skins_status_wrap"><span class="n7_golf_club_upgrade_skins_status n7_golf_club_upgrade_skins_status_progress"></span></div>' );
				}
				var status = chk.next().find('.n7_golf_club_upgrade_skins_status');
				n7_golf_club_skins_action( 'update', chk.val(), '', '', function(skin, action, rez) {
					need_update--;
					chk.get(0).checked = false;
					chk.eq(0).removeAttr('checked');
					status
						.removeClass( 'n7_golf_club_upgrade_skins_status_progress' )
						.addClass( 'n7_golf_club_upgrade_skins_status_' + ( rez.error ? 'error' : 'success' ) );
					if ( rez.error ) {
						errors++;
					}
					jQuery( '.n7_golf_club_upgrade_skins_button' ).removeAttr( 'disabled' );
					button.trigger( 'click' );
				} );
			} else {
				if ( need_update === 0 ) {
					jQuery( '.n7_golf_club_upgrade_skins' ).after(
						'<div class="trx_addons_info_box trx_addons_info_box">'
							+ ( errors > 0 ? N7_GOLF_CLUB_STORAGE['msg_update_skins_error'] : N7_GOLF_CLUB_STORAGE['msg_update_skins_result'] )
						+ '</div>'
					);
					jQuery( '.n7_golf_club_upgrade_skins_button' ).removeAttr( 'disabled' );
				}
			}
			e.preventDefault();
			return false;
		}
	);


	// Callback when skin is loaded successful
	function n7_golf_club_skins_action( action, skin, code, button, callback ){
		busy = true;
		if ( button ) {
			button.addClass( 'trx_addons_loading' );
		}
		jQuery.post(
			N7_GOLF_CLUB_STORAGE['ajax_url'], {
				'action': 'n7_golf_club_'+action+'_skin',
				'skin': skin,
				'code': code === undefined ? '' : code,
				'nonce': N7_GOLF_CLUB_STORAGE['ajax_nonce']
			},
			function(response){
				var rez = {};
				if ( button ) {
					button.removeClass( 'trx_addons_loading' );
				}
				if (response === '' || response === 0) {
					rez = { error: N7_GOLF_CLUB_STORAGE['msg_ajax_error'] };
				} else {
					try {
						rez = JSON.parse( response );
					} catch (e) {
						rez = { error: N7_GOLF_CLUB_STORAGE['msg_ajax_error'] };
						console.log( response );
					}
				}
				if ( callback !== undefined ) {
					callback(skin, action, rez);
				}
				// Show result
				if ( jQuery('.trx_addons_theme_panel').length > 0 ) {
					if ( rez.error ) {
						trx_addons_msgbox_warning( rez.error, N7_GOLF_CLUB_STORAGE['msg_'+action+'_skin_error_caption'] );
					} else {
						trx_addons_msgbox_success( typeof rez.success != 'undefined' && rez.success ? rez.success : N7_GOLF_CLUB_STORAGE['msg_'+action+'_skin_success'], N7_GOLF_CLUB_STORAGE['msg_'+action+'_skin_success_caption'] );
					}
					// Reload current page after the skin is switched (if success)
					if ( rez.error === '' ) {
						if (jQuery('.trx_addons_theme_panel .trx_addons_tabs').hasClass('trx_addons_panel_wizard')) {
							var prev_tab_id = jQuery( '#trx_addons_theme_panel_section_skins' ).prev().attr( 'id' );
							trx_addons_set_cookie( 'trx_addons_theme_panel_wizard_section', prev_tab_id && action != 'switch' ? prev_tab_id : 'trx_addons_theme_panel_section_skins' );
						} else {
							if ( location.hash != 'trx_addons_theme_panel_section_skins' ) {
								n7_golf_club_document_set_location( location.href.split('#')[0] + '#' + 'trx_addons_theme_panel_section_skins' );
							}
						}
						location.reload( true );
					}
				}
				busy = false;
			}
		);
	}

} );
