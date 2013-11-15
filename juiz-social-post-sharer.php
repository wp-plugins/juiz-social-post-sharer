<?php
/*
Plugin Name: Juiz Social Post Sharer
Plugin URI: http://wordpress.org/extend/plugins/juiz-social-post-sharer/
Description: Add buttons after (or before, or both) your posts to allow visitors share your content (includes no JavaScript mode). You can also use <code>juiz_sps($array)</code> template function or <code>[juiz_sps]</code> shortcode. For more informations see the setting page located in <strong>Settings</strong> submenu. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&amp;item_number=%23wp%2djsps&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">Donate</a>
Author: Geoffrey Crofte
Version: 1.3.2
Author URI: http://crofte.fr
License: GPLv2 or later 
*/

/**

Copyright 2012-2013  Geoffrey Crofte  (email : support@creativejuiz.com)

    
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

**/

define( 'JUIZ_SPS_PLUGIN_NAME',	 'Juiz Social Post Sharer' );
define( 'JUIZ_SPS_VERSION',		 '1.3.2' );
define( 'JUIZ_SPS_FILE',		 __FILE__ );
define( 'JUIZ_SPS_DIRNAME',		 basename( dirname( __FILE__ ) ) );
define( 'JUIZ_SPS_PLUGIN_URL',	 plugin_dir_url( __FILE__ ));
define( 'JUIZ_SPS_SLUG',		 'juiz-social-post-sharer' );
define( 'JUIZ_SPS_SETTING_NAME', 'juiz_SPS_settings' );


	
// multilingue

add_action( 'init', 'make_juiz_sps_multilang' );
function make_juiz_sps_multilang() {
	load_plugin_textdomain( 'jsps_lang', false, JUIZ_SPS_DIRNAME.'/languages' );
}

if ( is_admin() || ( defined( 'DOING_AJAX' ) && !DOING_AJAX ) ) {
	
	include('admin/jsps-admin.inc.php');

} // end if is_admin


