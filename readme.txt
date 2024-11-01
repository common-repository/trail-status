=== Trail Status ===
Contributors: falcon13
Donate link: https://onthegridwebdesign.com/software/trail-status
Tags: trail, trails, outdoors, biking, hiking, widget, shortcode
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 5.6
Stable tag: 2.2
License: GPLv3

Display the status of trails on your website.

== License ==
Released under the terms of the GNU General Public License.

== Description ==
This plugin allows you to display the status of trails on your website. Any user with author or higher permissions can update the status, while only editors and admin can add and update the trails and status names. The trails can have links to pages with more information about them. Both the shortcodes and widget are customizable. Widgets and shortcode can have different sets of trails and notes.

= Features =
*   Widget
*   Shortcode for showing the trails in a list format
*   A second shortcode for showing the trails in a block format
*   Add images to trails for display in the shortcode
*   Placeholder images for trails without images
*   Add links to the trails
*   Set up to 10 statuses including their color
*   Trail name and status can be the status color
*   The order of both trails and statuses can be set
*   Notes can be added at the end which can include embedded videos
*   Separate notes for the shortcode and widget
*   Initial set of trail conditions
*   Admin that allows authors and higher users to update the current conditions, while editors and higher users can create and edit the trails and statuses

DISCLAIMER: Under no circumstances do we release this plugin with any warranty, implied or otherwise. We cannot be held responsible for any damage that might arise from the use of this plugin. Back up your WordPress database and files before installation.

== Installation ==
= Shortcode Usage =
Table List Shortcode: [trail-status]
Blocks Shortcode: [trail-status-blocks]

= Options =
show_images: Show's the trail image. Trails without an image will get a placeholder image. (default: yes)
color_text: Set the text color for the trail name and status by the status. (default: yes)
box_shadow: Add box shadow to trail images. (default: yes)
small_images: If yes, the max-width of the trail images will be 50px. If no, the image will be the full thumbnail size. (default: no)

= Examples =
[trail-status]
[trail-status show_images="yes" color_text="no" box_shadow="yes" small_images="yes"]
[trail-status-blocks show_images="yes" color_text="yes" box_shadow="no" small_images="no"]

== Screenshots ==
1. Shortcode: List Version with Video Embed
2. Widget on 2016 Theme
3. Shortcode: Blocks Version with Simple Note
4. Updating the Status of the Trails
5. Admin Trail List Page
6. Admin Trail Add Page
7. Admin Status List Page with Inline Editing
8. Widget Settings

== Changelog ====
2.2.0 (9/10/2024)
- Under the hood updates: Imported common validation and filter helper files used on our other plugins to replace older functions in admin controller. Updated text output.

2.1.2 (12/9/2022)
- Filter functions and view helper improvements and PHP 8.2 updates.

2.1.1 (12/6/2022)
- Fixes to the Datatables JS Library startup.
- Updated Datatables JS library.
- Activated jQuery UI enhanced radio buttons on Update page.

2.1.0 (2/11/2022)
- Upgraded trail and status list pages to use Datatables Javascript library.
- Fixed bug in filter to allow sort order to have 0 and negative numbers.

2.0.1 (3/3/2018)
- Fixes to problems caused by new security checks.

2.0.0 (3/2/2018)
- Initial WordPress.org release.

== Frequently Asked Questions ==

= What about version 1.0? =
* The first version was a custom plugin that only showed three trails and there were only three statuses to choose from. It was only deployed on one website. The second version of this plugin is a complete rebuild from the first. It allows you to set up to ten different statuses along with custom colors for each. Before it was even complete, the second version was used as the base for two custom plugins.
= Can I use HTML in the notes? =
* Yes. You can also use iframes to add things like embedded videos.
