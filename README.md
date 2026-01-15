# Download Link Manager Pro

**Download Link Manager Pro** is a powerful yet simple WordPress plugin to manage, track, and secure your download links. It allows you to mask direct file URLs, track download counts, enforce countdown timers, and password-protect your files.

## Features

*   **Download Management**: Create and manage download links via a custom post type.
*   **Link Masking**: Hide real file paths (e.g., `your-site.com/?dlm-download=123`) to prevent hotlinking.
*   **Download Tracking**: Count every download hit. View basic stats in the admin panel.
*   **Shortcodes**: Easily insert download buttons into posts/pages with a user-friendly "Insert Download Link" popup.
*   **Styles**: Choose between Button, Link, or Box styles.
*   **Smart Download Page**:
    *   **Countdown Timer**: Force users to wait before downloading (great for SEO/Ads).
    *   **Password Protection**: Require a password to unzip or access files (display only).
    *   **Ad Spots**: Place for inserting ad codes on the download page.
*   **Silent Tracking**: Advanced JavaScript tracking that supports opening in new tabs without blocking user experience.

## Installation

1.  Upload the `download-link-manager` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Go to **Downloads** in the admin menu to start adding your files.

## Usage

### Adding a Download
1.  Go to **Downloads > Add New**.
2.  Enter a title and the direct **File URL**.
3.  (Optional) Set a version, password, or countdown time.
4.  Publish.

### Inserting into Post
1.  Edit any Post or Page.
2.  Click the **"Chèn Download Link"** button (next to Add Media).
3.  Select your file, choose a style, and click Insert.

### Shortcode Manual Usage
`[download_link id="123" text="Download Now" style="button" show_count="yes"]`

*   `id`: The download post ID.
*   `text`: Custom button text.
*   `style`: `button`, `link`, or `box`.
*   `show_version`: `yes` or `no`.
*   `show_count`: `yes` or `no`.

## Changelog

### 2.0.0
*   Major upgrade: Added Download Page template.
*   Added Countdown Timer & Password protection display.
*   Refactored Tracking system (AJAX/Beacon).
*   Added "Insert Download Link" popup UI.

### 1.0.0
*   Initial Release.

## Support & Donate

If you find this plugin useful, please consider supporting the development!

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-Donate-orange?style=for-the-badge&logo=buy-me-a-coffee)](https://www.buymeacoffee.com/deeaytee)

**Author:** Dat Nguyen (DeeAyTee)  
**Website:** [deeaytee.xyz](https://deeaytee.xyz)
