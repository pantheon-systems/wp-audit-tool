=== WordPress Audit Tool ===
Contributors: jkudish
Tags: audit help hosting
Tested up to: 6.5
Stable tag: 1.0.0
Requires at least: 5.0

Pantheon's WP Audit Tool is a plugin that extends the WordPress site health.

== Description ==

Pantheon's WP Audit Tool is a plugin that extends the WordPress site health with several additional health checks. It also provides several methods for easily accessing/exporting the data from the health checks.

This plugin provides the following new site health checks:

* Several additional PHP configuration items
* Checks for plugins with known issues on Pantheon (as per https://docs.pantheon.io/plugins-known-issues)
* Provides a list of non-standard WP DB Tables (e.g. those added by plugins or custom ones)
* Provides a list of individual DB tables sizes
* Provides a list of WP cron hooks and their schedules
* Provides a list of sys cron (if any) registered via crontab
* Provides an export of environment variables (but obfuscates sensitive ones)
* Provides a report on the number of ACF groups and fields

These new health checks are shown in the wp-admin site health page along with WordPress core's default site health checks.

This plugin provides a WP-CLI command to view/export all site health data. The command is accessible via `wp audit`

In addition to the WP-CLI command, this plugin provides a script that can be run when WP-CLI isn't available. This script can also be run without activating the plugin. This is handy when needing to quickly grab site health data without needing access to wp-admin or having wp-cli installed.

To run the script: drop this plugin into the plugins directory, then run the script like so:

`php path/to/wp-content/plugins/wp-audit-tool/scripts/audit.php`

The script will provide an output similar to WP-CLI (just with slightly less formatting niceties).

== Frequently Asked Questions ==

= Does this plugin send data to any external service? =

No. This plugin only provides data in the site's health wp-admin page and via scripts. Data is never sent to any external service.

== Screenshots ==

1. Overview of the new site health checks that the plugin provides
2. Example of the plugins with known issues on Pantheon section
3. Example of the non-core DB tables section

== Upgrade Notice ==

= 1.0.0 =
Initial Release

== Changelog ==

= 1.0.0 =
Initial Release
