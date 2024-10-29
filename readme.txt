=== Useful Admin Menu ===
Contributors: ioannup
Donate link:
Tags: admin, admin menu, auto, hide, search
Requires at least: 5.0
Requires PHP: 5.6
Tested up to: 6.2
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

The plugin automatically hides unused Admin Menu items and adds search box for menu items

If you use a lot of plugins - each of them adds their own admin menu item. The admin menu grows more and more. And though you don't use the majority of menu items, they bother you to find the certain menu item.
This small plugin solves this issue AUTOMATICALLY. It means you don't need to enable or disable some menu items manually. If you use a menu item it is on the menu if you don't use it a certain time (which you can set in the settings) - it will be hidden but you'll have access to them anyway.

### Plugin Features

* Search menu and submenu items
* Auto hides unused Admin Menu items
* These settings personal for each user
* Reset settings
* Reset clicks - to start collecting data and hiding unused menu items from scratch
* Set Menu Items which should be always shown or hidden

== Installation ==

== Frequently Asked Questions ==
= I installed the plugin and nothing is changed =

We have no information about which menu items you use right after installing the plugin. The changes will be shown in 7 days by default or you can set `Start in` option to `0` days if you would like to see changes immediately.

= The plugin worked some time but now I don't see `show more` option and hidden menu items =

It means you used all menu items for the last 7 days ( by default ) and there is nothing should be hidden for now

== Screenshots ==

1. Settings page
2. Example hiding menus
3. Example showing hidden menus
4. Example Search menus

= Minimum Requirements =

* WordPress 5.0 or greater
* PHP version 5.6 or greater
* MySQL version 5.6 or greater

== Changelog ==
 = 2.0 =
 - [Improvement] Using HTML ID for identification menu item instead of its href
 !!! Note: This version breaks backward compatibility and it requires reconfiguration settings

 = 1.3 =
----------------------------------------------------------------------
 - [Add] Ability to set Menu Items which should be always shown or hidden

 = 1.2.1 =
----------------------------------------------------------------------
 - [Bug] Show More option doesn't show on main network.

 = 1.2 =
----------------------------------------------------------------------
 - [Fix] CSS-styles when menu is collapsed

 = 1.1 =
----------------------------------------------------------------------
 - Add: Search menu and submenu items

 = 1.0 =
----------------------------------------------------------------------
 - Add: support Multisites

 = 0.3.1 =
----------------------------------------------------------------------
 - Fix: wrong folder name

 = 0.3 =
----------------------------------------------------------------------
 - Add Settings link to Plugins page;
 - Fix: hide items with updates when there are 0 updates

 = 0.2.1 =
----------------------------------------------------------------------
 - Fix: Hide other options only on the plugin's settings page

 = 0.2 =
----------------------------------------------------------------------
 - Add option Enable/Disable
 - Add option Show menu items with updates
 - Change default value to 0 for `Start in days` option
 - Fix: remove double separators
 - Fix: apply updated options immediatelly (without additional refresh)

 = 0.1 =
----------------------------------------------------------------------
 - The first version
