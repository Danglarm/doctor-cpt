=== Doctors CPT ===
Contributors: Danglarm
Tags: doctors, medical, cpt, custom post type, doctors directory
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Create a custom post type for doctors with specializations, cities, and custom fields.

== Description ==

A complete solution for creating a doctors directory on your WordPress site.

= Features =

* Custom Post Type "Doctors" with archive
* Specializations taxonomy (hierarchical)
* Cities taxonomy (non-hierarchical)
* Custom fields: Experience, Price From, Rating
* Archive with filtering and sorting
* Responsive templates
* Russian translation included

= Demo Data =

On activation, the plugin creates:
* 8 specializations (Cardiology, Neurology, etc.)
* 5 cities (Moscow, Saint Petersburg, etc.)
* 9 doctors with complete information

= Usage =

1. Install and activate the plugin
2. Demo data will be created automatically
3. Go to Settings → Permalinks → Save Changes
4. Visit /doctors/ to see the archive

== Installation ==

1. Upload `doctors-cpt.zip` via Plugins → Add New → Upload Plugin
2. Activate the plugin
3. Demo data will be created automatically
4. Go to Settings → Permalinks and click "Save Changes"

== Frequently Asked Questions ==

= How do I add my own doctors? =
Go to Doctors → Add New in WordPress admin.

= How do I change the number of doctors per page? =
Add this to your theme's functions.php:
`add_filter('doctors_archive_query_args', function($args) { $args['posts_per_page'] = 12; return $args; });`

= The /doctors/ page shows 404 error =
Go to Settings → Permalinks → Save Changes

== Screenshots ==

1. Doctors archive with filters
2. Single doctor page
3. Admin interface

== Changelog ==

= 1.0.0 =
* Initial release
* Custom post type with taxonomies
* Custom fields
* Archive with filtering
* Demo data on activation

== Upgrade Notice ==

= 1.0.0 =
Initial release.