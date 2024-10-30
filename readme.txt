=== Minimum Comment Length ===
Contributors: joostdevalk
Tags: comment, comments, spam
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.1
License: GPLv3

Check the comment for a set minimum length and disapprove it if it's too short. Make your commenters leave meaningful comments!

== Description ==

Don't you find it annoying when someone comments with just a smiley? Or a "thanks"? On quite a few forums you have to leave a comment with a minimum of 15 characters to be able to comment, but WordPress doesn't allow you to set such a threshold. With this plugin, it does. It easily checks the comment for a set minimum length and disapprove it if it's too short (except if you're either an editor or an admin).

More info:

* [Minimum Comment Length](https://yoast.com/wordpress/plugins/minimum-comment-length/).
* Check out the other [Wordpress plugins](https://yoast.com/wordpress/plugins/) by the same authors.

== Installation ==

Installation is easy:

* Download the plugin or search for "minimum comment length" in your WordPress admin panel.
* If you downloaded it, copy the folder `minimum-comment-length` to the plugins directory of your blog.
* Activate the plugin.

Optional:

* An options panel will appear under Options.
* Decide on which comment length you'd like to block and what error to throw when a comment is too short.

== Screenshots ==

1. Screenshot of the admin panel.
2. Example warning for comment that's too short.

== Changelog ==

= 1.1 =

* Added a ton of translations, plugin now includes: Bosnian, Bulgarian, Chinese, Danish, Dutch, French (France), German, Greek, Hebrew, Indonesian, Italian, Malay, Persian, Polish, Portuguese (Brazil), Portuguese (Portugal), Russian, Spanish (Spain), Swedish, Turkish, Romanian.
* Updated the code slightly to be more in line with current core coding standards.
* Moved screenshots to assets directory.

= 1.0.1 =

* Fixed major bug in comment length detection causing no comments to come through.
* Added French and Portuguese translations.

= 1.0 =

* Added inline & PHPdoc documentation.
* Made the entire plugin ready for i18n, you can find the .pot file in /languages/, please email translations to the author.
* Added Dutch translation.
* The minimum length check is now also removed for editors.
* Make the plugin use the options API.
* Moved the entire plugin into 1 class.
* Improved the look of the settings screen.
* Removed Ozh admin menu icon.

= 0.6 =

* Added settings link to plugins page.
* Remove the check for admin users.

= 0.5 =

* Initial version