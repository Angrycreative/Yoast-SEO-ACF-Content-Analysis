=== Yoast SEO - ACF Content Analysis ===
Contributors: viktorfroberg, marol87, pekz0r, angrycreative
Tags: Angry Creative, Yoast SEO, Yoast, SEO, ACF, Advanced Custom Fields
Requires at least: 4.0
Tested up to: 4.4.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin ensures that Yoast SEO analysize all ACF content including FlexiContent and Repeaters.
Requires version 3.0 or later of Yoast SEO plugin.

== Description ==

This plugin ensures that Yoast SEO analysize all ACF content including FlexiContent and Repeaters.
Requires version 3.0 or later of Yoast SEO plugin.

= Filters =
`ysacf_exclude_fields`: exceclude acf fields from Yoast scoring. Should return array of field names.

Example: exceclude text-color field from Yoast scoring.

`
add_filter('ysacf_exclude_fields', function(){
    return array(
        'text_color',
    );
});
`


== Installation ==

1. Download, unzip and upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress (activate for network if multisite)

== Changelog ==

= 1.1.0 =
* Bug fixes and stability improvements

= 1.0.0 =
* First public release
