<?php

// uninstall hook
register_uninstall_hook( JUIZ_SPS_FILE, 'juiz_sps_uninstaller' );
function juiz_sps_uninstaller() {
	delete_option( JUIZ_SPS_SETTING_NAME );
}

// activation hook
register_activation_hook( JUIZ_SPS_FILE, 'juiz_sps_activation' );
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
			'juiz_sps_target_link'		=> 0, // news
			'juiz_sps_twitter_user'		=> 'CreativeJuiz',
			'juiz_sps_display_in_types' => array('post'),
			'juiz_sps_display_where'	=> 'bottom',
			'juiz_sps_write_css_in_html'=> 0,
			'juiz_sps_mail_subject'		=> __('Visit this link','jsps_lang'),
			'juiz_sps_mail_body'		=> __('Hi, I found this information for you! Have a nice day :)','jsps_lang')
		);
		
		update_option( JUIZ_SPS_SETTING_NAME , $default_array);
	}
	else {

		// if is version under 1.1.0
		if ( !isset($juiz_sps_options['juiz_sps_display_in_types']) ) {
			$new_options = array(
				'juiz_sps_twitter_user'		=> 'CreativeJuiz',
				'juiz_sps_display_in_types' => array('post'),
				'juiz_sps_display_where'	=> 'bottom',
				'juiz_sps_write_css_in_html'=> 0,
				'juiz_sps_mail_subject'		=> __('Visit this link','jsps_lang'),
				'juiz_sps_mail_body'		=> __('Hi, I found this information for you! Have a nice day :)','jsps_lang')
			);

			$updated_array = array_merge($juiz_sps_options, $new_options);

			update_option( JUIZ_SPS_SETTING_NAME , $updated_array);
		}

	}
}

