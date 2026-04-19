## 📌 Description

The "Videos Chapters & Logos" plugin allows you to embed MP4 videos with a logo, poster, and markers on your WordPress site.

With this plugin you can:
- Embed MP4 videos in posts, pages, and widgets  
- Display a custom logo over the video player  
- Add an optional poster image before playback starts  
- Create chapter markers inside the video timeline  
- Allow users to click markers to jump to specific timestamps  

⚠️ Important: MP4 videos must be placed inside the `assets/video/` folder of the plugin.

---

## ⚙️ Installation

1. Upload the plugin folder to:
   /wp-content/plugins/

2. Activate the plugin in WordPress:
   - Go to Plugins
   - Click Activate on “Videos Chapters & Logos”

3. Use the shortcode:

[videos_chapters_logos video="Sintel.mp4" logo="logo.png" poster="poster.png" width="800" height="450" autoplay="false" loop="true" markers="0:39=Introduction,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4"]

---

## 🧩 Shortcode Parameters

video → MP4 file name (default: Sintel.mp4)  
logo → Logo image file  
poster → Poster image file  
width → Video width (default: 640)  
height → Video height (default: 360)  
autoplay → true / false  
loop → true / false  
markers → Chapter markers in format time=text  

Example:
0:39=Introduction,5:50=Chapter 2,7:50=Chapter 3

---

## ❓ Frequently Asked Questions

How do I add multiple markers?
0:39=Intro,5:50=Chapter 2,7:50=Chapter 3,9:00=Chapter 4

Can I customize the size?
Yes, using width and height attributes.

Can I use formats other than MP4?
No, only MP4 is supported.

---

## 📝 Changelog

1.0
- Initial release
- MP4 video support
- Logo overlay
- Chapter markers

---

## ⬆️ Upgrade Notice

1.0
First stable version.

---

## 📜 License

GPLv2 or later. Free to use, modify, and redistribute under the GNU General Public License.
