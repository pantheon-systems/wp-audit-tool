<?php

class WP_Audit_Tool_Health_Check_Env_Variables
{
	public static function results($info)
	{
		$fields = self::get_env_fields();
		$info['sys-cron'] = [
			'label' => __('Environment Variables', 'wp-audit-tool'),
			'fields' => $fields,
		];

		return $info;
	}

	protected static function get_env_fields()
	{
		if (empty($_ENV)) {
			return [
				[
					'label' => __('No environment variables detected', 'wp-audit-tool'),
				]
			];
		}

		foreach ($_ENV as $key => $value) {
			if (str_contains( $key, 'AUTH') || str_contains( $key, 'SALT') || str_contains( $key, 'PASSWORD') || str_contains( $key, '_KEY') ) {
				$value = '******** (obfuscated)';
			}

			$fields[] = [
				'label' => $key,
				'value' => $value,
			];
		}

		return $fields;
	}
}
