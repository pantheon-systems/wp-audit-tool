# WordPress Audit Tool

Pantheon's WP Audit Tool is a plugin that extends the WordPress site health with several additional health checks. It also provides several methods for easily accessing/exporting the data from the health checks.

## Site Health Checks

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

![Overview of the new site health checks that the plugin provides](/assets/screenshot-1.png "Overview of the new site health checks that the plugin provides")

![Example of the plugins with known issues on Pantheon section](/assets/screenshot-2.png "Example of the plugins with known issues on Pantheon section")

![Example of the non-core DB tables section](/assets/screenshot-3.png "Example of the non-core DB tables section")

## WP-CLI command

This plugin provides a WP-CLI command to view/export all site health data:

### NAME

  `wp audit`

### DESCRIPTION

  Prints the WP Site Health/Debug Data.

### SYNOPSIS

  `wp audit [--sections=<sections>] [--format=<table>]`

### OPTIONS

  `[--sections=<sections>]`
    Specify which sections to display. Comma separated list of section names. Default: all sections.

  `[--format=<table>]`
    Specify the format to export each section in. Options: table, json, csv, yaml. Default: table.

### EXAMPLES

    `wp audit`

    `wp audit --sections=wp-core,wp-paths-sizes`

    `wp audit --sections=pantheon-problematic-plugins,db-non-core-tables --format=json`

## Basic Script

In addition to the WP-CLI command, this plugin provides a script that can be run when WP-CLI isn't available. This script can also be run without activating the plugin. This is handy when needing to quickly grab site health data without needing access to wp-admin or having wp-cli installed.

To run the script: drop this plugin into the plugins directory, then run the script like so:

`php path/to/wp-content/plugins/wp-audit-tool/scripts/audit.php`

The script will provide an output similar to WP-CLI (just with slightly less formatting niceties).