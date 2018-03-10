=== Custom Shortcodes (FT) ===
Contributors: filippo.toso
Tags: shortcodes
Requires at least: 4.9.0
Tested up to: 4.9.4
Requires PHP: 5.6
License: MIT License
License URI: https://opensource.org/licenses/MIT

A simple WordPress plugin to create custom shortcodes from the WordPress control panel.

== Description ==

If you build website for work you'll probably aready know the "shordcode hell".
It's that situation where you keed adding shortcodes in the functions.php file
of your website theme up to the point where you lose control.

Using this plugin you can create custom shortcodes in HTML / CSS / JS and PHP
directly form the WordPress control panel and they will work right away!

Just create, test and activate them.

== Installation ==

You can easily install this plugin in your WordPress site following these steps:

1. Download the ZIP archive.
2. Upload the custom-shortcodes folder into your WordPress plugins folder.
3. Activate the plugin.
4. Navigate to Tools -> Custom Shortcodes to create your shortcodes.
5. Enjoy!

== Frequently Asked Questions ==

= Does it use eval()? =

No it uses include() and output buffering.

= Is the code optimized and cacheable by the PHP engine? =

Yes as all shortcodes' code resides in *.php files on the filesystem and are executed through include().

= Who can create a shortcode? =

Only administrators at this point (for obvious security reasons).

== Changelog ==

= 1.0 =
* First stable version.
