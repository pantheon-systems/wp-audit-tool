<?php

add_filter('debug_information', 'wp_audit_tool_debug_information');
function wp_audit_tool_debug_information($info) {
    foreach( glob(__DIR__ . '/health-checks/*.php') as $file ) {
        require_once $file;
    }

   $info = WP_Audit_Tool_Health_Check_PHP::results($info);
   $info = WP_Audit_Tool_Health_Check_Plugins_Known_Issues::results($info);
   $info = WP_Audit_Tool_Health_Check_WP_Cron::results($info);
   $info = WP_Audit_Tool_Health_Check_ACF::results($info);

   return $info;
}