if (!is_admin()) {

	// Enqueue & deregister
	add_action( 'wp_enqueue_scripts', 'juiz_sps_style_and_script');
	if ( !function_exists('juiz_sps_style_and_script')) {
		function juiz_sps_style_and_script() {
			$juiz_sps_options = get_option( JUIZ_SPS_SETTING_NAME );
			if(is_array($juiz_sps_options)) {
				// TODO future : if(is_numeric($juiz_sps_options['juiz_sps_style'] ) && $juiz_sps_options['juiz_sps_write_css_in_html']==0)
				if( is_numeric( $juiz_sps_options['juiz_sps_style'] ) ) {
					wp_enqueue_style( 'juiz_sps_styles', JUIZ_SPS_PLUGIN_URL.'css/'.JUIZ_SPS_SLUG.'-'.$juiz_sps_options['juiz_sps_style'].'.min.css', false, JUIZ_SPS_VERSION, 'all' );
				}
				if(is_numeric( $juiz_sps_options['juiz_sps_counter'] ) && $juiz_sps_options['juiz_sps_counter'] == 1 ) {
					wp_enqueue_script( 'juiz_sps_scripts', JUIZ_SPS_PLUGIN_URL.'js/'.JUIZ_SPS_SLUG.'.min.js', array('jquery'), JUIZ_SPS_VERSION, true);
				}
			}
		}
	}


	// Thanks to http://boiteaweb.fr/sf_get_current_url-obtenir-url-courante-fonction-semaine-5-6765.html
	if ( !function_exists('sf_get_current_url') ) {
		function sf_get_current_url( $mode = 'base' ) {
			
			$url = 'http'.(is_ssl() ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			
			switch( $mode ){
				case 'raw' :  return $url; break;
				case 'base' :   return reset(explode('?', $url)); break;
				case 'uri' :  $exp = explode( '?', $url );
				return trim( str_replace( home_url(), '', reset( $exp ) ), '/' ); break;
				default:  return false;
			}
		}
	}


	// template function
	if ( !function_exists('get_juiz_sps')) {
		function get_juiz_sps($networks = array(), $counters = 0, $is_current_page_url = 0, $is_shortcode = 0) {

			global $post;

			$show_me =  (get_post_meta($post->ID,'_jsps_metabox_hide_buttons',true)=='on') ? false : true;
			$show_me = 	$is_shortcode ? true : $show_me;

			// show buttons only if post meta don't ask to hide it, and if it's not a shortcode.
			if ( $show_me ) {

				// texts, URL and image to share
				$text = esc_attr(urlencode($post->post_title));
				$url = $post ? get_permalink() : sf_get_current_url( 'raw' );
				//$url = urlencode(get_permalink());
				if ( $is_current_page_url ) {
					$url = sf_get_current_url( 'raw' );
				}
				$url = apply_filters('juiz_sps_the_shared_permalink', $url);
				$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';

				// some markup filters
				$hide_intro_phrase 			= apply_filters('juiz_sps_network_name', false);
				$share_the_post_sentence 	= apply_filters('juiz_sps_intro_phrase_text', __('Share the post','jsps_lang') );
				$before_the_sps_content 	= apply_filters('juiz_sps_before_the_sps', '');
				$after_the_sps_content 		= apply_filters('juiz_sps_after_the_sps', '');
				$before_the_list 			= apply_filters('juiz_sps_before_the_list', '');
				$after_the_list 			= apply_filters('juiz_sps_after_the_list', '');
				$before_first_i 			= apply_filters('juiz_sps_before_first_item', '');
				$after_last_i 				= apply_filters('juiz_sps_after_last_item', '');
				$container_classes 			= apply_filters('juiz_sps_container_classes', '');
				$rel_nofollow 				= apply_filters('juiz_sps_links_nofollow', 'rel="nofollow"');

				// markup filters
				$div 	= apply_filters('juiz_sps_container_tag', 'div');
				$p 		= apply_filters('juiz_sps_phrase_tag', 'p');
				$ul 	= apply_filters('juiz_sps_list_container_tag', 'ul'); 
				$li 	= apply_filters('juiz_sps_list_of_item_tag', 'li');


				// get the plugin options
				$juiz_sps_options = get_option( JUIZ_SPS_SETTING_NAME );

				// classes and attributes options
				$juiz_sps_target_link = (isset($juiz_sps_options['juiz_sps_target_link']) && $juiz_sps_options['juiz_sps_target_link']==1) ? ' target="_blank"' : '';
				$juiz_sps_hidden_name_class = (isset($juiz_sps_options['juiz_sps_hide_social_name']) && $juiz_sps_options['juiz_sps_hide_social_name']==1) ? ' juiz_sps_hide_name' : '';
				$container_classes .= (intval($counters)==1) ? ' juiz_sps_counters' : '';

				// other options
				$juiz_sps_display_where = isset($juiz_sps_options['juiz_sps_display_where']) ? $juiz_sps_options['juiz_sps_display_where'] : '';
				$force_pinterest_snif = intval($juiz_sps_options['juiz_sps_force_pinterest_snif']);

				// beginning markup
				$juiz_sps_content = $before_the_sps_content;
				$juiz_sps_content .= "\n".'<'.$div.' class="juiz_sps_links '.$container_classes.' juiz_sps_displayed_'.$juiz_sps_display_where.'">';
				$juiz_sps_content .= $hide_intro_phrase ? '' : "\n".'<'.$p.' class="screen-reader-text juiz_sps_maybe_hidden_text">'.$share_the_post_sentence.' "'.get_the_title().'"</'.$p.'>'."\n";
				$juiz_sps_content .= $before_the_list;
				$juiz_sps_content .= "\n\t".'<'.$ul.' class="juiz_sps_links_list'.$juiz_sps_hidden_name_class.'">';
				$juiz_sps_content .= $before_first_i;

				// networks to display
				// 2 differents results by : 
				// -- using hook (options from admin panel)
				// -- using shortcode/template-function (the array $networks in parameter of this function)
				$juis_sps_networks = array();

				if ( count($networks) > 0 ) {
					// compare $juiz_sps_options['juiz_sps_networks'] array and $networks array and conserve form the first one all that correspond to the second one's keys
					$juis_sps_networks = array();
					foreach($juiz_sps_options['juiz_sps_networks'] as $k => $v) {
						if(in_array($k, $networks)) {
							$juis_sps_networks[$k]=$v;
							$juis_sps_networks[$k][0]=1; //set its visible value to 1 (visible)
						}
					}

				}
				else {
					$juis_sps_networks = $juiz_sps_options['juiz_sps_networks'];
				}


				// each links (come from options or manual array)
				foreach($juis_sps_networks as $k => $v) {
					if( $v[0] == 1 ) {
						$api_link = $api_text = '';
						$url = apply_filters('juiz_sps_the_shared_permalink_for_'.$k, $url);

						$twitter_user = $juiz_sps_options['juiz_sps_twitter_user'] != '' ? "&amp;related=".$juiz_sps_options['juiz_sps_twitter_user']."&amp;via=". $juiz_sps_options['juiz_sps_twitter_user'] : '';

						switch ($k) {
							case "twitter" :
								$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$url.'&amp;text='.$text.'&amp;url='.$url.$twitter_user;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Twitter','jsps_lang'));
								break;

							case "facebook" :
								$api_link = 'https://www.facebook.com/sharer/sharer.php?u='.$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Facebook','jsps_lang'));
								break;

							case "google" :
								$api_link = 'https://plus.google.com/share?url='.$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Google+','jsps_lang'));
								break;

							case "pinterest" :
								if ( $image != '' && $force_pinterest_snif==0 ) {
									$api_link = 'http://pinterest.com/pin/create/bookmarklet/?media='.$image[0].'&amp;url='.$url.'&amp;title='.get_the_title().'&amp;description='.$post->post_excerpt;
								}
								else {
									$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
									$juiz_sps_target_link = "";
								}
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share an image of this article on Pinterest','jsps_lang'));
								break;

							case "viadeo" :
								$api_link = "http://www.viadeo.com/shareit/share/?url=".$url;
								$api_text = __('Share this article on Viadeo','jsps_lang');
								break;

							case 'linkedin':
								$api_link = "http://www.linkedin.com/shareArticle?mini=true&amp;ro=true&amp;trk=JuizSocialPostSharer&amp;title=".$text."&amp;url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on LinkedIn','jsps_lang'));
								break;

							case 'digg':
								$api_link = "http://digg.com/submit?phase=2%20&amp;url=".$url."&amp;title=".$text;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Digg','jsps_lang'));
								break;

							case 'stumbleupon':
								$api_link = "http://www.stumbleupon.com/badge/?url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on StumbleUpon','jsps_lang'));
								break;

							case 'weibo':
								$api_link = "http://service.weibo.com/share/share.php?url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on Weibo','jsps_lang'));
								break;

							case 'vk':
								$api_link = "http://vkontakte.ru/share.php?url=".$url;
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article on VKontakte','jsps_lang'));
								break;

							case 'mail' :
								if (strpos($juiz_sps_options['juiz_sps_mail_body'], '%%') || strpos($juiz_sps_options['juiz_sps_mail_subject'], '%%') ) {
									$api_link = esc_attr('mailto:?subject='.$juiz_sps_options['juiz_sps_mail_subject'].'&amp;body='.$juiz_sps_options['juiz_sps_mail_body']);
									$api_link = preg_replace(array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#'), array(get_the_title(), get_site_url(), get_permalink()), $api_link);
								}
								else {
									$api_link = 'mailto:?subject='.$juiz_sps_options['juiz_sps_mail_subject'].'&amp;body='.$juiz_sps_options['juiz_sps_mail_body']." : ".$url;
								}
								$api_text = apply_filters('juiz_sps_share_text_for_'.$k, __('Share this article with a friend (email)','jsps_lang'));
								break;
						}

						$network_name = isset($v[1]) ? $v[1] : $k;
						$juiz_sps_content .= '<'.$li.' class="juiz_sps_item juiz_sps_link_'.$k.'"><a href="'.$api_link.'" '.$rel_nofollow.' title="'.$api_text.'"'.$juiz_sps_target_link.'><span class="juiz_sps_icon"></span><span class="juiz_sps_network_name">'.$network_name.'</span></a></'.$li.'>';

					}
				}
				$general_counters = (isset($juiz_sps_options['juiz_sps_counter']) && $juiz_sps_options['juiz_sps_counter']==1) ? 1 : 0;
				$hidden_info = '<input type="hidden" class="juiz_sps_info_plugin_url" value="'.JUIZ_SPS_PLUGIN_URL.'" /><input type="hidden" class="juiz_sps_info_permalink" value="'.$url.'" />';

				$juiz_sps_content .= $after_last_i;
				$juiz_sps_content .= (($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1)) ? '<li class="juiz_sps_item juiz_sps_totalcount_item"><span class="juiz_sps_totalcount" title="'.__('Total: ', "jsps_lang").'"><span class="juiz_sps_t_nb"></span></span></li>' : '';
				$juiz_sps_content .= '</'.$ul.'>'."\n\t";
				$juiz_sps_content .= $after_the_list;
				$juiz_sps_content .= ( ($general_counters==1 && intval($counters)==1) || ($general_counters==0 && intval($counters)==1))  ? $hidden_info : '';
				$juiz_sps_content .= '</'.$div.'>'."\n\n";
				$juiz_sps_content .= $after_the_sps_content;


				// final markup

				return $juiz_sps_content;

			} // end of if post meta hide sharing buttons

		} // end of get_juiz_sps()
	} // end of if exists


	// just a little alias
	if ( !function_exists("juiz_sps")){
		function juiz_sps($networks = array(), $counters = 0, $current_page = 0, $is_shortcode = 0) {
			echo get_juiz_sps($networks, $counters, $current_page, $is_shortcode);
		}
	}

	// write buttons in content
	add_action('the_content', 'juiz_sps_print_links', 10, 1);
	add_action('the_excerpt', 'juiz_sps_print_links', 10, 1);

	if ( !function_exists('juiz_sps_print_links')) {
		function juiz_sps_print_links($content) {
			
			$juiz_sps_options = get_option(JUIZ_SPS_SETTING_NAME);

			if( isset($juiz_sps_options['juiz_sps_display_in_types']) ) {

				// write buttons only if administrator checked this type
				$is_all_lists = in_array('all_lists', $juiz_sps_options['juiz_sps_display_in_types']);
				$singular_options = $juiz_sps_options['juiz_sps_display_in_types'];
				unset($singular_options['all_lists']);

				$is_lists_authorized = (is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) && $is_all_lists ? true : false;
				$is_singular = is_singular($singular_options);

				if ( $is_singular || $is_lists_authorized ) {

					$need_counters = $juiz_sps_options['juiz_sps_counter'] ? 1 : 0;

					$jsps_links = get_juiz_sps(array(), $need_counters);

					$juiz_sps_display_where = isset($juiz_sps_options['juiz_sps_display_where']) ? $juiz_sps_options['juiz_sps_display_where'] : '';

					if( 'top' == $juiz_sps_display_where || 'both' == $juiz_sps_display_where )
						$content = $jsps_links.$content;
					if( 'bottom' == $juiz_sps_display_where || 'both' == $juiz_sps_display_where )
						$content = $content.$jsps_links;

					return $content;
				}
				else 
					return $content;
			}
			else 
				return $content;

		} // end function
	} // end if function exists


	/*
	 * Add shortcode
	 */
	if ( !function_exists('sc_4_jsps')) {

		function sc_4_jsps($atts) {
			
			$atts = shortcode_atts(array(
				'buttons' 	=> 'facebook,twitter,mail,google,stumbleupon,linkedin,pinterest,viadeo,digg,weibo',
				'counters'	=> 0,
				'current'	=> 1
			), $atts);
			
			// buttons become array ("digg,mail", "digg ,mail", "digg, mail", "digg , mail", are right syntaxes)
			$jsps_networks = preg_split('#[\s+,\s+]#', $atts['buttons']);
			$jsps_counters = intval($atts['counters']);
			$jsps_current_page = intval($atts['current']);

			if( $jsps_current_page == 1 ) {
				wp_enqueue_script( 'juiz_sps_scripts', JUIZ_SPS_PLUGIN_URL.'js/'.JUIZ_SPS_SLUG.'.js', array('jquery'), false, true );
			}
			
			ob_start();
			juiz_sps($jsps_networks, $jsps_counters, $jsps_current_page, 1); //do an echo
			$jsps_sc_output = ob_get_contents();
			ob_end_clean();
			

			return $jsps_sc_output;
		}
		add_shortcode('juiz_social','sc_4_jsps');
		add_shortcode('juiz_sps'   ,'sc_4_jsps');
	}

} // end of if not admin


/**
	Prévoir:
	- ajouter les réseaux sociaux russes (cf http://wordpress.org/support/topic/vkcom?replies=8)
	- ajouter l'autre réseau chinois (cf http://wordpress.org/support/topic/button-for-plurk?replies=2)
	- ajouter le réseau app.net (cf http://wordpress.org/support/topic/button-for-appnet?replies=2)
**/
