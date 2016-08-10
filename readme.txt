=== ACF Content Analysis for Yoast SEO ===
Contributors: ViktorFroberg, marol87, pekz0r, angrycreative
Tags: Angry Creative, Yoast SEO, Yoast, SEO, ACF, Advanced Custom Fields
Requires at least: 4.0
Tested up to: 4.5.3
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ensure that Yoast SEO analysize all ACF content including Flexible Content and Repeaters.
Requires version 3.0 or later of Yoast SEO plugin.

== Description ==

This plugin ensures that Yoast SEO analysize all ACF content including Flexible Content and Repeaters.
Requires version 3.0 or later of Yoast SEO plugin.

= Filters =
`ysacf_exclude_fields`: exclude acf fields from Yoast scoring. Should return array of field names.

Example: exclude the text-color and background_image fields from Yoast scoring.

`
add_filter('ysacf_exclude_fields', function(){
    return array(
        'text_color',
        'background_image'
    );
});
`


== Installation ==

1. Download, unzip and upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress (activate for network if multisite)

== Changelog ==

= 1.2.5 =
* Fix for taxonomies in the latest version of WordPress

= 1.2.4 =
* Fix filter.
* Add check for empty content.

= 1.2.3 =
* Fix PHP warnings.
* Works with WordPress 4.5

= 1.2.2 =
* Fix warning for undefined index
* Fix support for multisite

= 1.2.1 =
* Bugfix for Yoast SEO Premium

= 1.2.0 =
* Add support for alt and title tags in images.
* Security improvements
* Images returned as url will now be analysed as urls.

= 1.1.1 =
* Bugfix for undefined index

= 1.1.0 =
* Add support for taxonomies
* Bug fixes and stability improvements
* Works with WordPress 4.4.1

= 1.0.0 =
* First public release
