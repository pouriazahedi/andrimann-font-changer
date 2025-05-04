=== Fontify ===
Contributors: pouriazahedi  
Tags: custom fonts, woff, woff2, font uploader, typography  
Requires at least: 5.0  
Tested up to: 6.8  
Requires PHP: 7.2  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Upload and apply custom fonts (WOFF or WOFF2) to your entire WordPress site, including admin panel — without writing code.

== Description ==

Fontify lets you upload and apply custom fonts to your WordPress site easily.

With Fontify, you can:
- Upload .woff or .woff2 font files directly from the admin panel.
- Apply the uploaded font site-wide, including the admin dashboard.
- Avoid dealing with CSS or modifying theme files.
- Fall back to system/default fonts if no custom font is uploaded.

Once installed, visit Settings > Custom Font to configure your font.  
Simply upload a WOFF or WOFF2 file, type a font name (e.g., "MyFont"), and you're done.

This plugin is especially useful for branding, RTL websites, or localization needs.

== Installation ==

1. Upload the plugin files to the /wp-content/plugins/fontify directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to Settings > Custom Font.
4. Enter a font-family name (e.g., MyFont).
5. Upload a .woff or .woff2 font file.
6. Click Save Settings.
7. The font will be applied across your site and admin panel.

== Frequently Asked Questions ==

= What font formats are supported? =  
Currently, only .woff and .woff2 font formats are supported.

= Where are uploaded fonts stored? =  
Uploaded fonts are stored in the /wp-content/uploads/custom-fonts/ directory.

= What happens if I don’t upload a font? =  
Your site will continue using the system or theme’s default font.

= Can I use this plugin with any theme? =  
Yes. Fontify works independently of the theme and injects CSS globally.

== Screenshots ==

1. Settings panel for uploading and configuring a custom font.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
First public release of Fontify.