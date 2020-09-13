=== WP Advanced Menu ===
Contributors: maxpertici
Donate link: 
Tags: Navigation, Menu, Walker, Theme
Requires at least: 5.0
Tested up to: 5.5
Stable tag: 1.7
Requires PHP: 5.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Forget the walker and create powerful navigations with a real theme system.
WP:AM is a new way to quickly create a powerful, modern and personalized navigation menu for your website.
You can create WP:AM themes for your menus in the same way as you create WordPress themes for your websites.

= Warning =
This plugin is intended for developers. It is still a phase of experimentation.
This side-project is still in development, but you already have features bellow integrated into the plugin.

= Features =

* Theme
-- Child Theme
Create child themes by defining a theme as a template. If you need a higher level, you can also define another theme as grand parent: origin.
-- Library
Load your themes (or those of others) from a WordPress theme or plugin. Create your own library, reuse and improve your menus from project to project.
-- Override
Quickly personalize an existing theme with the override system. Copy an item and customize it. That’s it.

* Item
-- Hierarchy
Catch an item easily with the hierarchy template system for post type, taxonomy, archive and custom items.
-- Types
Each menu item now has a type to help you create more advanced menus. WP:AM also offers new types of elements such as the image type or the Gutenberg block.

* Panel
-- Breakpoints
No need to rework your css files, you can now adjust your breakponts directly in the back office.
-- Options
Add your theme options to the back office and configure your menu according to the needs of the current project.

* Misc
-- ACF
WP:AM is built on ACF. So use your favorite fields and build your ideas :)
-- Hooks
WP:AM likes hooks and is starting to create new ones to make life easier for developers.
-- Cache
WP:AM is compatible with cache plugins. Your stylesheets will be properly supported.
-- Translation
WP:AM is translation ready. Fortunately :P

= Documentation =
A draft <a href="https://maxpertici.slite.com/p/note/SyHKacXjEJffPmco7QZPaN">documentation</a> is available (and almost up to date)

== Installation ==
1. Install the plugin and activate.
2. If ACF is missing, install and activate ACF
3. Create a navigation menu
4. Validate location and theme for this menu


== Changelog ==

= 1.7 =
* Fix nav-menu.php WPML compatibility

= 1.6 =
* Fix nav-menu.php page

= 1.5 =
* rename function prefixed with _

= 1.4 =
* security improvment
* fix dist subfolder name

= 1.3 =
* Add WARNING and rewrite readme.txt

= 1.2 =
* Add reusable block item
* Replace element item by content item

= 1.1 =
* FIX element <-> item translation support in nav-menu screen

= 1.0 =
* Launch free version