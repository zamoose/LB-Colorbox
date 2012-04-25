=== Plugin Name ===
Contributors: ZaMoose
Donate link: htttp://literalbarrage.org/blog/code/lb-colorbox
Tags: colors, swatches, Kuler
Requires at least: 3.3
Tested up to: 3.3.2
Stable tag: 0.6
 
Collect and share color swatches, then use them in themes. Easy peasy.

== Description ==

Collect color schemes. Use 'em, display 'em, go nuts.

LB Colorbox is a new way to collect color schemes and tuck them away inside of WordPress for safe keeping. If you've ever admired the palettes available at [ColourLovers](http://www.colourlovers.com/), [StudioPress](http://www.studiopress.com/palettes), [Colllor](http://colllor.com) or elsewhere, you can now recreate that functionality from the safety of your own WordPress home. It stores your swatches as Custom Post Types inside of WordPress.

You can also directly import color schemes from Adobe's [Kuler service](http://kuler.adobe.com/). You'll need to apply for a [Kuler API key](http://kuler.adobe.com/api/) in order to use this feature, but once you've done so, a wealth of options are available to you.
== Installation ==

1. Upload the `lb-colorbox/` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure LB Colorbox options under 'Settings'->'ColorBox'
1. Start adding ColorBoxes under the 'ColorBoxes' menu item.

= Enabling Kuler functionality = 

1. Go to [http://kuler.adobe.com/api](http://kuler.adobe.com/api) and sign up for an Adobe account/Kuler API key
1. Navigate to Settings->ColorBox
1. Enter your Kuler API key
1. (Optional) Set the number of Kulers you wish to retrieve (defaults to 20 at a time)
1. Click Save
1. Kulers will now be listed under the top-level ColorBoxes menu item

== Frequently Asked Questions ==

= Where are the settings? =

Under `Settings->ColorBox`.

= Where are ColorBoxes managed/created? = 

All ColorBoxes can be managed under a new top-level menu item labeled `ColorBoxes`.

= Where are the ColorBoxes displayed (i.e., what's their permalink base)? =

ColorBoxes are viewable at `[YOUR WORDPRESS URL]/colorbox/` by default.

= Where can I view individual ColorBoxes? =

You can view each ColorBox at `[YOUR WORDPRESS URL]/colorbox/[COLORBOX SLUG]/`.

= Can I change the permalink base? =

Not currently.

== Screenshots ==

1. Create your own swatches and save them as Custom Post Types
2. Supports direct imports from Adobe's Kuler tool
3. Custom Post Type
4. Saving a Kuler

== Changelog ==

= 0.6 =
* Fatal error related to `WP_List_Table` should now be fixed.

= 0.5.1 =
* README updates

= 0.5 =
* Initial plugin release.

== Upgrade Notice ==
= 0.6 = 
No more fatal WP_List_Table errors

= 0.5.1 =
README updates

= 0.5 =
Intial plugin release.
