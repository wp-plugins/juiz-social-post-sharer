=== Juiz Social Post Sharer ===
Contributors: CreativeJuiz
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P39NJPCWVXGDY&lc=FR&item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&item_number=%23wp%2djsps&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: social, twitter, google, facebook, digg, stumbleupon, linkedin, pinterest, viadeo, weibo, post, share
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.2.3

Add buttons after your posts to allow visitors share your content (includes no JavaScript mode).

== Description ==

Add buttons after your posts to allow visitors share your content (includes no JavaScript mode).

Select your favorites social networks among a little list.

**Please, use the support forum to tell me bugs encountered**


Social networks supported:

* Digg
* Facebook 
* Google+
* LinkedIn
* Mail
* Pinterest
* StumbleUpon
* Twitter
* Viadeo
* Weibo

Options plugin:

* 5 graphic templates available
* Choose from all available networks
* Open links in a new window (deactivated by default)
* Choose to display only the social network icon
* Add your Twitter account name to add "via" during a share
* Choose to display button only on certain type of post
* Choose to display button at the bottom, the top of the content, or both
* Customize mail texts (subject and body)
* Some hooks are available for markup customization (or add some things)
* Use shortcode <code>[juiz_sps]</code> or <code>[juiz_social]</code> where you want
* Use template function <code>juiz_sps()</code> or <code>get_juiz_sps()</code> in your code

Languages available:

* English
* French

<strong>Full <a href="http://creativejuiz.fr/blog/doc/juiz-social-post-sharer-documentation.html">Documentation</a> available</strong>.

Note: the administration page is compatible with <a href="http://wordpress.org/extend/plugins/juiz-smart-mobile-admin" target="_blank">Juiz Smart Mobile Admin</a>

Next major updates:

* Add a counter for each social network
* Write CSS directly in HTML (head) for mobile performance

This tool relies on third-party applications (API), so if you say "it's broken", please tell me why ;)



–––––––––––––––––––––––––––––––––––

Français

––

Ajoutez des boutons après vos publications pour permettre aux visiteurs de partager votre contenu (inclus un mode sans JavaScript)

Sélectionnez vos réseaux sociaux favoris parmis une petite liste.

**Merci d'utiliser le forum de support si vous rencontrez un bogue.**


Les réseaux sociaux supportés :

* Digg
* Facebook 
* Google+
* LinkedIn
* Mail
* Pinterest
* StumbleUpon
* Twitter
* Viadeo
* Weibo

Options du plugin :

* 5 thèmes graphiques de base
* Faites votre choix parmi tous les réseaux disponibles
* Ouverture des liens dans une nouvelle fenêtre (désactivé par défaut)
* Choix de n'afficher que l'icône du réseau social
* Ajout du pseudo Twitter pour ajouter la mention "via" lors d'un partage
* Choix du type de contenu bénéficiant des boutons
* Choix de l'emplacement des boutons (avant, après le contenu, ou les deux)
* Personnalisez les textes du mail (sujet et corps)
* Quelques hooks sont disponibles pour modifier le markup HTML (ou ajouter des choses)
* Utilisez le shortcode <code>[juiz_sps]</code> ou <code>[juiz_social]</code> où vous le souhaitez
* Utilisez la fonction de template <code>juiz_sps()</code> ou <code>get_juiz_sps()</code> dans votre code

Langues disponibles :

* Français
* Anglais

<strong><a href="http://creativejuiz.fr/blog/doc/juiz-social-post-sharer-documentation.html">Documentation</a> complète disponible !</strong>.

Note : la page d'administration est compatible avec <a href="http://wordpress.org/extend/plugins/juiz-smart-mobile-admin" target="_blank">Juiz Smart Mobile Admin</a>

Prochaines mises à jour majeure :

* Ajout d'un compteur pour chaque réseau social
* Écriture du CSS directement dans le HTML (head) pour des raisons de performance sur mobile

Cet outil dépend d'applications tierces (API), donc si vous notez le plugin comme étant cassé ou fonctionnant mal, merci de me dire pourquoi ;)



== Installation ==

You can use one of both method :

**Installation via your Wordpress website**

1. Go to the **admin menu 'Plugins' -> 'Install'** and **search** for 'Juiz Social Post Sharer'
1. **Click** 'install' and **activate it**
1. (optional) Configure the Plugin in **Settings**

**Manual Installation**

1. **Download** the plugin (it's better :p)
1. **Unzip** `juiz-social-post-sharer` folder to the `/wp-content/plugins/` directory
1. **Activate the plugin** through the 'Plugins' menu in WordPress
1. It's finished !


== Frequently Asked Questions ==

Find a complete documentation on <a href="http://creativejuiz.fr/blog/doc/juiz-social-post-sharer-documentation.html">this official documentation</a>

= I can't just use shortcode by deactivating all the checkbox display option in admin option page? since 1.2.0 =
Yes, it's a bug, please, use the plugin version 1.2.2. 

= New style is not visible? =
Please update to 1.1.3

= Some options are not visible (if it's not the first installation, but an update of the plugin) =
Deactivate and reactivate the plugin to force the options rebuild.


== Screenshots ==

1. Themes available
2. After a post

== Other plugins ==

Find my plugins at <a href="http://profiles.wordpress.org/creativejuiz/">http://profiles.wordpress.org/creativejuiz/</a>


== Changelog ==

= 1.2.3 =
* Removes new Facebook API because of the complexity of use for the user (old API always works)

= 1.2.2 =
* New: Facebook and Pinterest new API integrated
* New hook to remove `rel="nofollow"` on links
* New hook to customize container element (div by default)
* New hook to remove intro sentence, or its container tag
* New: to perform customization, you can use %%title%% (insert the post title), %%siteurl%% (insert the site URL) or %%permalink%% (insert the post URL) variables
* Bug fix: you can now use shortcode or template function only by choosing option "I'm a ninja, I want to use the shortcode only!"
* Translation updates (French, English)

= 1.2.1 =
* [juiz_sps] shortcode added (you now have [juiz_social] and [juiz_sps])
* CSS improvement for themes not really well thought ;)

= 1.2.0 =
* New social networks available : weibo
* CSS improvement
* Documentation available! (see the bottom of settings page)
* New hooks and template functions available (see the documentation)

= 1.1.4 =
* New choice: displaying button on all lists of articles (blog, archive, tag, search result, etc.)
* Admin page improvement
* New dynamic classes on HTML generated code
* Partial documentation available with plugin (see the footer links)

= 1.1.3 =
* Bug fix on new style

= 1.1.2 =
* New hook for developper (can now hook shared url)
* Styles : New optionnal style for buttons (thanks to <a href="http://tonytrancard.fr">Tony</a>)
* Styles : bug correction for Chrome
* Styles : little margin added before and after line of buttons

= 1.1.1 =
* Styles bug correction

= 1.1.0 =
* Add your Twitter account name to add "via" during a share
* Choose to display button only on certain type of post
* Choose to display button at the bottom, the top of the content, or both
* Some hooks are available for markup customization (or add some things)
* Customize mail texts (subject and body)

= 1.0.1 =
* Performance enhancement (thank you <a href="http://profiles.wordpress.org/juliobox/">Julio</a>)
* Some typos corrected

= 1.0.0 =
* The first beta version

== Upgrade Notice ==

= 1.2.2 =
Several bug fixes, you can update ;)

= 1.1.0 =
Some new things, update it :)

= 1.0.0 =
Try it ;)
