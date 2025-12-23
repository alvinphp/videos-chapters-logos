=== videos chapters & logos ===
Contributors: alvingil
Tags: chapters, logo, mp4, video player, markers
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The "Videos Chapters & Logos" plugin allows you to embed MP4 videos with a logo, poster, and markers on your WordPress site.

With this plugin, you can add an MP4 video with a logo (and an optional poster), as well as set chapter points within the video that are displayed as markers. Users can click on the markers to jump to different parts of the video.

**Important:** For the plugin to work correctly, MP4 videos must be placed in the `assets/video/` folder inside the plugin directory.

== Installation ==

1. **Upload the plugin to your WordPress site**:
   - Upload the plugin folder to `/wp-content/plugins/`.

2. **Activate the plugin**:
   - Go to the "Plugins" section in your WordPress admin panel and click "Activate" next to "Videos Chapters & Logos".

3. **Add the shortcode to your content**:
   - After activating the plugin, you can add the following shortcode to any page, post, or widget:
   
     ```[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="800" height="450" autoplay="false" loop="true" markers="0:39=Introduction,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4"]```
   
   - **Shortcode parameters**:
     - **video**: The file path of the MP4 video (default: `Sintel.mp4`).
     - **logo**: The logo to display over the video (default: `example_logo.png`).
     - **poster**: The poster image that appears before the video starts (optional).
     - **width**: The width of the video (default: `640`).
     - **height**: The height of the video (default: `360`).
     - **autoplay**: Whether the video should autoplay (options: `true`, `false`, default: `false`).
     - **loop**: Whether the video should loop when finished (options: `true`, `false`, default: `false`).
     - **markers**: The markers in the video in the format `time=text`, for example, `0:39=Introduction, 5:50=Chapter 2`.

== Frequently Asked Questions ==

= How can I add more than one marker? =

You can add multiple markers by separating them with commas in the `markers` attribute, like this:

```[videos_chapters_logos video="Sintel.mp4" logo="logo.png" width="800" height="450" autoplay="false" loop="true" markers="0:39=Introduction,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4"]```

Each marker is defined in the format `time=text`.

= How can I customize the video design? =

You can adjust the `width` and `height` parameters to change the video size. Additionally, the logo can be customized with any image you upload to your media library.

= Can I use other video formats besides MP4? =

Currently, the plugin is configured to work with MP4 files. If you'd like to support additional video formats, you would need to customize the code.

== Changelog ==

= 1.0 =
* First version of the plugin.

== Upgrade Notice ==

= 1.0 =
First version of the plugin. No important updates yet.

== License ==

This plugin is licensed under the GPLv2 or later. You can redistribute and modify it under the terms of the GNU General Public License.
