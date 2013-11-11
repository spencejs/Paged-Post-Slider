# Paged Post Slider
Contributors: spencejosiah  
Donate link: http://josiahspence.com/  
Tags: slider, paged posts, pagination, ajax, carousel  
Requires at least: 3.5.1
Tested up to: 3.7.1
Stable tag: 1.3
License: GPLv2  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automagically turns multi-page posts into an ajax-based slideshow. Simply activate, choose the display options for your slider, and go!

## Description

Wordpress has an excellent, but little known, [feature](http://codex.wordpress.org/Styling_Page-Links) for splitting up long posts into multiple pages. However, a growing trend among major news and blog sites is instead to split up posts into dynamically loading sliders. While there are many slider plugins available for Wordpress, none of them quite tackles this functionality. That's where the Paged Post Slider comes in: it takes normal multi-page posts from Wordpress and replaces them with an all-ajax slider that requires almost no setup.

What the slider does:

*   Replaces Wordpress' built-in post pagination funtionality with an ajax-based carousel.
*   Uses hash based URLs for easy direct linking to specific slides. This also preserves the functionality of the browser's Back button.
*   Automatically adds slide navigation and a slide counter (e.g. '1 of 5') to sliders according to the preferences you set.
*   Adds the 'Insert Page Break' button to the TinyMCE post editor so that you can easily split your content into multiple pages/slides.
*   Provides an optional stylesheet for (very) basic styling of the slider navigation.
*   Degrades gracefully. If the plugin is missing or uninstalled, posts will behave exactly like normal multi-page posts.

### Demo:

See a [demo](http://codecarpenter.com/freebie/wordpress-plugin-paged-post-slider/paged-post-slider-demo/) of the slider in action.

## Installation

1. Upload the 'paged-post-slider' directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Customize your display options on the PPS Settings page
1. Make paginated posts using the newly visible 'Insert Page Break' button in the post editor
1. Watch the magic happen!

## Frequently Asked Questions

### How do I split up my posts into different slides?

Just treat it like a normal Wordpress multi-page post. To make this extra-easy, the plugin activates the 'Insert Page Break' button in the post editor. Just insert your cursor wherever you want to break between slides and click the button - Presto! You have a new slide!

For more information about Wordpress' built-in multi-page post funtionality, visit [this page](http://codex.wordpress.org/Styling_Page-Links).

### Why am I seeing an extra Next/Previous navigation element in my theme?

Your theme contains its own `wp_link_pages()` tag to accomodate Wordpress' built-in post pagination feature. To ensure that this does not interfere with the plugin, please remove any reference to the  `wp_link_pages()` tag from your `single.php` file. Note that it is possible that the tag is inluded in a template part, rather than directly in the `single.php` file itself. 

### How can I change the way the slider looks?

The Paged Post Slider is designed to be syled by the user using standard CSS. On the plugin's Settings page, you can choose to use the included styles, but even these are meant only as a basic starting point.

## Screenshots

1. An example slide using the included styles in the 2012 theme. 
2. The Paged Post Slider Settings page.
3. An example of a post broken up into slides on the backend. Note the 'Insert Page Break' button.

## Changelog

= 1.3 =
* Adds support for additional permalink structures. Stuctures now include Wordpress default, trailing slash, no trailing slash, ending in .html, and ending in .htm.

= 1.2.7 =
* Adds Paged Post functionality to Pages as well as Single Posts

= 1.2.6 =
* Fixes problem where scripts would not load

= 1.2.5 =
* Fixes possible conflict with other plugins

= 1.2.4 =
* Restricted script and css to only load on posts that use the slider

= 1.2.3 =
* Cleaned up TinyMCE function

= 1.2.2 =
* Fixed tagging.

= 1.1 =
* CSS styles now clear any floats in a slide.
* Added link to demo page.

= 1.0 =
* Intial commit.