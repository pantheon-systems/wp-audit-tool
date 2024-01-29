<?php

class WP_Audit_Tool_Health_Check_PHP 
{
    public static function results($info) {
        $info['wp-server']['fields']['php_ini_loaded_file'] = [
            'label' => __('PHP.ini loaded file', 'wp-audit-tool'),
            'value' => php_ini_loaded_file(),
            'debug' => php_ini_loaded_file(),
        ];
     
        $info['wp-server']['fields']['php_ini_scanned_files'] = [
            'label' => __('PHP.ini scanned files', 'wp-audit-tool'),
            'value' => php_ini_scanned_files(),
            'debug' => php_ini_scanned_files(),
        ];
        
        $info['wp-server']['fields']['display_errors'] = [
            'label' => __('PHP display errors', 'wp-audit-tool'),
            'value' => ini_get('display_errors') ? __( 'Yes' ) : __( 'No' ),
            'debug' => ini_get('display_errors'),
        ];
     
        $info['wp-server']['fields']['register_globals'] = [
            'label' => __('PHP.ini registered globals', 'wp-audit-tool'),
            'value' => ini_get('register_globals'),
            'debug' => ini_get('register_globals'),
        ];
        
        $info['wp-server']['fields']['register_globals'] = [
            'label' => __('PHP loaded extensions', 'wp-audit-tool'),
            'value' => implode(", ", get_loaded_extensions()),
            'debug' => get_loaded_extensions(),
        ];
     
        return $info;
     }
}