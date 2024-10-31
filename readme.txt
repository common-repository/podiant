=== Podiant ===
Contributors: amarksteadman
Tags: podcasting
Requires at least: 4.4.0
Tested up to: 5.4
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.2

Sync your Podiant podcasts with your WordPress website.

== Description ==

While [Podiant](https://podiant.co/) provides a first-class website for podcasts and networks, some creators need greater control and flexibility. The Podiant WordPress plugin lets podcasters seamlessly sync their episodes to their WordPress site.

== Installation ==

1. Download the plugin and unzip it to your site's `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Login to your Podiant dashboard, at https://app.podiant.co/ and select your podcast
4. Head to "Apps and integrations" and copy your "Pull key" by clicking the text labeled "Copy to clipboard" underneath the "Pull key" heading.
5. Within WordPress, go to the new "Podiant" menu, click "Settings" and "Podcasts", and paste in the Pull Key from the earlier step.

== Frequently Asked Questions ==

= Can I add multiple podcasts to one WordPress site? =

Yes! If you have multiple podcasts with Podiant, either separately or as part of a network, you can add them to your WordPress site.

= How does syncing work? =

The plugin creates a new type of post, called Episode, which you can show in your main loop (the list of posts on the homepage), or via a separate page.

The plugin periodically checks with Podiant, to see if new episodes are available, or any changes have been made. Because we use your Podiant Pull Key, the plugin will sync scheduled episodes, which will only go live on WordPress when your content goes live on Podiant.

When a new episode is uploaded, the plugin will add the artwork and the text of the episode's show notes to a new Episode post within WordPress. When you view the episode in your published WordPress site, a player will appear, similar to the one you'll find on your Podiant website.

= Can I customize the player? =

Yep. You'll find settings for the player in the Podiant > Settings menu within your WordPress dashboard. You can pick the style of player, and whether it appears above or below the show notes.

= Do I need to host my podcast with Podiant? =

Yes. You can do so by signing up for an account at https://app.podiant.co/. You can review our terms and conditions at https://podiant.co/terms/.

== Credits ==

* [WP Background Processing](https://github.com/deliciousbrains/wp-background-processing), by [Delicious Brains](https://github.com/deliciousbrains)
* [Parsedown](https://parsedown.org), by [Emanuil Rusev](https://github.com/erusev)

== More to Come ==

This is the initial release of the plugin, to help our customers get started with WordPress quickly and easily. We'll be releasing improvements regularly.

If you have any questions, you can ask them in the [Podiant forum](https://forum.podiant.co/).

== Changelog ==

= 1.0 =
* Plugin released
