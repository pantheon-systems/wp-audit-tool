<?php
/*
 Plugin Name: WP Audit Tool
 Plugin URI: https://github.com/pantheon-systems/wp-audit-tool
 Description: Pantheon's WP Audit Tool is a plugin that provides a suite of tools to help audit a WordPress site's configuration.
 Author: Pantheon, jkudish
 Version: 1.0.0
 Author URI: https://pantheon.io
 Text Domain: wp-audit-tool
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/lib/health-checks.php';
