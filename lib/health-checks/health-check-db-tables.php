<?php

class WP_Audit_Tool_Health_Check_Non_Core_DB_Tables
{
	public static function results($info)
	{
		$db_tables = self::get_non_core_db_tables();
		$fields = self::format_tables($db_tables);

		$info['db-non-core-tables'] = [
			'label' => __('Non-Core Database Tables', 'wp-audit-tool') . ' (' . count($db_tables) . ')',
			'description' => __( 'This reports any database tables that aren\'t from WordPress core', 'wp-audit-tool' ),
			'fields' => $fields,
		];
		
		return $info;
	}

	protected static function get_non_core_db_tables()
	{
		global $wpdb;
		return array_diff($wpdb->get_col("SHOW TABLES", 0), $wpdb->tables());
	}

	protected static function format_tables($db_tables)
	{
		if (empty($db_tables)) {
			return [
				[
					'label' => __('No non-core DB tables detected', 'wp-audit-tool'),
					'value' => '',
				]
			];
		}

		$fields = [];
		foreach ($db_tables as $table) {
			$fields[] = [
				'label' => $table,
				'value' => '',
				'debug' => $table,
			];
		}

		return $fields;
	}
}