// description setting page
add_filter( 'plugin_action_links_'.plugin_basename( JUIZ_SPS_FILE ), 'juiz_sps_plugin_action_links',  10, 2);
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
			#juiz-sps .error { margin: 20px 20px 20px 48px; }
			#juiz-sps .error em { font-weight: normal; }
			#juiz-sps h3 { font-size: 1.65em; color: #444; font-weight:normal; }
			#juiz-sps table + h3 { margin-top: 3em;}
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

			#juiz-sps input[type="radio"] + label { display: inline-block; vertical-align: middle; margin-right: 20px;}
			.juiz_sps_options_p { margin: .2em 5% .2em 0; }

			.juiz_sps_styles_options label { vertical-align:top;}
			.juiz_sps_styles_options input { vertical-align:8px;}
			.juiz_sps_demo_styles { display:inline-block; vertical-align:middle; width:592px; height:26px; background:url('.JUIZ_SPS_PLUGIN_URL.'/img/demo-sprites.png) no-repeat 0 -26px}
			[for="jsps_style_2"] .juiz_sps_demo_styles { background-position: 0 0 }
			[for="jsps_style_3"] .juiz_sps_demo_styles { height: 36px; background-position: 0 -93px }
			[for="jsps_style_4"] .juiz_sps_demo_styles { height: 36px; background-position: 0 -129px }
			.juiz_sps_style_name { display:inline-block; margin: 4px 0 0 2px; color: #777;}
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
	add_settings_field('juiz_sps_twitter_user', __('What is your Twitter user name to be mentioned?', 'jsps_lang') , 'juiz_sps_setting_input_twitter_user', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');


	add_settings_section('juiz_sps_plugin_display_in', __('Display settings','jsps_lang'), 'juiz_sps_section_text_display', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_display_in_types', __('What type of content must have buttons?','jsps_lang'), 'juiz_sps_setting_checkbox_content_type', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in');
	add_settings_field('juiz_sps_display_where', __('Where do you want to display buttons?','jsps_lang'), 'juiz_sps_setting_radio_where', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in');


	add_settings_section('juiz_sps_plugin_advanced', __('Advanced settings','jsps_lang'), 'juiz_sps_section_text_advanced', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_counter', __('Display counter of sharing?','jsps_lang').'<br /><em>('.__('need JavaScript','jsps_lang').')</em>', 'juiz_sps_setting_radio_counter', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_hide_social_name', __('Show only social icon?', 'jsps_lang').'<br /><em>('.__('hide text, show it on mouse over or focus', 'jsps_lang').')</em>', 'juiz_sps_setting_radio_hide_social_name', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_target_link', __('Open links in a new window?', 'jsps_lang').'<br /><em>('.sprintf(__('adds a %s attribute', 'jsps_lang'), '<code>target="_blank"</code>').')</em>', 'juiz_sps_setting_radio_target_link', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_write_css_in_html', __('Write CSS code in HTML head?', 'jsps_lang').'<br /><em>('.__('good thing for performance on mobile', 'jsps_lang').')</em>', 'juiz_sps_setting_radio_css_in_html', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');


	add_settings_section('juiz_sps_plugin_mail_informations', __('Customize mail texts','jsps_lang'), 'juiz_sps_section_text_mail', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_mail_subject', __('Mail subject:','jsps_lang'), 'juiz_sps_setting_input_mail_subject', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations');
	add_settings_field('juiz_sps_mail_body', __('Mail body:','jsps_lang'), 'juiz_sps_setting_textarea_mail_body', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations');


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

	// new options (1.1.0)
	$newoptions['juiz_sps_write_css_in_html'] = (int)$options['juiz_sps_write_css_in_html']==1 ? 1 : 0;
	$newoptions['juiz_sps_twitter_user'] = preg_replace( "#@#", '', sanitize_key( $options['juiz_sps_twitter_user'] ) );
	$newoptions['juiz_sps_mail_subject'] = sanitize_text_field( $options['juiz_sps_mail_subject'] );
	$newoptions['juiz_sps_mail_body'] = sanitize_text_field( $options['juiz_sps_mail_body'] );

	if ( is_array($options['juiz_sps_display_in_types']) && count($options['juiz_sps_display_in_types']) > 0 ) {
		$newoptions['juiz_sps_display_in_types'] = $options['juiz_sps_display_in_types'];
	}
	else {
		wp_redirect( admin_url('options-general.php?page='.JUIZ_SPS_SLUG.'&message=1337') );
		exit;
	}
	$newoptions['juiz_sps_display_where'] = in_array($options['juiz_sps_display_where'], array('bottom', 'top', 'both')) ? $options['juiz_sps_display_where'] : 'bottom';
	
	return $newoptions;
}

// first section text
function juiz_sps_section_text() {
	echo '<p class="juiz_sps_section_intro">'. __('Here, you can modify default settings of the SPS plugin', 'jsps_lang') .'</p>';
}

// radio fields styles choice
function juiz_sps_setting_radio_style_choice() {

	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
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

// input for twitter username
function juiz_sps_setting_input_twitter_user() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
		$username = isset($options['juiz_sps_twitter_user']) ? $options['juiz_sps_twitter_user'] : '';
	echo '<p class="juiz_sps_options_p">
			<input id="juiz_sps_twitter_user" value="'.esc_attr($username).'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_twitter_user]" type="text"> <em>('.__('Username without "@"', 'jsps_lang').')</em>
	  	</p>';
	}
}


// Advanced section text
function juiz_sps_section_text_display() {
	echo '<p class="juiz_sps_section_intro">'. __('You can choose precisely the types of content that will benefit from the sharing buttons.', 'jsps_lang') .'</p>';
}
// checkbox for type of content
function juiz_sps_setting_checkbox_content_type() {
	$cpts	= get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => true) );
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if( is_array($options) && isset($options['juiz_sps_display_in_types']) && is_array($options['juiz_sps_display_in_types'])) {
		
		global $wp_post_types;
		$no_icon = '<span style="display:inline-block;width:16px;height:16px;background:url('.admin_url('images/menu.png').') -276px -8px no-repeat;">&#160;</span>';
		
		foreach ( $cpts as $cpt ) {

			$selected = in_array($cpt, $options['juiz_sps_display_in_types']) ? 'checked="checked"' : '';

			$icon = isset($wp_post_types[$cpt]->menu_icon) && $wp_post_types[$cpt]->menu_icon ? '<img alt="&#8226; " src="'.esc_url($wp_post_types[$cpt]->menu_icon).'"/>' : $no_icon;
			echo '<p><input type="checkbox" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_in_types][]" id="'.$cpt.'" value="'.$cpt.'" '.$selected.'> <label for="'.$cpt.'">'.$icon.' '.$wp_post_types[$cpt]->label . '</label>';
		}
	}
}

// where display buttons
// radio fields styles choice
function juiz_sps_setting_radio_where() {

	$options = get_option( JUIZ_SPS_SETTING_NAME );

	$w_bottom = $w_top = $w_both = "";
	if ( is_array($options) && isset($options['juiz_sps_display_where']) )
		${'w_'.$options['juiz_sps_display_where']} = " checked='checked'";
	
	echo '	<input id="jsps_w_b" value="bottom" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_bottom.' />
			<label for="jsps_w_b">'. __('Content bottom', 'jsps_lang') . '</label>
			
			<input id="jsps_w_t" value="top" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_top.' />
			<label for="jsps_w_t">'. __('Content top', 'jsps_lang') . '</label>
			
			<input id="jsps_w_2" value="both" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_both.' />
			<label for="jsps_w_2">'. __('Both', 'jsps_lang') . '</label>';

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
			<label style="color:#777;" for="jsps_counter_y">'. __('Yes', 'jsps_lang') . '</label>
			
			<input id="jsps_counter_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter]" type="radio" '.$n.'  disabled="disabled" />
			<label style="color:#777;" for="jsps_counter_n">'. __('No', 'jsps_lang') . '</label>';
}

// radio fields Y or N for hide text
function juiz_sps_setting_radio_hide_social_name() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_hide_social_name']) AND $options['juiz_sps_hide_social_name']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo "	<input id='jsps_hide_name_y' value='1' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_hide_social_name]' type='radio' ".$y." />
			<label for='jsps_hide_name_y'>". __('Yes', 'jsps_lang') . "</label>
			
			<input id='jsps_hide_name_n' value='0' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_hide_social_name]' type='radio' ".$n." />
			<label for='jsps_hide_name_n'>". __('No', 'jsps_lang') . "</label>";
}

