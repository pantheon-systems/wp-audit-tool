<?php

if ( ! defined( 'WP_CLI' ) ) {
    return;
}

class WP_Audit_Tool_CLI_Command {
    protected $environment;
    protected $data;

    public function __construct( ) {
        require_once ABSPATH . 'wp-admin/includes/admin.php';
        require_once ABSPATH . 'wp-admin/includes/update.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
        require_once __DIR__ . '/../lib/health-checks.php';

        $this->environment = wp_get_environment_type();
        WP_Site_Health::get_instance();
        WP_Debug_Data::check_for_updates();
        $this->data = WP_Debug_Data::debug_data();
    }
	
    /**
     * Prints the WP Site Health/Debug Data.
     *
     * ## OPTIONS
     *
     * [--sections=<sections>]
     * : Specify which sections to display. Comma separated list of section names. Default: all sections.
     *
     * [--format=<table>]
     * : Specify the format to export each section in. Options: table, json, csv, yaml. Default: table.
     * 
     * ## EXAMPLES
     *
     *     wp audit
     * 
     *     wp audit --sections=wp-core,wp-paths-sizes
     * 
     *     wp audit --sections=pantheon-problematic-plugins,db-non-core-tables --format=json
     *
     * @when after_wp_load
     */
    public function __invoke( $args, $assoc_args ) {
        $format = WP_CLI\Utils\get_flag_value( $assoc_args, 'format', 'table' );
        $sections = WP_CLI\Utils\get_flag_value( $assoc_args, 'sections', [
            'wp-core',
            'wp-paths-sizes',
            'wp-dropins',
            'wp-active-theme',
            'wp-parent-theme',
            'wp-themes-inactive',
            'wp-mu-plugins',
            'wp-plugins-active',
            'wp-plugins-inactive',
            'wp-media',
            'wp-server',
            'wp-database',
            'wp-constants',
            'wp-filesystem',
            'pantheon-problematic-plugins',
            'db-non-core-tables',
            'db-table-sizes',
            'wp-cron',
            'sys-cron',
            'env-variables',
            'acf',
        ] );
        if (is_string($sections)) {
            $sections = preg_split ("/\,/", $sections);  
        }

        foreach ( $this->data as $section => $sectionData ) {
            if ( ! in_array( $section, $sections )) {
                continue;
            }

            WP_CLI::line('');
            WP_CLI::line( '# ' . $sectionData['label'] );

            // overwrite the formatting for certain sections
            $section_method = 'format_' . str_replace('-', '_', $section);
            if (method_exists($this, $section_method)) {
                $this->{$section_method}( $format, $sectionData );
                continue;
            }

            if (empty($sectionData['fields'])) {
                WP_CLI::line('No data available');
                continue;
            }
            
            WP_CLI\Utils\format_items( $format, $sectionData['fields'], array( 'label', 'value' ) );            
        }
    }
    
    // some sections don't look good in table format due to not fitting into a single line
    // in that case, format them as simple lines when using the table format
    protected function format_as_simple_lines( $format, $sectionData ) {
        if ($format !== 'table') {
            WP_CLI\Utils\format_items( $format, $sectionData['fields'], array( 'label', 'value' ) );            
        }

        foreach($sectionData['fields'] as $field) {
            WP_CLI::line($field['label'] . ': ' . $field['value']);
        }
    }

    protected function format_wp_active_theme( $format, $sectionData ) {
        return $this->format_env_variables($format, $sectionData);
    }
    
    protected function format_wp_media( $format, $sectionData ) {
        return $this->format_env_variables($format, $sectionData);
    }
    
    protected function format_wp_server( $format, $sectionData ) {
        return $this->format_env_variables($format, $sectionData);
    }
    
    protected function format_env_variables( $format, $sectionData ) {
        return $this->format_as_simple_lines($format, $sectionData);
    }

    protected function format_wp_paths_sizes( $format, $sectionData ) {
        $sizes_data = WP_Debug_Data::get_sizes();
        $directories = [];
        foreach($sizes_data as $directory => $size) {
            $directories[] = [
                'Label' => str_replace('_', ' ', $directory),
                'Path' => isset($size['path']) ? $size['path'] : '',
                'Size' => $size['size'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $directories, array( 'Label', 'Path', 'Size' ) );
    }
    
    protected function format_pantheon_problematic_plugins( $format, $sectionData ) {
        $plugins = [];
        foreach($sectionData['fields'] as $field) {
            $plugins[] = [
                'Plugin' => $field['label'],
                'Severity' => $field['debug']['severity'],
                'Url' => $field['debug']['url'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $plugins, array( 'Plugin', 'Severity', 'Url' ) );
    }
    
    protected function format_db_non_core_tables( $format, $sectionData ) {
        $dbs = [];
        foreach($sectionData['fields'] as $field) {
            $dbs[] = [
                'Custom DB Table' => $field['label'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $dbs, array( 'Custom DB Table' ) );
    }
    
    protected function format_db_table_sizes( $format, $sectionData ) {
        $dbs = [];
        foreach($sectionData['fields'] as $field) {
            $dbs[] = [
                'DB Table' => $field['label'],
                'Size' => $field['value'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $dbs, array( 'DB Table', 'Size' ) );
    }
    
    protected function format_sys_cron( $format, $sectionData ) {
        $crons = [];
        foreach($sectionData['fields'] as $field) {
            if ($field['label'] === 'Cron Expression') {
                continue;
            }

            $crons[] = [
                'Cron Expression' => $field['label'],
                'Command' => $field['value'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $crons, array( 'Cron Expression', 'Command' ) );
    }
    
    protected function format_wp_cron( $format, $sectionData ) {
        $crons = [];
        foreach($sectionData['fields'] as $field) {
            if ($field['label'] === 'Hook') {
                continue;
            }

            $crons[] = [
                'Hook' => $field['label'],
                'Schedule / next run' => $field['value'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $crons, array( 'Hook', 'Schedule / next run' ) );
    }
    
    protected function format_acf( $format, $sectionData ) {
        $acf = [];
        foreach($sectionData['fields'] as $field) {
            if ($field['value'] === 'ACF is not installed') {
                WP_CLI::line('ACF is not installed');
                return;
            }

            $acf[] = [
                'Label' => str_replace('Number of ', '', $field['label']),
                'Number' => $field['value'],
            ];
        }

        WP_CLI\Utils\format_items( $format, $acf, array( 'Label', 'Number' ) );
    }
}

WP_CLI::add_command( 'audit', 'WP_Audit_Tool_CLI_Command' );