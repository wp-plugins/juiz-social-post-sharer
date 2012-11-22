<?php
/*
Plugin Name: Juiz Social Post Sharer
Plugin URI: http://www.creativejuiz.fr/blog/
Description: Add buttons after your posts to allow visitors share your content (includes no JavaScript mode). The setting page is located in *Settings* submenu. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&amp;item_number=%23wp%2djsps&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">Donate</a>
Author: Geoffrey Crofte
Version: 1.0.1
Author URI: http://crofte.fr
License: GPLv2 or later 
*/

/*

Copyright 2012  Geoffrey Crofte  (email : support@creativejuiz.com)

    
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

*/

define( 'JUIZ_SPS_PLUGIN_NAME',	 'Juiz Social Post Sharer' );
define( 'JUIZ_SPS_VERSION',		 '1.0.1' );
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

	// uninstall hook
	register_uninstall_hook( __FILE__, 'juiz_sps_uninstaller' );
	function juiz_sps_uninstaller() {
		delete_option( JUIZ_SPS_SETTING_NAME );
	}

	// activation hook
	register_activation_hook( __FILE__, 'juiz_sps_activation' );
	function juiz_sps_activation() {
		$juiz_sps_options = get_option ( JUIZ_SPS_SETTING_NAME );
		if ( !is_array($juiz_sps_options) ) {
			
			$default_array = array(
				'juiz_sps_style' 			=> 1,
				'juiz_sps_networks' 		=> array(
												"facebook"		=>	array(1, "Facebook"), 
												"twitter"		=>	array(1, "Twitter"), 
												"google"		=>	array(0, "Google+"),
												"pinterest" 	=>	array(0, "Pinterest"),
												"viadeo" 		=>	array(0, "Viadeo"),
												"linkedin" 		=>	array(0, "LinkedIn"),
												"digg"	 		=>	array(0, "Digg"),
												"stumbleupon"	=>	array(0, "StumbleUpon"),
												"mail"			=>	array(1, "E-mail")
											),
				'juiz_sps_counter'			=> 0,
				'juiz_sps_hide_social_name' => 0,
				'juiz_sps_target_link'		=> 0
			);
			
			update_option( JUIZ_SPS_SETTING_NAME , $default_array);
		}
	}

	// description setting page
	add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), 'juiz_sps_plugin_action_links',  10, 2);
	function juiz_sps_plugin_action_links( $links, $file ) {
		$links[] = '<a href="'.admin_url('options-general.php?page='.JUIZ_SPS_SLUG).'">' . __('Settings') .'</a>';
		return $links;
	}
	
	
	/*
	 * Options page
	 */
	 
	 
	// Settings page in admin menu

	add_action('admin_menu', 'add_juiz_sps_settings_page');
	function add_juiz_sps_settings_page() {
		add_submenu_page( 
			'options-general.php', 
			__('Social Post Sharer', 'jsma_lang'),
			__('Social Post Sharer', 'jsma_lang'),
			'administrator',
			JUIZ_SPS_SLUG ,
			'juiz_sps_settings_page' 
		);
	}

	// Some styles for settings page in admin
	add_action( 'admin_head-settings_page_'.JUIZ_SPS_SLUG, 'juiz_sps_custom_admin_header');
	function juiz_sps_custom_admin_header() {
		echo '
			<!-- '.JUIZ_SPS_PLUGIN_NAME.' styles -->
			<style rel="stylesheet">
				#juiz-sps h3 { font-size: 1.65em; color: #444; font-weight:normal; }
				.juiz_sps_section_intro {font-style: italic; color: #777; }
				#juiz-sps form {padding-left:45px}
				#juiz-sps th {font-weight:bold; padding-left:0}
				#juiz-sps th em {font-weight:normal;font-style: italic; color: #777;}

				.jsps_demo_icon { display: inline-block; width: 16px; height: 16px; margin-right: 5px; vertical-align: middle; background: url('.JUIZ_SPS_PLUGIN_URL.'/img/sps_sprites.png) no-repeat 0 -16px;}
				.jsps_demo_icon_google 		{ background-position: -16px -16px }
				.jsps_demo_icon_facebook 	{ background-position: -32px -16px }
				.jsps_demo_icon_mail	 	{ background-position: -48px -16px }
				.jsps_demo_icon_pinterest 	{ background-position: -64px -16px }
				.jsps_demo_icon_viadeo	 	{ background-position: -80px -16px }
				.jsps_demo_icon_linkedin 	{ background-position: -96px -16px }
				.jsps_demo_icon_digg	 	{ background-position: -112px -16px }
				.jsps_demo_icon_stumbleupon	{ background-position: -128px -16px }

				:checked + label .jsps_demo_icon_twitter 	{ background-position: 0 0 }
				:checked + label .jsps_demo_icon_google 	{ background-position: -16px 0 }
				:checked + label .jsps_demo_icon_facebook 	{ background-position: -32px 0 }
				:checked + label .jsps_demo_icon_mail	 	{ background-position: -48px 0 }
				:checked + label .jsps_demo_icon_pinterest 	{ background-position: -64px 0 }
				:checked + label .jsps_demo_icon_viadeo	 	{ background-position: -80px 0 }
				:checked + label .jsps_demo_icon_linkedin 	{ background-position: -96px 0 }
				:checked + label .jsps_demo_icon_digg	 	{ background-position: -112px 0 }
				:checked + label .jsps_demo_icon_stumbleupon{ background-position: -128px 0 }

				.juiz_sps_options_p { margin: .2em 5% .2em 0; }

				.juiz_sps_styles_options {}
				.juiz_sps_demo_styles { display:inline-block; vertical-align:middle; width:592px; height:26px; background:url('.JUIZ_SPS_PLUGIN_URL.'/img/demo-sprites.png) no-repeat 0 -26px}
				[for="jsps_style_2"] .juiz_sps_demo_styles { background-position: 0 0 }
				[for="jsps_style_3"] .juiz_sps_demo_styles { height: 36px; background-position: 0 -93px }
				[for="jsps_style_4"] .juiz_sps_demo_styles { height: 36px; background-position: 0 -129px }
				.juiz_sps_style_name { display:inline-block; margin: 4px 0 0 18px; color: #777;}
			</style>
			<!-- end of '.JUIZ_SPS_PLUGIN_NAME.' styles -->
		';
	}
	

	/*
	 *****
	 ***** Sections and fields for settings
	 *****
	 */

	function add_juiz_sps_plugin_options() {
		// all options in single registration as array
		register_setting( JUIZ_SPS_SETTING_NAME, JUIZ_SPS_SETTING_NAME, 'juiz_sps_sanitize' );
		
		add_settings_section('juiz_sps_plugin_main', __('Main settings','jsps_lang'), 'juiz_sps_section_text', JUIZ_SPS_SLUG);
		add_settings_field('juiz_sps_style_choice', __('Choose a style to display', 'jsps_lang'), 'juiz_sps_setting_radio_style_choice', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
		add_settings_field('juiz_sps_network_selection', __('Display those following social networks:', 'jsps_lang') , 'juiz_sps_setting_checkbox_network_selection', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');


		add_settings_section('juiz_sps_plugin_advanced', __('Advanced settings','jsps_lang'), 'juiz_sps_section_text_advanced', JUIZ_SPS_SLUG);
		add_settings_field('juiz_sps_counter', __('Display counter of sharing?','jsps_lang').'<br /><em>('.__('need JavaScript','jsps_lang').')</em>', 'juiz_sps_setting_radio_counter', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
		add_settings_field('juiz_sps_hide_social_name', __('Show only social icon?', 'jsps_lang').'<br /><em>('.__('hide text, show it on mouse over or focus', 'jsps_lang').')</em>', 'juiz_sps_setting_radio_hide_social_name', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
		add_settings_field('juiz_sps_target_link', __('Open links in a new window?', 'jsps_lang').'<br /><em>('.sprintf(__('adds a %s attribute', 'jsps_lang'), '<code>target="_blank"</code>').')</em>', 'juiz_sps_setting_radio_target_link', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');


	}
	add_filter('admin_init','add_juiz_sps_plugin_options');

	
	// sanitize posted data
	
	function juiz_sps_sanitize($options) {
		
		if( is_array( $options['juiz_sps_networks'] ) ) {
		
			$temp_array = array('facebook'=>0, 'twitter'=>0, 'google'=>0, 'pinterest'=>0, 'viadeo'=>0, 'linkedin'=>0, 'digg'=>0, 'stumbleupon'=>0, 'mail' => 0);
			$juiz_sps_opt = get_option ( JUIZ_SPS_SETTING_NAME );

			foreach( $options['juiz_sps_networks'] as $nw )
				$temp_array[$nw]=1;

			foreach($temp_array as $k => $v)
				$juiz_sps_opt['juiz_sps_networks'][$k][0] = $v;

			$newoptions['juiz_sps_networks'] = $juiz_sps_opt['juiz_sps_networks'];
		}


		$newoptions['juiz_sps_style'] = $options['juiz_sps_style']>=1 && $options['juiz_sps_style']<=4 ? (int)$options['juiz_sps_style'] : 1;
		$newoptions['juiz_sps_hide_social_name'] = (int)$options['juiz_sps_hide_social_name']==1 ? 1 : 0;
		$newoptions['juiz_sps_target_link'] = (int)$options['juiz_sps_target_link']==1 ? 1 : 0;
		$newoptions['juiz_sps_counter'] = (int)$options['juiz_sps_counter']==1 ? 1 : 0;
		
		return $newoptions;
	}
	
	// first section text
	function juiz_sps_section_text() {
		echo '<p class="juiz_sps_section_intro">'. __('Here, you can modify default settings of the SPS plugin', 'jsps_lang') .'</p>';
	}
	
	// radio fields styles choice
	function juiz_sps_setting_radio_style_choice() {
		$n1 = $n2 = '';
		$options = get_option( JUIZ_SPS_SETTING_NAME );
		if ( is_array($options) )
			$n1 = $n2 = $n3 = $n4 = "";
			${'n'.$options['juiz_sps_style']} = " checked='checked'";
		
			echo '<p class="juiz_sps_styles_options">
						<input id="jsps_style_1" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n1.' />
						<label for="jsps_style_1"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Juizy Light Tone', 'jsps_lang') . '</span></label>
					</p>
					<p class="juiz_sps_styles_options">
						<input id="jsps_style_2" value="2" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n2.' />
						<label for="jsps_style_2"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Juizy Light Tone Reverse', 'jsps_lang') . '</span></label>
					</p>
					<p class="juiz_sps_styles_options">
						<input id="jsps_style_3" value="3" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n3.' />
						<label for="jsps_style_3"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Blue Metro Style', 'jsps_lang') . '</span></label>
					</p>
					<p class="juiz_sps_styles_options">
						<input id="jsps_style_4" value="4" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n4.' />
						<label for="jsps_style_4"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Gray Metro Style', 'jsps_lang') . '</span></label>
					</p>';
	}


	// checkboxes fields for networks
	function juiz_sps_setting_checkbox_network_selection() {
		$y = $n = '';
		$options = get_option( JUIZ_SPS_SETTING_NAME );
		if ( is_array($options) ) {
			foreach($options['juiz_sps_networks'] as $k => $v) {

				$is_checked = ($v[0] == 1) ? ' checked="checked"' : '';
				$is_js_test = ($k == 'pinterest') ? ' <em>('.__('uses JavaScript to work','jsps_lang').')</em>' : '';

				echo '<p class="juiz_sps_options_p">
						<input id="jsps_network_selection_'.$k.'" value="'.$k.'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_networks][]" type="checkbox"
					'.$is_checked.' />
				  		<label for="jsps_network_selection_'.$k.'"><span class="jsps_demo_icon jsps_demo_icon_'.$k.'"></span>'.$v[1].''.$is_js_test.'</label>
				  	</p>';
			}
		}
	}

	// Advanced section text
	function juiz_sps_section_text_advanced() {
		echo '<p class="juiz_sps_section_intro">'. __('Modify advanced settings of the plugin. Some of them needs JavaScript (only one file loaded)', 'jsps_lang') .'</p>';
	}
	// radio fields Y or N for counter
	function juiz_sps_setting_radio_counter() {
		$y = $n = '';
		$options = get_option( JUIZ_SPS_SETTING_NAME );

		if ( is_array($options) )
			(isset($options['juiz_sps_counter']) AND $options['juiz_sps_counter']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
		
			echo '	<em style="color:#777;">' . __('This option will be enabled for a next version','jsps_lang') . '</em><br />
					<input id="jsps_counter_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter]" type="radio" '.$y.' disabled="disabled" />
					<label style="color:#777;" for="jsps_counter_y"> '. __('Yes', 'jsps_lang') . '</label>
					&nbsp;&nbsp;
					<input id="jsps_counter_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter]" type="radio" '.$n.'  disabled="disabled" />
					<label style="color:#777;" for="jsps_counter_n"> '. __('No', 'jsps_lang') . '</label>';
	}

	// radio fields Y or N for hide text
	function juiz_sps_setting_radio_hide_social_name() {
		$y = $n = '';
		$options = get_option( JUIZ_SPS_SETTING_NAME );

		if ( is_array($options) )
			(isset($options['juiz_sps_hide_social_name']) AND $options['juiz_sps_hide_social_name']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
		
			echo "	<input id='jsps_hide_name_y' value='1' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_hide_social_name]' type='radio' ".$y." />
					<label for='jsps_hide_name_y'> ". __('Yes', 'jsps_lang') . "</label>
					&nbsp;&nbsp;
					<input id='jsps_hide_name_n' value='0' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_hide_social_name]' type='radio' ".$n." />
					<label for='jsps_hide_name_n'> ". __('No', 'jsps_lang') . "</label>";
	}

	// radio fields Y or N for target _blank
	function juiz_sps_setting_radio_target_link() {
		$y = $n = '';
		$options = get_option( JUIZ_SPS_SETTING_NAME );

		if ( is_array($options) )
			(isset($options['juiz_sps_target_link']) AND $options['juiz_sps_target_link']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
		
			echo "	<input id='jsps_target_link_y' value='1' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$y." />
					<label for='jsps_target_link_y'> ". __('Yes', 'jsps_lang') . "</label>
					&nbsp;&nbsp;
					<input id='jsps_target_link_n' value='0' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$n." />
					<label for='jsps_target_link_n'> ". __('No', 'jsps_lang') . "</label>";
	}
	
	


	// The page layout/form

	function juiz_sps_settings_page() {
		?>
		<div id="juiz-sps" class="wrap">
			<div id="icon-options-general" class="icon32">&nbsp;</div>
			<h2><?php _e('Manage Juiz Social Post Sharer', 'jsps_lang') ?></h2>

			<form method="post" action="options.php">
				<?php
					settings_fields( JUIZ_SPS_SETTING_NAME );
					do_settings_sections( JUIZ_SPS_SLUG );
					submit_button();
				?>
			</form>

			<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&amp;item_number=%23wp%2djsps&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted"><?php _e('Donate', 'jsps_lang') ?></a></p>
		</div>
		<?php
	}

} // end if is_admin

if (!is_admin()){

	// Enqueue & deregister
	add_action( 'init', 'juiz_sps_style_and_script');
	function juiz_sps_style_and_script() {
		$juiz_sps_options = get_option( JUIZ_SPS_SETTING_NAME );
		if(is_array($juiz_sps_options)) {
			if(is_numeric($juiz_sps_options['juiz_sps_style']))
				wp_enqueue_style( 'juiz_sps_styles', JUIZ_SPS_PLUGIN_URL.'/css/'.JUIZ_SPS_SLUG.'-'.$juiz_sps_options['juiz_sps_style'].'.css', false, false, 'all' );
			if(is_numeric($juiz_sps_options['juiz_sps_counter']) && $juiz_sps_options['juiz_sps_counter']==1)
				wp_enqueue_script( 'juiz_sps_scripts', JUIZ_SPS_PLUGIN_URL.'/js/'.JUIZ_SPS_SLUG.'.js', array('jquery'), false, true );
		}
	}

	add_action('the_content', 'juiz_sps_print_links', 1, 10);
	function juiz_sps_print_links($content) {

		global $post;

		$juiz_sps_content = '';
		$juiz_sps_options = get_option( JUIZ_SPS_SETTING_NAME );


		if ( is_array($juiz_sps_options) ) {

			$juiz_sps_target_link = ($juiz_sps_options['juiz_sps_target_link']==1) ? ' target="_blank"' : '';
			$juiz_sps_hidden_name_class = ($juiz_sps_options['juiz_sps_hide_social_name']==1) ? ' juiz_sps_hide_name' : '';

			$juiz_sps_content  = '<div class="juiz_sps_links">';
			$juiz_sps_content .= '<p class="screen-reader-text juiz_sps_maybe_hidden_text">'.__('Share the post','jsps_lang').' "'.get_the_title().'"</p>';
			$juiz_sps_content .= '<ul class="juiz_sps_links_list'.$juiz_sps_hidden_name_class.'">';

			foreach($juiz_sps_options['juiz_sps_networks'] as $k => $v) {
				if( $v[0] == 1 ) {
					$api_link = $api_text = '';
					$text = urlencode($post->post_title);
					$url = urlencode($post->guid);
					switch ($k) {
						case "twitter" :
							$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$url.'&amp;text='.$text.'&amp;url='.$url;
							$api_text = __('Share this article on Twitter','jsps_lang');
							break;

						case "facebook" :
							$api_link = 'https://www.facebook.com/sharer/sharer.php?u='.$url;
							$api_text = __('Share this article on Facebook','jsps_lang');
							break;

						case "google" :
							$api_link = 'https://plus.google.com/share?url='.$url;
							$api_text = __('Share this article on Google+','jsps_lang');
							break;

						case "pinterest" :
							$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
							$api_text = __('Share an image of this article on Pinterest','jsps_lang');
							break;

						case "viadeo" :
							$api_link = "http://www.viadeo.com/shareit/share/?url=".$url;
							$api_text = __('Share this article on Viadeo','jsps_lang');
							break;

						case 'linkedin':
							$api_link = "http://www.linkedin.com/shareArticle?mini=true&amp;ro=true&amp;trk=JuizSocialPostSharer&amp;title=".$text."&amp;url=".$url;
							$api_text = __('Share this article on LinkedIn','jsps_lang');
							break;

						case 'digg':
							$api_link = "http://digg.com/submit?phase=2%20&amp;url=".$url."&amp;title=".$text;
							$api_text = __('Share this article on Digg','jsps_lang');
							break;

						case 'stumbleupon':
							$api_link = "http://www.stumbleupon.com/badge/?url=".$url;
							$api_text = __('Share this article on StumbleUpon','jsps_lang');
							break;

						case "mail" :
							$api_link = 'mailto:?subject='.__('Visit this link','jsps_lang').'&amp;body='.__('Hi, I found this information for you! Have a nice day :)','jsps_lang')." : ".$url;
							$api_text = __('Share this article with a friend (email)','jsps_lang');
							break;
					}

					$juiz_sps_content .= '<li class="juiz_sps_link_'.$k.'"><a href="'.$api_link.'" rel="nofollow" title="'.$api_text.'"'.$juiz_sps_target_link.'><span class="juiz_sps_icon"></span><span class="juis_sps_network_name">'.$v[1].'</span></a></li>';

				}
			}

			$juiz_sps_content .= '</ul>';
			$juiz_sps_content .= '</div>';

		}

		return $content.$juiz_sps_content;
	}

}