// radio fields Y or N for target _blank
function juiz_sps_setting_radio_target_link() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_target_link']) AND $options['juiz_sps_target_link']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo "	<input id='jsps_target_link_y' value='1' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$y." />
			<label for='jsps_target_link_y'>". __('Yes', 'jsps_lang') . "</label>
			
			<input id='jsps_target_link_n' value='0' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$n." />
			<label for='jsps_target_link_n'>". __('No', 'jsps_lang') . "</label>";
}

// radio to display CSS in html head or not
function juiz_sps_setting_radio_css_in_html() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_write_css_in_html']) AND $options['juiz_sps_write_css_in_html']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo '	<em style="color:#777;">' . __('This option will be enabled for a next version','jsps_lang') . '</em><br />
			<input id="jsps_target_link_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_write_css_in_html]" type="radio" '.$y.' disabled="disabled" />
			<label style="color:#777;" for="jsps_target_link_y">'. __('Yes', 'jsps_lang') . '</label>
			
			<input id="jsps_target_link_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_write_css_in_html]" type="radio" '.$n.' disabled="disabled" />
			<label style="color:#777;" for="jsps_target_link_n">'. __('No', 'jsps_lang') . '</label>';
}


// Mail section text
function juiz_sps_section_text_mail() {
	echo '<p class="juiz_sps_section_intro">'. __('You can customize texts to display when visitors share your content by mail button', 'jsps_lang') .'</p>';
}

function juiz_sps_setting_input_mail_subject() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if(isset($options['juiz_sps_mail_subject']))
		echo '<input id="juiz_sps_mail_subject" value="'.esc_attr($options['juiz_sps_mail_subject']).'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_mail_subject]" type="text">';
}

function juiz_sps_setting_textarea_mail_body() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if(isset($options['juiz_sps_mail_body']))
		echo '<textarea id="juiz_sps_mail_body" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_mail_body]">'.esc_textarea($options['juiz_sps_mail_body']).'</textarea>';
}

// The page layout/form

function juiz_sps_settings_page() {
	?>
	<div id="juiz-sps" class="wrap">
		<div id="icon-options-general" class="icon32">&nbsp;</div>
		<h2><?php _e('Manage Juiz Social Post Sharer', 'jsps_lang') ?></h2>

		<?php if ( isset($_GET['message']) && $_GET['message'] = '1337') { ?>
		<div class="error settings-error">
			<p>
				<strong><?php echo sprintf(__('You must chose at least one %stype of content%s.', 'jsps_lang'), '<a href="#post">', '</a>'); ?></strong><br>
				<em><?php _e('Is you don\'t want to use this plugin more longer, go to Plugins section and deactivate it.','jsps_lang'); ?></em></p>
		</div>
		<?php } ?>
		
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