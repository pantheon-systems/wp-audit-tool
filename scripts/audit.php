<?php

/*
 * This script can be run when WP-CLI isn't available. This script can also be run without activating the plugin. 
 * This is handy when needing to quickly grab site health data without needing access to wp-admin or having wp-cli installed.
 * 
 * To run the script: drop this plugin into the plugins directory, then run the script like so:
 *  php path/to/wp-content/plugins/wp-audit-tool/scripts/audit.php
*/

require_once __DIR__ . '/../../../../wp-blog-header.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/update.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
require_once __DIR__ . '/../lib/health-checks.php';

WP_Site_Health::get_instance();
WP_Debug_Data::check_for_updates();
$info = WP_Debug_Data::debug_data();

print WP_Debug_Data::format($info, 'info');