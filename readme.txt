=== Grid Buddies ===
Contributors: Relevad
Tags: grid, layout, content, easy
Requires at least: 4.2.4
Tested up to: 4.3
Stable tag: 0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display content into custom grid arrangements.

== Description ==

Grid Buddies allow for you to insert a shortcode anywhere onto your Wordpress site and have posts appear in a highly customizable grid arrangement. 
Colors, spacing, pagination, fixed widget areas and more can all be set through the plugin settings page. 

Requirements:

 * PHP version >= 5.3.0 (Dependent on 5.3 functionality. Plugin will not work without 5.3 or higher)
 * Jquery version 1.6 or higher (wordpress 4.1 ships with 1.11.1)
 * Ability to execute wordpress shortcodes in the location(s) you want to place grid arrangements. (see installation)

This plugin was developed by Relevad Corporation. Authors: Gleb Promokhov.

== Installation ==

1. Upload the entire 'gridBuddies' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create a new 'Grid Buddy' through the new admin menu created on the sidebar
4. Grid Buddies can be customized in the following ways:
	* Style
		Set the number of columns, gutter width (space around all sides of each grid box), colors of background and text and max box height.
	* Content
		Set number of posts to show, post order, which category and tags to pull posts from (if none selected, most recently published posts will be used), and set excerpt and navigation options
	* Stamping
		Stamping allows for you to create a widget area on the grid with a fixed position. Grid Boxes around stamped widget areas will automatically position themselves around them.
		Content can be added into these widget areas through the Wordpress 'widgets' menu. 
5. The Shortcode for the Grid Buddy can be found on the top of an induvidual settings page or as the right column in the Grid Buddies table. To place the shortcode on:
	* Pages / Posts: 
		Add the shortcode `[grid-buddy id=#]` to where you want the grid arrangement shown on your post/page.
	* Themes: 
		Add the PHP code `<?php echo do_shortcode('[grid-buddy id=#]'); ?>` where you want the grid arrangement to be shown.
	* Widgets: 
		Add `[grid-buddy id=#]` inside a Shortcode Widget or add `<?php echo do_shortcode('[grid-buddy id=#]'); ?>` inside a PHP Code Widget
		There are many plugins that enable shortcode or PHP in widgets. 
		Here are two great ones: [Shortcode Widget](http://wordpress.org/plugins/shortcode-widget/) and [PHP Code Widget](http://wordpress.org/plugins/php-code-widget/)


== Frequently Asked Questions ==

= Something's not working or I found a bug. What do I do? =

First, please make sure that all Relevad Plugins are updated to the latest version.
If updating does not resolve your issue please contact plugins AT relevad DOT com
or
find this plugin on wordpress.org and contact us through the support tab.

= How do I calculate the margins for my stamped widget areas? = 

For the current version of Grid Buddy, widget areas can only be positioned by specifying their top and left margins.
The distance you set for the top and left margins will set the distance between the top and left side of the widget area from the top and
left of the Grid Buddy container. Take the height and width of the widget area into account when specifying these values. 

= Instead of columns can I organize my posts into rows? Or both rows and columns? =

The current version of Grid Buddy does not include this feature. 

= Does the Grid Buddy dynamically resize with screen resizing? =

The posts will be laid out in accordance to the size of the window on page load, and will not change until the window is resized and the page is refreshed. 

== Screenshots ==

1. Admin table of added Grid Buddies
2. Induvidual style settings of example Grid Buddy.
3. Induvidual stamping settings of example Grid Buddy.
4. Active Grid Buddy.
5. Active Grid Buddy with stamped widget area with Wordpress calender in it.  

== Changelog ==

= 0.7 =

Plugin released.

== Upgrade Notice ==

