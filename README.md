# Tag Manager Injector

Small WordPress plugin that injects the Google Tag Manager (GTM) snippets into your site without requiring a third-party plugin.

Usage
-----

- After activating the plugin, go to WordPress admin -> Settings -> "Tag Manager Injector".
- Enter your GTM container ID (example: `GTM-ABC1234`) and save.
- Visit the front-end, view page source, and search for `gtm.js?id=` to confirm the container ID is present. The noscript iframe will also include the ID.


Installation
-----

Easiest method (recommended)
----------------------------

- Download the repository ZIP from GitHub (Code → Download ZIP) or use the archive link:

	https://github.com/MiloLockhartOE/Tag-Manager-Injector/archive/refs/heads/main.zip

- In your WordPress admin, go to Plugins → Add New → Upload Plugin.
- Choose the downloaded ZIP file and click Install Now, then Activate.

Notes
-----

- WordPress expects the plugin file (`Tag-Manager-Injector.php`) to live at the top level inside the plugin folder. If the downloaded ZIP contains an extra nested folder (for example `Tag-Manager-Injector-main/Tag-Manager-Injector/`), extract locally and re-zip only the `Tag-Manager-Injector` folder before uploading.
- After activation, configure the GTM ID at Settings → Tag Manager Injector.


Notes
-----

- The plugin uses the saved option as the primary source for the GTM ID. If no value is saved, it will fall back to the `MY_GTM_ID` constant in `Tag-Manager-Injector.php` when that constant is set to something other than the placeholder `GTM_ID`.
- The settings page requires the `manage_options` capability (admins).
- The plugin sanitizes input and accepts IDs matching the pattern `GTM-XXXX` (alphanumeric, underscores, hyphens).

Security
--------

Only administrators can change the GTM ID. The plugin validates input and escapes output before printing into the page.

Support
-------
Open an issue in the repository if you need help or want additional features.
