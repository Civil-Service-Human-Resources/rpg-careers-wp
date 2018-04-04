=== ACF Tab Merge ===
Contributors: enshrined
Donate link: https://enshrined.co.uk
Tags: ACF, Advanced Custom Fields, ACF Tab, Tabs, Merge
Requires at least: 4.0
Tested up to: 4.9.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin merges ACF tabs across field groups into a single tab list.

== Description ==

This plugin merges ACF tabs across field groups into a single tab list.

Very useful if you're re-using field groups across templates and don't want all your tabs to display seperately.

Requires [Advanced Custom Fields](https://www.advancedcustomfields.com) to work.

N.B. This has only been tested on ACF5 so may not work on the 4.x versions.
See [this plugin](https://en-gb.wordpress.org/plugins/acf-merge-group-tabs/) if you're using older versions of ACF.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Changelog ==

= 1.0.2 =
* Remove active class off of merged tabs

= 1.0.1 =
* Updated jQuery selectors to not select sub-tabs as this breaks them. Only get first level tabs!

= 1.0.0 =
* Initial Release