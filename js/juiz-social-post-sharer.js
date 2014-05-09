/*
Plugin Name: Juiz Social Post Sharer
Plugin URI: http://creativejuiz.fr
Author: Geoffrey Crofte
*/


/* Juiz SPS core */

;jQuery(document).ready(function($){
	
	jQuery.fn.juiz_get_counters = function(){
		return this.each(function(){
			
			var plugin_url 		= $(this).find('.juiz_sps_info_plugin_url').val();
			var url 			= $(this).find('.juiz_sps_info_permalink').val();

			var $twitter 	= $(this).find('.juiz_sps_link_twitter');
			var $linkedin 	= $(this).find('.juiz_sps_link_linkedin');
			var $delicious 	= $(this).find('.juiz_sps_link_delicious');
			var $facebook 	= $(this).find('.juiz_sps_link_facebook');
			var $pinterest 	= $(this).find('.juiz_sps_link_pinterest');
			var $google 	= $(this).find('.juiz_sps_link_google');
			var $stumble 	= $(this).find('.juiz_sps_link_stumbleupon');



			var twitter_url		= "http://cdn.api.twitter.com/1/urls/count.json?url=" + url + "&callback=?"; 
			// return : {"count":18,"url":"http:\/\/www.creativejuiz.fr\/blog\/"}
			var delicious_url	= "http://feeds.delicious.com/v2/json/urlinfo/data?url=" + url + "&callback=?" ;
			// return : [{"url": "http://www.creativejuiz.fr/blog", "total_posts": 2}]
			var linkedin_url	= "http://www.linkedin.com/countserv/count/share?format=jsonp&url=" + url + "&callback=?";
			// return : {"count":17,"fCnt":"17","fCntPlusOne":"18","url":"http:\/\/stylehatch.co"}
			var pinterest_url   = "http://api.pinterest.com/v1/urls/count.json?callback=?&url=" + url;
			// return : ({"count": 0, "url": "http://stylehatch.co"})
			var facebook_url	= "https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22"+url+"%22";
			// return : {"data": [{"like_count": 6,"total_count": 25,"share_count": 9,"click_count": 0,"comment_count": 10}]}
			var google_url		= plugin_url+"js/get-noapi-counts.php?nw=google&url=" + url;
			var stumble_url		= plugin_url+"js/get-noapi-counts.php?nw=stumble&url=" + url;


			if ( $twitter.length ) {
				$.getJSON(twitter_url)
					.done(function(data){
						$twitter.prepend('<span class="juiz_sps_counter">' + data.count + '</span>');
					});
			}
			if ( $linkedin.length ) {
				$.getJSON(linkedin_url)
					.done(function(data){
						$linkedin.prepend('<span class="juiz_sps_counter">' + data.count + '</span>');
					});
			}
			if ( $pinterest.length ) {
				$.getJSON(pinterest_url)
					.done(function(data){
						$pinterest.prepend('<span class="juiz_sps_counter">' + data.count + '</span>');
					});
			}
			if ( $google.length ) {
				$.getJSON(google_url)
					.done(function(data){
						var count = data.count;
						$google.prepend('<span class="juiz_sps_counter">' + count.replace('\u00a0', '') + '</span>');
					})
			}
			if ( $stumble.length ) {
				$.getJSON(stumble_url)
					.done(function(data){
						$stumble.prepend('<span class="juiz_sps_counter">' + data.count + '</span>');
					})
			}
			if ( $facebook.length ) {
				$.getJSON(facebook_url)
					.done(function(data){
						$facebook.prepend('<span class="juiz_sps_counter">' + data.data[0].share_count + '</span>');
					});
			}
			if ( $delicious.length ) {
				$.getJSON(delicious_url)
					.done(function(data){
						$delicious.prepend('<span class="juiz_sps_counter">' + data[0].total_posts + '</span>');
					});
			}
		});
	}; // juiz_get_counters()

	jQuery.fn.juiz_update_counters = function(){
		return this.each(function(){

			var $group			= $(this);
			var $total_count 	= $group.find('.juiz_sps_totalcount');
			var $total_count_nb = $total_count.find('.juiz_sps_t_nb');
			var total_text = $total_count.attr('title');
			$total_count.prepend('<span class="juiz_sps_total_text">'+total_text+'</span>');

			function count_total() {
				var total = 0;
				$group.find('.juiz_sps_counter').each(function(){
					total += parseInt($(this).text());
				});
				$total_count_nb.text(total);
			}
			setInterval(count_total, 1200);

		});
	}; // juiz_get_counters()

	$('.juiz_sps_links.juiz_sps_counters').juiz_get_counters();
	$('.juiz_sps_counters .juiz_sps_links_list').juiz_update_counters();
});
/*
//var google_url = "https://clients6.google.com/rpc?key=YOUR_API_KEY";
//var digg_url = "http://services.digg.com/2.0/story.getInfo?links=" + url + "&type=javascript&callback=?"; 
*/