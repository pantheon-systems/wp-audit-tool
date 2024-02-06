<?php

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