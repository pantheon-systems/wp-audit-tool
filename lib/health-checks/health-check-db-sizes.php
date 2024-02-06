<?php

class WP_Audit_Tool_Health_Check_DB_Sizes
{
	public static function results($info)
	{
		$fields = self::get_db_tables();

		$info['db-table-sizes'] = [
			'label' => __('Database Table Sizes', 'wp-audit-tool'),
			'description' => __( 'This reports all database table sizes', 'wp-audit-tool' ),
			'debug' => 'db-table-sizes',
			'fields' => $fields,
		];
		
		return $info;
	}

	protected static function get_db_tables()
	{
		global $wpdb;
		$db_tables = $wpdb->get_col("SHOW TABLES", 0);

		if (empty($db_tables)) {
			return [
				[
					'label' => __('Couldn\'t detect DB fields', 'wp-audit-tool'),
					'value' => '',
				]
			];
		}

		$fields = [];

		$total_bytes = 0;
		foreach ($db_tables as $table) {
			$table_bytes = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT SUM(data_length + index_length) FROM information_schema.TABLES where table_schema = %s and Table_Name = %s GROUP BY Table_Name LIMIT 1',
					DB_NAME,
					$table
				)
			);
			$total_bytes += $table_bytes;

			$fields[] = [
				'label' => $table,
				'value' => self::bytes_to_human_readable_size($table_bytes),
				'debug' => $table . ': ' . self::bytes_to_human_readable_size($table_bytes),
			];
		}

		$fields[] = [
			'label' => 'Total DB Size',
			'value' => self::bytes_to_human_readable_size($total_bytes),
			'debug' => 'Total DB size: ' . self::bytes_to_human_readable_size($total_bytes),
		];

		return $fields;
	}

	protected static function bytes_to_human_readable_size($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffix[$f_base];
	  }
}
