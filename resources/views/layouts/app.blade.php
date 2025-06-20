<!DOCTYPE html>
<html lang="en-US" class="js scheme_default" style="--fixed-rows-height:0px;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')

            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link property="stylesheet" rel="stylesheet" id="n7-golf-club-font-google_fonts-css"
            href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;subset=latin,latin-ext&amp;display=swap"
            type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/css/font-icons/css/fontello.css" type="text/css"
            media="all">
        <link property="stylesheet" rel="stylesheet" href="/css/frontend.min.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/plugins_wp/trx_addons/css/__styles.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/plugins_wp/trx_addons/addons/mouse-helper/mouse-helper.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/plugins_wp/booked/dist/booked.css?ver=2.3.5" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/plugins_wp/trx_addons/components/widgets/custom_links/custom_links.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/style.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/css/style.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/css/__plugins.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/css/__custom.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/plugins/mailchimp-for-wp/mailchimp-for-wp.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/plugins/woocommerce/woocommerce.css" type="text/css" media="all">
        <link property="stylesheet" rel="stylesheet" href="/skins/default/plugins/booked/booked.css" type="text/css" media="all">
        <style id="elementor-post-16730">
            .elementor-16730 .elementor-element.elementor-element-a71f409>.elementor-container>.elementor-row>.elementor-column>.elementor-column-wrap>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
            }

            .elementor-16730 .elementor-element.elementor-element-a71f409 {
            padding: 20px 50px 20px 50px;
            }

            .elementor-16730 .elementor-element.elementor-element-8c9975a .logo_image {
            max-height: 85px;
            }

            .elementor-16730 .elementor-element.elementor-element-702f802>.elementor-widget-container {
            margin: 2px 0px 0px 8px;
            }

            .elementor-16730 .elementor-element.elementor-element-c007fc8 .elementor-icon-wrapper {
            text-align: center;
            }

            .elementor-16730 .elementor-element.elementor-element-c007fc8 .elementor-icon i,
            .elementor-16730 .elementor-element.elementor-element-c007fc8 .elementor-icon svg {
            transform: rotate(0deg);
            }

            .elementor-16730 .elementor-element.elementor-element-c007fc8>.elementor-widget-container {
            margin: 0px 0px -6px 5px;
            }

            .elementor-16730 .elementor-element.elementor-element-7592fcc>.elementor-container>.elementor-row>.elementor-column>.elementor-column-wrap>.elementor-widget-wrap {
            align-content: center;
            align-items: center;
            }

            .elementor-16730 .elementor-element.elementor-element-fd77838 .logo_image {
            max-height: 50px;
            }

            .elementor-16730 .elementor-element.elementor-element-5572430>.elementor-widget-container {
            margin: 1px 0px 0px 0px;
            }

            .elementor-16730 .elementor-element.elementor-element-7a52a5c>.elementor-widget-container {
            margin: 0px 0px 0px 0px;
            }

            .elementor-16730 .elementor-element.elementor-element-d001020 {
            --spacer-size: 90px;
            }

            .elementor-16730 .elementor-element.elementor-element-8c84b20 .sc_layouts_title {
            min-height: 0px;
            }

            .elementor-16730 .elementor-element.elementor-element-68d9535 {
            --spacer-size: 35px;
            }

            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon-wrapper {
            text-align: center;
            }

            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon {
            font-size: 15px;
            }

            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon i,
            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon svg {
            transform: rotate(0deg);
            }

            @media(max-width:1024px) {
            .elementor-16730 .elementor-element.elementor-element-d001020 {
                --spacer-size: 80px;
            }

            .elementor-16730 .elementor-element.elementor-element-68d9535 {
                --spacer-size: 20px;
            }

            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon {
                font-size: 14px;
            }
            }

            @media(max-width:767px) {
            .elementor-16730 .elementor-element.elementor-element-4b53325 {
                width: 50%;
            }

            .elementor-16730 .elementor-element.elementor-element-1abe2d5 {
                width: 50%;
            }

            .elementor-16730 .elementor-element.elementor-element-d001020 {
                --spacer-size: 30px;
            }

            .elementor-16730 .elementor-element.elementor-element-68d9535 {
                --spacer-size: 5px;
            }

            .elementor-16730 .elementor-element.elementor-element-bbcd42d .elementor-icon {
                font-size: 12px;
            }
            }
        </style>
        <style id="elementor-post-17907">
            .elementor-17907 .elementor-element.elementor-element-3002112 {
            --spacer-size: 112px;
            }

            .elementor-17907 .elementor-element.elementor-element-678227b .logo_image {
            max-height: 85px;
            }

            .elementor-17907 .elementor-element.elementor-element-678227b>.elementor-widget-container {
            margin: -10px 0px 0px 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-893d886 .sc_item_title_text {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-893d886 .sc_item_title_text2 {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-e27d59a {
            --spacer-size: 20px;
            }

            .elementor-17907 .elementor-element.elementor-element-e6d4252 {
            --spacer-size: 25px;
            }

            .elementor-17907 .elementor-element.elementor-element-9ace7eb {
            --spacer-size: 33px;
            }

            .elementor-17907 .elementor-element.elementor-element-01fb277 .sc_item_title_text {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-01fb277 .sc_item_title_text2 {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-3407239 {
            --spacer-size: 20px;
            }

            .elementor-17907 .elementor-element.elementor-element-cbf8c68 .sc_item_title_text {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-cbf8c68 .sc_item_title_text2 {
            -webkit-text-stroke-width: 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-5d59745 {
            --spacer-size: 85px;
            }

            .elementor-17907 .elementor-element.elementor-element-6e5f57b {
            --divider-border-style: solid;
            --divider-border-width: 1px;
            }

            .elementor-17907 .elementor-element.elementor-element-6e5f57b .elementor-divider-separator {
            width: 100%;
            }

            .elementor-17907 .elementor-element.elementor-element-6e5f57b .elementor-divider {
            padding-top: 10px;
            padding-bottom: 10px;
            }

            .elementor-17907 .elementor-element.elementor-element-eae54d0 {
            --spacer-size: 19px;
            }

            .elementor-17907 .elementor-element.elementor-element-44ca617 {
            --spacer-size: 25px;
            }

            @media(max-width:1024px) and (min-width:768px) {
            .elementor-17907 .elementor-element.elementor-element-47c11eb {
                width: 23%;
            }

            .elementor-17907 .elementor-element.elementor-element-0f1ba52 {
                width: 25%;
            }

            .elementor-17907 .elementor-element.elementor-element-887c61b {
                width: 20%;
            }

            .elementor-17907 .elementor-element.elementor-element-a503a79 {
                width: 32%;
            }
            }

            @media(max-width:1024px) {
            .elementor-17907 .elementor-element.elementor-element-3002112 {
                --spacer-size: 80px;
            }

            .elementor-17907 .elementor-element.elementor-element-5d59745 {
                --spacer-size: 60px;
            }
            }

            @media(max-width:767px) {
            .elementor-17907 .elementor-element.elementor-element-3002112 {
                --spacer-size: 60px;
            }

            .elementor-17907 .elementor-element.elementor-element-678227b>.elementor-widget-container {
                margin: 0px 0px 25px 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-e6d4252 {
                --spacer-size: 15px;
            }

            .elementor-17907 .elementor-element.elementor-element-9ace7eb {
                --spacer-size: 20px;
            }

            .elementor-17907 .elementor-element.elementor-element-01fb277>.elementor-widget-container {
                padding: 35px 0px 0px 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-cbf8c68>.elementor-widget-container {
                padding: 35px 0px 0px 0px;
            }

            .elementor-17907 .elementor-element.elementor-element-5d59745 {
                --spacer-size: 40px;
            }
            }
        </style>
        <script type="text/javascript" src="/js/jquery/jquery.min.js" id="jquery-core-js"></script>
        <script type="text/javascript" src="/js/jquery/jquery-migrate.min.js" id="jquery-migrate-js"></script>
        @stack('styles')
    </head>

    <body>
        <div class="body_wrap">
            <div class="page_wrap">

                @include('layouts.header', ['option'=>isset($header)?$header:null])
                <div class="page_content_wrap">
                    <div class="content_wrap">
                      <div class="content">
                        <a id="content_skip_link_anchor" class="n7_golf_club_skip_link_anchor" href="#"></a>
                            <article id="post-18275" class="post_item_single post_type_page post-18275 page type-page status-publish hentry">
                                <div class="post_content entry-content">

                                @yield('content')

                                </div><!-- .entry-content -->
                            </article>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
        <a href="#" class="trx_addons_scroll_to_top trx_addons_icon-up scroll_to_top_style_default show" title="Scroll to top"></a>
        <div class="trx_addons_mouse_helper trx_addons_mouse_helper_base trx_addons_mouse_helper_style_default trx_addons_mouse_helper_permanent"></div>
        <script type="text/javascript" src="/js/jquery/ui/core.min.js" id="jquery-ui-core-js"></script>
        <script type="text/javascript" defer="defer" src="/js/jquery/ui/datepicker.min.js?ver=1.13.1" id="jquery-ui-datepicker-js"></script>
        <script type="text/javascript" id="jquery-ui-datepicker-js-after">
        jQuery(function(jQuery){jQuery.datepicker.setDefaults({"closeText":"Close","currentText":"Today","monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"nextText":"Next","prevText":"Previous","dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"dateFormat":"M d, yy","firstDay":1,"isRTL":false});});
        </script>
        <script type="text/javascript" src="/plugins_wp/booked/assets/js/spin.min.js?ver=2.0.1" id="booked-spin-js-js"></script>
        <script type="text/javascript" src="/plugins_wp/booked/assets/js/spin.jquery.js?ver=2.0.1" id="booked-spin-jquery-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/booked/assets/js/tooltipster/js/jquery.tooltipster.min.js?ver=3.3.0" id="booked-tooltipster-js"></script>
        <script type="text/javascript" id="booked-functions-js-extra">
        /* <![CDATA[ */
        var booked_js_vars = {"ajax_url":"https:\/\/golfclub.themerex.net\/wp-admin\/admin-ajax.php","profilePage":"","publicAppointments":"","i18n_confirm_appt_delete":"Are you sure you want to cancel this appointment?","i18n_please_wait":"Please wait ...","i18n_wrong_username_pass":"Wrong username\/password combination.","i18n_fill_out_required_fields":"Please fill out all required fields.","i18n_guest_appt_required_fields":"Please enter your name to book an appointment.","i18n_appt_required_fields":"Please enter your name, your email address and choose a password to book an appointment.","i18n_appt_required_fields_guest":"Please fill in all \"Information\" fields.","i18n_password_reset":"Please check your email for instructions on resetting your password.","i18n_password_reset_error":"That username or email is not recognized."};
        /* ]]> */
        </script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/booked/assets/js/functions.js?ver=2.3.5" id="booked-functions-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/advanced-popups/public/js/advanced-popups-public.js?ver=1.1.3" id="advanced-popups-js"></script>
        <!-- <script type="text/javascript" defer="defer" src="/plugins_wp/contact-form-7/includes/js/index.js?ver=5.6.2" id="contact-form-7-js"></script> -->
        <script type="text/javascript" defer="defer" src="/plugins_wp/revslider/public/assets/js/rbtools.min.js?ver=6.5.18" async="" id="tp-tools-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/revslider/public/assets/js/rs6.min.js?ver=6.5.31" async="" id="revmin-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/js/magnific/jquery.magnific-popup.min.js" id="magnific-popup-js"></script>

        <!-- <script type="text/javascript" defer="defer" src="/plugins_wp/trx_demo/js/trx_demo_panels.js" id="trx_demo_panels-js"></script> -->
        <script type="text/javascript" defer="defer" src="/plugins_wp/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.7.0-wc.6.8.2" id="jquery-blockui-js"></script>

        <script type="text/javascript" defer="defer" src="/plugins_wp/woocommerce/assets/js/frontend/add-to-cart.min.js?ver=6.8.2" id="wc-add-to-cart-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/woocommerce/assets/js/js-cookie/js.cookie.min.js?ver=2.1.4-wc.6.8.2" id="js-cookie-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/woocommerce/assets/js/frontend/woocommerce.min.js?ver=6.8.2" id="woocommerce-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/woocommerce/assets/js/frontend/cart-fragments.min.js?ver=6.8.2" id="wc-cart-fragments-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/booked/includes/add-ons/frontend-agents/js/functions.js?ver=2.3.5" id="booked-fea-js-js"></script>
        <!-- <script type="text/javascript" defer="defer" src="/plugins_wp/ti-woocommerce-wishlist/assets/js/public.min.js?ver=1.47.0" id="tinvwl-js"></script> -->
        <script type="text/javascript" defer="defer" src="/plugins_wp/booked/includes/add-ons/woocommerce-payments//js/frontend-functions.js?ver=6.0.3" id="booked-wc-fe-functions-js"></script>
        <script type="text/javascript" id="trx_addons-js-extra">
      /* <![CDATA[ */
      var TRX_ADDONS_STORAGE = {"ajax_url":"https:\/\/golfclub.themerex.net\/wp-admin\/admin-ajax.php","ajax_nonce":"dc893edf26","site_url":"https:\/\/golfclub.themerex.net","post_id":"5002","vc_edit_mode":"","is_preview":"","is_preview_gb":"","is_preview_elm":"","popup_engine":"magnific","scroll_progress":"hide","hide_fixed_rows":"1","smooth_scroll":"","animate_inner_links":"1","disable_animation_on_mobile":"","add_target_blank":"0","menu_collapse":"1","menu_collapse_icon":"trx_addons_icon-ellipsis-vert","menu_stretch":"1","resize_tag_video":"","resize_tag_iframe":"1","user_logged_in":"","theme_slug":"n7-golf-club","theme_bg_color":"#FFFFFF","theme_accent_color":"#2CC374","page_wrap_class":".page_wrap","email_mask":"^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-zA-Z0-9_\\-]+(\\.[a-zA-Z0-9_\\-]+)*\\.[a-zA-Z0-9]{2,6}$","mobile_breakpoint_fixedrows_off":"768","mobile_breakpoint_fixedcolumns_off":"768","mobile_breakpoint_stacksections_off":"768","mobile_breakpoint_fullheight_off":"1025","mobile_breakpoint_mousehelper_off":"1025","msg_caption_yes":"Yes","msg_caption_no":"No","msg_caption_ok":"OK","msg_caption_apply":"Apply","msg_caption_cancel":"Cancel","msg_caption_attention":"Attention!","msg_caption_warning":"Warning!","msg_ajax_error":"Invalid server answer!","msg_magnific_loading":"Loading image","msg_magnific_error":"Error loading image","msg_magnific_close":"Close (Esc)","msg_error_like":"Error saving your like! Please, try again later.","msg_field_name_empty":"The name can't be empty","msg_field_email_empty":"Too short (or empty) email address","msg_field_email_not_valid":"Invalid email address","msg_field_text_empty":"The message text can't be empty","msg_search_error":"Search error! Try again later.","msg_send_complete":"Send message complete!","msg_send_error":"Transmit failed!","msg_validation_error":"Error data validation!","msg_name_empty":"The name can't be empty","msg_name_long":"Too long name","msg_email_empty":"Too short (or empty) email address","msg_email_long":"E-mail address is too long","msg_email_not_valid":"E-mail address is invalid","msg_text_empty":"The message text can't be empty","msg_copied":"Copied!","ajax_views":"","menu_cache":[".menu_mobile_inner nav > ul"],"login_via_ajax":"1","double_opt_in_registration":"1","msg_login_empty":"The Login field can't be empty","msg_login_long":"The Login field is too long","msg_password_empty":"The password can't be empty and shorter then 4 characters","msg_password_long":"The password is too long","msg_login_success":"Login success! The page should be reloaded in 3 sec.","msg_login_error":"Login failed!","msg_not_agree":"Please, read and check 'Terms and Conditions'","msg_password_not_equal":"The passwords in both fields are not equal","msg_registration_success":"Thank you for registering. Please confirm registration by clicking on the link in the letter sent to the specified email.","msg_registration_error":"Registration failed!","shapes_url":"http:\/\/golfclub.themerex.net\/wp-content\/themes\/n7-golf-club\/skins\/default\/trx_addons\/css\/shapes\/","elementor_stretched_section_container":".page_wrap","pagebuilder_preview_mode":"","elementor_animate_items":".elementor-heading-title,.sc_item_subtitle,.sc_item_title,.sc_item_descr,.sc_item_posts_container + .sc_item_button,.sc_item_button.sc_title_button,.social_item,nav > ul > li","msg_change_layout":"After changing the layout, the page will be reloaded! Continue?","msg_change_layout_caption":"Change layout","add_to_links_url":[{"mask":"elementor.com\/","link":"https:\/\/be.elementor.com\/visit\/?bta=2496&nci=5383&brand=elementor&utm_campaign=theme"}],"msg_no_products_found":"No products found! Please, change query parameters and try again.","audio_effects_allowed":"0","mouse_helper":"1","mouse_helper_delay":"8","mouse_helper_centered":"0","msg_mouse_helper_anchor":"Scroll to","portfolio_use_gallery":"","scroll_to_anchor":"0","update_location_from_anchor":"0","msg_sc_googlemap_not_avail":"Googlemap service is not available","msg_sc_googlemap_geocoder_error":"Error while geocode address","sc_icons_animation_speed":"50","msg_sc_osmap_not_avail":"OpenStreetMap service is not available","msg_sc_osmap_geocoder_error":"Error while geocoding address","osmap_tiler":"vector","osmap_tiler_styles":{"basic":{"title":"Basic","slug":"basic","url":"https:\/\/api.maptiler.com\/maps\/{style}\/style.json?key=C1rALu26mR1iTxEBrqQj","maxzoom":"18","token":""}},"osmap_attribution":"Map data \u00a9 <a href=\"https:\/\/www.openstreetmap.org\/\">OpenStreetMap<\/a> contributors","slider_round_lengths":"1"};
      /* ]]> */
      </script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/js/__scripts.js" id="trx_addons-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/components/api/woocommerce/woocommerce.js" id="trx_addons-woocommerce-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/addons/mouse-helper/mouse-helper.js" id="trx_addons-mouse-helper-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/components/shortcodes/skills/chart-legacy.min.js" id="chart-legacy-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/components/shortcodes/skills/skills.js" id="trx_addons-sc_skills-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/components/cpt/layouts/shortcodes/menu/superfish.min.js" id="superfish-js"></script>
        <script type="text/javascript" src="/plugins_wp/trx_addons/js/tweenmax/tweenmax.min.js" id="tweenmax-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins_wp/trx_addons/components/api/mailchimp-for-wp/mailchimp-for-wp.js" id="trx_addons-mailchimp-js"></script>
        <!-- <script type="text/javascript" defer="defer" src="/plugins_wp/wp-gdpr-compliance/Assets/js/front.min.js?ver=1661193633" id="wpgdprc-front-js-js"></script> -->
        <script type="text/javascript" defer="defer" src="/js/__scripts.js" id="n7-golf-club-init-js"></script>
        <script type="text/javascript" id="mediaelement-core-js-before">
        var mejsL10n = {"language":"en","strings":{"mejs.download-file":"Download File","mejs.install-flash":"You are using a browser that does not have Flash player enabled or installed. Please turn on your Flash player plugin or download the latest version from https:\/\/get.adobe.com\/flashplayer\/","mejs.fullscreen":"Fullscreen","mejs.play":"Play","mejs.pause":"Pause","mejs.time-slider":"Time Slider","mejs.time-help-text":"Use Left\/Right Arrow keys to advance one second, Up\/Down arrows to advance ten seconds.","mejs.live-broadcast":"Live Broadcast","mejs.volume-help-text":"Use Up\/Down Arrow keys to increase or decrease volume.","mejs.unmute":"Unmute","mejs.mute":"Mute","mejs.volume-slider":"Volume Slider","mejs.video-player":"Video Player","mejs.audio-player":"Audio Player","mejs.captions-subtitles":"Captions\/Subtitles","mejs.captions-chapters":"Chapters","mejs.none":"None","mejs.afrikaans":"Afrikaans","mejs.albanian":"Albanian","mejs.arabic":"Arabic","mejs.belarusian":"Belarusian","mejs.bulgarian":"Bulgarian","mejs.catalan":"Catalan","mejs.chinese":"Chinese","mejs.chinese-simplified":"Chinese (Simplified)","mejs.chinese-traditional":"Chinese (Traditional)","mejs.croatian":"Croatian","mejs.czech":"Czech","mejs.danish":"Danish","mejs.dutch":"Dutch","mejs.english":"English","mejs.estonian":"Estonian","mejs.filipino":"Filipino","mejs.finnish":"Finnish","mejs.french":"French","mejs.galician":"Galician","mejs.german":"German","mejs.greek":"Greek","mejs.haitian-creole":"Haitian Creole","mejs.hebrew":"Hebrew","mejs.hindi":"Hindi","mejs.hungarian":"Hungarian","mejs.icelandic":"Icelandic","mejs.indonesian":"Indonesian","mejs.irish":"Irish","mejs.italian":"Italian","mejs.japanese":"Japanese","mejs.korean":"Korean","mejs.latvian":"Latvian","mejs.lithuanian":"Lithuanian","mejs.macedonian":"Macedonian","mejs.malay":"Malay","mejs.maltese":"Maltese","mejs.norwegian":"Norwegian","mejs.persian":"Persian","mejs.polish":"Polish","mejs.portuguese":"Portuguese","mejs.romanian":"Romanian","mejs.russian":"Russian","mejs.serbian":"Serbian","mejs.slovak":"Slovak","mejs.slovenian":"Slovenian","mejs.spanish":"Spanish","mejs.swahili":"Swahili","mejs.swedish":"Swedish","mejs.tagalog":"Tagalog","mejs.thai":"Thai","mejs.turkish":"Turkish","mejs.ukrainian":"Ukrainian","mejs.vietnamese":"Vietnamese","mejs.welsh":"Welsh","mejs.yiddish":"Yiddish"}};
        </script>
        <script type="text/javascript" defer="defer" src="/js/mediaelement/mediaelement-and-player.min.js?ver=4.2.16" id="mediaelement-core-js"></script>
        <script type="text/javascript" defer="defer" src="/js/mediaelement/mediaelement-migrate.min.js?ver=6.0.3" id="mediaelement-migrate-js"></script>
        <script type="text/javascript" id="mediaelement-js-extra">
        /* <![CDATA[ */
        var _wpmejsSettings = {"pluginPath":"\/wp-includes\/js\/mediaelement\/","classPrefix":"mejs-","stretching":"responsive"};
        /* ]]> */
        </script>
        <script type="text/javascript" defer="defer" src="/js/mediaelement/wp-mediaelement.min.js?ver=6.0.3" id="wp-mediaelement-js"></script>
        <script type="text/javascript" defer="defer" src="/skins/default/skin.js" id="n7-golf-club-skin-default-js"></script>
        <script type="text/javascript" defer="defer" src="/plugins/woocommerce/woocommerce.js" id="n7-golf-club-woocommerce-js"></script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/lib/swiper/swiper.min.js?ver=5.3.6" id="swiper-js"></script>
        <script type="text/javascript" defer="defer" src="/js/imagesloaded.min.js?ver=4.1.4" id="imagesloaded-js"></script>
        <script type="text/javascript" defer="defer" src="/js/masonry.min.js?ver=4.2.2" id="masonry-js"></script>
        <!-- <script type="text/javascript" defer="" src="/plugins_wp/mailchimp-for-wp/assets/js/forms.js?ver=4.8.7" id="mc4wp-forms-api-js"></script> -->
        <script type="text/javascript" src="/plugins_wp/elementor/assets/js/webpack.runtime.min.js?ver=3.7.2" id="elementor-webpack-runtime-js"></script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/js/frontend-modules.min.js?ver=3.7.2" id="elementor-frontend-modules-js"></script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/lib/waypoints/waypoints.min.js?ver=4.0.2" id="elementor-waypoints-js"></script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/lib/share-link/share-link.min.js?ver=3.7.2" id="share-link-js"></script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/lib/dialog/dialog.min.js?ver=4.9.0" id="elementor-dialog-js"></script>
        <script type="text/javascript" id="elementor-frontend-js-before">
        var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Extra","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Extra","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}}},"version":"3.7.2","is_static":false,"experimentalFeatures":{"e_import_export":true,"e_hidden_wordpress_widgets":true,"landing-pages":true,"elements-color-picker":true,"favorite-widgets":true,"admin-top-bar":true},"urls":{"assets":"https:\/\/golfclub.themerex.net\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"editorPreferences":[]},"kit":{"stretched_section_container":".page_wrap","active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":5002,"title":"Home%203%20%E2%80%93%20N7%20Golf%20Club","excerpt":"","featuredImage":false}};
        </script>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/js/frontend.min.js?ver=3.7.2" id="elementor-frontend-js"></script><span id="elementor-device-mode" class="elementor-screen-only"></span>
        <script type="text/javascript" src="/plugins_wp/elementor/assets/js/preloaded-modules.min.js?ver=3.7.2" id="preloaded-modules-js"></script><svg style="display: none;" class="e-font-icon-svg-symbols"></svg>
        @stack('scripts')
    </body>
</html>
